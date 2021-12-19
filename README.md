# nuxt_learning
I. Cấu truc folder
    Cấu trúc source bao gồm 2 folder:

    Backend: Đây là folder chứa source laravel đóng vai trò là API

    Frontend: Đây là folder chứa source nuxt js đóng vai trò là giao diện người dùng (client)
        Source nuxt js sẽ gọi API để lấy dữ liệu và hiển thị lên màn hình.

II. Các bước cài đặt và chạy chương trình
1. Cấu hình phần source Backend:
    + Copy file môi trường .env.example và đổi tên thành .env
    + Tạo mới mysql database
    + Cấu hình lại thông tin kết nối database trong file .env
    + Chạy lệnh bên dưới để download thư mục source vendor
   
         composer up
         
    + Chạy lệnh bên dưới để tạo generate key cho JWT
         
         php artisan jwt:secret
         
    + Chạy lệnh bên dưới để thực hiện migration và tạo database
   
        php artisan migrate
        
        php artisan db:seed
        
    + Chạy lệnh start API

        php artisan serve

2. Cấu hình phần source Frontend:
    + Chạy lệnh bên dưới để tải về thư viện của node js
        npm install
    + Chạy lệnh start ứng dụng
        npm run dev

III. Thông tin hệ thống
1. Web URL:
    http://localhost:3000/

2. Tài khoản đăng nhập
    Tất cả các tài khoản user được tạo sẵn trong table mst_users đều có password chung là: Tt123456@

    vd tài khoản đăng nhập:
    
        username:a.trinh@gmail.com
        Password:Tt123456@
