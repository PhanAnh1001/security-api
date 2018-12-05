<?php

namespace App\Lib;

use Exception;
use Firebase\JWT\JWT;

/**
 * Lib Auth
 * All logic of athenticate Api
 */
class Auth
{
	const PUBLIC_API_BASE = 1;
	const PRIVATE_USER_API_BASE = 2;

	const ROLE_APP = 1;
	const ROLE_USER = 2;

	/**
     * Create a new token.
     * 
     * @param  $params:   object
     * @param  $baseType:   int
     * @return string
     */
    public static function createToken($params, $baseType) {
        $payload = [
            'iss' => "localhost", // Issuer of the token
            'sub' => '', // Subject of the token
            'role' => 0,
            'iat' => time(), // Time when JWT was issued. 
            'exp' => time() + 7*24*3600 // Expiration time
        ];

        if ($baseType == Auth::PRIVATE_USER_API_BASE) {
            $payload['sub'] =  $params->id;
            $payload['role'] =  Auth::ROLE_USER;
        }

        if ($baseType == Auth::PUBLIC_API_BASE) {
            $payload['role'] =  Auth::ROLE_APP;
            $payload['exp'] =  time() + 365*24*60*3600;
        }
        
        // As you can see we are passing `JWT_SECRET` as the second parameter that will 
        // be used to decode the token in the future.
        return JWT::encode($payload, env('JWT_SECRET'));
    }

	/**
	 * Validate Token
	 * @param $token: string
	 * @param $baseType: int
	 * Throw Exception when validate false
	 */
	public static function validateToken($token, $baseType, $id = 0)
	{
		try {
            $credentials = JWT::decode($token, env('JWT_SECRET'), ['HS256']);
            switch ($baseType) {
            	case self::PUBLIC_API_BASE:
            		self::validatePublic($credentials);
            		break;
            	
            	case self::PRIVATE_USER_API_BASE:
            		self::validatePrivateUser($credentials, $id);
            		break;
            	
            	default:
					throw new Exception('Invalid base type.');
            		break;
            }
        } catch(\Exception $e) {
			throw $e;
        }

        return;
	}

	/**
	 * Validate access public API
	 * @param $credentials: object result of decode token
	 * Throw Exception when validate false
	 */
	private static function validatePublic($credentials)
	{
		if (!isset($credentials->role)
            || !in_array($credentials->role, [self::ROLE_APP, self::ROLE_USER])) {
			throw new Exception('Permission application denided.');
        }
        
        return;
	}

	/**
	 * Validate access private user API
	 * @param $credentials: object result of decode token
	 * Throw Exception when validate false
	 */
	private static function validatePrivateUser($credentials, $id)
	{
		if (!isset($credentials->role)
            || $credentials->role != self::ROLE_USER
            || !isset($credentials->sub)
            || intval($credentials->sub) != intval($id)) {
			throw new Exception('Permission user denided.');
        }
        
        return;
	}
}
