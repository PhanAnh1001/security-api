# Security API

1.Tạo DB bằng SQL: CREATE SCHEMA `local` DEFAULT CHARACTER SET utf8 ;

2.Cd vào thư mục project và chạy lệnh: php artisan migrate
3.Tạo dữ liệu test bằng lệnh: php artisan db:seed
4.Tạo local host bằng lệnh: php -S localhost:8000 -t public
5.Lấy token private user bằng API: 
	POST http://localhost:8000/api/v1/auth/login
	params: email, password (12345)
6.Token public sinh bằng thư viện JWT ở front end. ví dụ test:
	Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJsb2NhbGhvc3QiLCJzdWIiOiIiLCJyb2xlIjoxLCJpYXQiOjE1NDQwMjc0MTQsImV4cCI6MzQzNjE4NzQxNH0.ziaTqe3_ZP5zQPx5UplS3QH3xVIFccoPNmBbBS58z_8
7.Dùng public token có thể truy cập các API public:
	GET http://localhost:8000/api/v1/users // Thông tin public nhiều user
	POST http://localhost:8000/api/v1/users // Đăng ký user
		params: name, email, password
8.Dùng private user token có thể truy cập tất cả các API:
	GET http://localhost:8000/api/v1/users
	POST http://localhost:8000/api/v1/users 
		params: name, email, password
	GET http://localhost:8000/api/v1/users/{id}
	DELETE http://localhost:8000/api/v1/users/{id}
	PUT http://localhost:8000/api/v1/users/{id} 
		params: name, email, password

**Note: Khi test bằng Postman các API POST, PUT cần truyền các params bằng phương thức x-www-form-urlencoded.
