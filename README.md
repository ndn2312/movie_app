# 🎬 Movie Streaming Platform

<p align="center">
  <img src="https://img.shields.io/badge/Laravel-9.19-red?style=for-the-badge&logo=laravel" alt="Laravel">
  <img src="https://img.shields.io/badge/PHP-8.0.2+-blue?style=for-the-badge&logo=php" alt="PHP">
  <img src="https://img.shields.io/badge/MySQL-8.0-orange?style=for-the-badge&logo=mysql" alt="MySQL">
  <img src="https://img.shields.io/badge/Bootstrap-5.2.3-purple?style=for-the-badge&logo=bootstrap" alt="Bootstrap">
  <img src="https://img.shields.io/badge/License-MIT-green?style=for-the-badge" alt="License">
</p>

<p align="center">
  Một nền tảng xem phim trực tuyến hiện đại được xây dựng với Laravel, cung cấp trải nghiệm người dùng mượt mà và các tính năng quản lý nội dung mạnh mẽ.
</p>

## 📋 Mục lục

-   [Tính năng chính](#-tính-năng-chính)
-   [Công nghệ sử dụng](#-công-nghệ-sử-dụng)
-   [Yêu cầu hệ thống](#-yêu-cầu-hệ-thống)
-   [Cài đặt](#-cài-đặt)
-   [Cấu hình](#-cấu-hình)
-   [Sử dụng](#-sử-dụng)

## ✨ Tính năng chính

### 🎭 Cho người dùng

-   **Xem phim trực tuyến** với chất lượng HD/FullHD/4K
-   **Tìm kiếm nâng cao** theo thể loại, quốc gia, năm sản xuất
-   **Hệ thống đánh giá** và bình luận phim
-   **Danh sách yêu thích** cá nhân
-   **Lịch sử xem phim** với tính năng tiếp tục xem
-   **Chatbot AI** hỗ trợ tư vấn phim
-   **Thông báo** phim mới và cập nhật
-   **Giao diện responsive** trên mọi thiết bị

### 🛠️ Cho quản trị viên

-   **Dashboard quản lý** thống kê chi tiết
-   **Quản lý phim** và tập phim
-   **Hệ thống leech** tự động từ API bên ngoài
-   **Quản lý người dùng** và phân quyền
-   **Quản lý thể loại**, quốc gia, danh mục
-   **Hệ thống thông báo** tự động
-   **Báo cáo thống kê** chi tiết

### 🔧 Tính năng kỹ thuật

-   **Tối ưu SEO** với slug thân thiện
-   **Cache thông minh** để tăng tốc độ
-   **API RESTful** cho tích hợp
-   **Xử lý ảnh** tự động
-   **Backup dữ liệu** định kỳ
-   **Logging hệ thống** chi tiết

## 🛠️ Công nghệ sử dụng

### Backend

-   **Laravel 9.19** - PHP Framework
-   **PHP 8.0.2+** - Ngôn ngữ lập trình
-   **MySQL 8.0** - Cơ sở dữ liệu
-   **Laravel Sanctum** - API Authentication
-   **Laravel Socialite** - Social Login
-   **Guzzle HTTP** - API Client

### Frontend

-   **Bootstrap 5.2.3** - CSS Framework
-   **Vite** - Build tool
-   **Axios** - HTTP Client
-   **jQuery** - JavaScript Library
-   **Owl Carousel** - Slider component

### Tools & Services

-   **Composer** - PHP Dependency Manager
-   **NPM** - JavaScript Package Manager
-   **XAMPP** - Development Environment

## 📋 Yêu cầu hệ thống

-   PHP >= 8.0.2
-   Composer
-   Node.js & NPM
-   MySQL >= 8.0
-   Apache/Nginx
-   OpenSSL PHP Extension
-   PDO PHP Extension
-   Mbstring PHP Extension
-   Tokenizer PHP Extension
-   XML PHP Extension

## 🚀 Cài đặt

### 1. Clone repository

```bash
git clone https://github.com/your-username/movie-app.git
cd movie-app
```

### 2. Cài đặt dependencies

```bash
# Cài đặt PHP dependencies
composer install

# Cài đặt JavaScript dependencies
npm install
```

### 3. Cấu hình môi trường

```bash
# Copy file môi trường
cp .env.example .env

# Tạo application key
php artisan key:generate
```

### 4. Cấu hình database

Chỉnh sửa file `.env`:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=movie_app
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

### 5. Chạy migration và seeder

```bash
# Tạo bảng database
php artisan migrate

# Seed dữ liệu mẫu (tùy chọn)
php artisan db:seed
```

### 6. Tạo symbolic link cho storage

```bash
php artisan storage:link
```

### 7. Build assets

```bash
# Development
npm run dev

# Production
npm run build
```

### 8. Khởi chạy server

```bash
php artisan serve
```

Truy cập ứng dụng tại: `http://localhost:8000`

## ⚙️ Cấu hình

### Cấu hình Mail (tùy chọn)

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your_email@gmail.com
MAIL_PASSWORD=your_app_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=your_email@gmail.com
MAIL_FROM_NAME="${APP_NAME}"
```

### Truy cập Admin Panel

1. Đăng ký tài khoản admin đầu tiên
2. Truy cập: `http://localhost:8000/admin`
3. Đăng nhập với thông tin admin

### Quản lý phim

1. **Thêm phim thủ công**: Admin Panel > Phim > Thêm mới
2. **Leech từ API**: Admin Panel > Leech > Tìm kiếm và thêm
3. **Quản lý tập phim**: Chọn phim > Thêm tập

### Cấu hình thể loại và danh mục

1. **Thể loại**: Admin Panel > Thể loại
2. **Quốc gia**: Admin Panel > Quốc gia
3. **Danh mục**: Admin Panel > Danh mục

## 🔌 API

### Authentication

```bash
# Đăng nhập
POST /api/login
Content-Type: application/json
{
  "email": "user@example.com",
  "password": "password"
}
```

### Movies Endpoints

```bash
# Lấy danh sách phim
GET /api/movies?page=1&limit=20

# Lấy chi tiết phim
GET /api/movies/{id}

# Tìm kiếm phim
GET /api/movies/search?q=keyword
```

### Favorites Endpoints

```bash
# Thêm vào yêu thích
POST /api/favorites
Authorization: Bearer {token}
{
  "movie_id": 1
}

# Lấy danh sách yêu thích
GET /api/favorites
Authorization: Bearer {token}
```

## 🤝 Đóng góp

Chúng tôi hoan nghênh mọi đóng góp! Để đóng góp:

1. Fork project
2. Tạo feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit changes (`git commit -m 'Add some AmazingFeature'`)
4. Push to branch (`git push origin feature/AmazingFeature`)
5. Tạo Pull Request

### Guidelines

-   Viết code theo PSR-12 standard
-   Thêm tests cho features mới
-   Cập nhật documentation
-   Sử dụng commit message rõ ràng

## 🛡️ Bảo mật

### Báo cáo lỗ hổng bảo mật

Nếu phát hiện lỗ hổng bảo mật, vui lòng gửi email đến: kocoten001a@gmail.com

### Best Practices

-   Thường xuyên cập nhật dependencies
-   Sử dụng HTTPS trong production
-   Cấu hình firewall phù hợp
-   Backup dữ liệu định kỳ
-   Monitor logs thường xuyên

## 📱 Screenshots

### Trang chủ

![Homepage](docs/images/homepage.png)

### Chi tiết phim

![Movie Detail](docs/images/movie-detail.png)

### Admin Panel

![Admin Panel](docs/images/admin-panel.png)
