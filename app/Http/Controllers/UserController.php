<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Laravel\Lumen\Routing\Controller as BaseController;
use Input;
use DB;

class UserController extends BaseController
{
    /**
     * The request instance.
     *
     * @var \Illuminate\Http\Request
     */
    private $request;
    /**
     * Create a new controller instance.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return void
     */
    public function __construct(Request $request) {
        $this->request = $request;
    }

    public function showAllUsers()
    {
        return response()->json(['result' => User::all()]);
    }

    public function showOneUser($id)
    {
        return response()->json(['result' => User::find($id)]);
    }

    public function create(Request $request)
    {
        $this->validate($this->request, [
            'email'     => 'required|email',
            'password'  => 'required'
        ]);

        $params = $request->all();
        $params['password'] = app('hash')->make($params['password'], []);
        $user = User::create($params);

        return response()->json(['result' => $user], 201);
    }

    public function update($id, Request $request)
    {
        $params = $request->all();
        $user = User::findOrFail($id);
        if (!empty($params['password'])) {
            $params['password'] = app('hash')->make($params['password'], []);
        }
        $user->update($params);

        return response()->json(['result' => $user], 200);
    }

    public function delete($id)
    {
        User::findOrFail($id)->delete();
        return response(['result'=> 'Deleted Successfully'], 200);
    }
}
