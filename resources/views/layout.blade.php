<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width,initial-scale=1.0, maximum-scale=1.0, user-scalable=no" name="viewport">
    <meta name="theme-color" content="#0f1824">
    <meta http-equiv="Content-Language" content="vi">
    <meta content="VN" name="geo.region">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="shortcut icon" href="https://cdn-icons-png.flaticon.com/512/3418/3418886.png" type="image/png">

    <title>Ndn Phim 2025 - Xem phim hay nhất</title>
    <meta name="description"
        content="Ndn Phim 2025 - Kho phim chất lượng cao, cập nhật nhanh nhất, xem phim online miễn phí HD">
    <link rel="canonical" href="">

    <!-- SEO Meta Tags -->
    <meta property="og:locale" content="vi_VN">
    <meta property="og:title" content="Ndn Phim 2025 - Xem phim hay nhất">
    <meta property="og:description"
        content="Kho phim chất lượng cao 2025 - Phim lẻ, phim bộ, phim chiếu rạp. Tuyển chọn phim hay nhất Việt Nam, Hàn Quốc, Trung Quốc, Mỹ...">
    <meta property="og:url" content="">
    <meta property="og:site_name" content="Phim hay 2025">
    <meta property="og:image" content="">

    <!-- CSS Files -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <link rel='stylesheet' href='{{asset(' css/bootstrap.min.css')}}'>
    {{--
    <link rel='stylesheet' href='{{asset(' css/layout.css')}}'> --}}

    <link rel='stylesheet' href='{{asset(' css/style.css')}}'>



    <!-- Google Fonts -->
    <link
        href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&family=Be+Vietnam+Pro:wght@400;500;600;700&display=swap"
        rel="stylesheet">
    <div id="fb-root"></div>
    <script async defer crossorigin="anonymous" src="https://connect.facebook.net/vi_VN/sdk.js#xfbml=1&version=v22.0">
    </script>
    <script src='{{asset(' js/jquery.min.js')}}'></script>

    <!-- Plyr CSS -->
    <link rel="stylesheet" href="https://cdn.plyr.io/3.7.8/plyr.css" />
    <!-- Plyr JS -->
    <script src="https://cdn.plyr.io/3.7.8/plyr.polyfilled.js"></script>

    <!-- HLS.js cho phát video m3u8 -->
    <script src="https://cdn.jsdelivr.net/npm/hls.js@latest"></script>
    <script src="{{asset('js/vendors/hls/hls-player.js')}}"></script>

    <!-- Favorites CSS -->
    <link rel="stylesheet" href="{{asset('css/favorites.css')}}" />

    <!-- Custom CSS -->
    <style>
        .login-error,
        .register-error {
            margin-top: 10px;
            padding: 8px 12px;
            border-radius: 4px;
            font-size: 14px;
            animation: fadeIn 0.3s ease-in;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Notification Styles - Simplified & Elegant */
        .notification-icon-wrapper {
            position: relative;
            display: inline-block;
            margin-right: 15px;
        }

        .notification-icon {
            cursor: pointer;
            font-size: 18px;
            color: #fff;
            padding: 8px;
            position: relative;
            transition: color 0.2s ease;
        }

        .notification-icon:hover {
            color: #4dabf7;
        }

        .notification-count {
            position: absolute;
            top: 2px;
            right: 0;
            background-color: #fa5252;
            color: white;
            border-radius: 50%;
            font-size: 10px;
            min-width: 16px;
            height: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 1px solid #1d2544;
            font-weight: bold;
            padding: 0 3px;
        }

        .notification-dropdown {
            display: none;
            position: absolute;
            top: calc(100% + 5px);
            right: -10px;
            width: 350px;
            background-color: #1d2544;
            border-radius: 6px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
            z-index: 1000;
            max-height: 450px;
            overflow-y: auto;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .notification-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 12px 15px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            background-color: #111827;
            border-radius: 6px 6px 0 0;
        }

        .notification-header h3 {
            margin: 0;
            font-size: 16px;
            color: #fff;
            font-weight: 600;
        }

        .notification-actions {
            display: flex;
            gap: 8px;
        }

        .action-btn {
            background: none;
            border: none;
            color: #adb5bd;
            font-size: 12px;
            cursor: pointer;
            padding: 4px 8px;
            border-radius: 4px;
            transition: all 0.2s ease;
        }

        .action-btn:hover {
            background-color: rgba(255, 255, 255, 0.1);
            color: #fff;
        }

        .mark-btn:hover {
            color: #40c057;
        }

        .delete-btn:hover {
            color: #ff6b6b;
        }

        .notification-list {
            max-height: 350px;
            overflow-y: auto;
        }

        .notification-list::-webkit-scrollbar {
            width: 4px;
        }

        .notification-list::-webkit-scrollbar-thumb {
            background-color: rgba(255, 255, 255, 0.2);
            border-radius: 2px;
        }

        .notification-item {
            padding: 12px 15px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
            display: flex;
            align-items: center;
            transition: background-color 0.2s ease;
        }

        .notification-item:last-child {
            border-bottom: none;
        }

        .notification-item.unread {
            background-color: rgba(59, 130, 246, 0.05);
            border-left: 3px solid #4dabf7;
        }

        .notification-item:hover {
            background-color: rgba(255, 255, 255, 0.05);
        }

        .notification-link {
            display: flex;
            flex: 1;
            text-decoration: none;
            color: inherit;
        }

        .notification-img {
            width: 45px;
            height: 60px;
            border-radius: 4px;
            object-fit: cover;
            margin-right: 12px;
        }

        .notification-content {
            flex: 1;
        }

        .notification-title {
            color: #f8f9fa;
            font-weight: 600;
            margin-bottom: 4px;
            font-size: 14px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .notification-message {
            color: #adb5bd;
            font-size: 12px;
            margin-bottom: 4px;
            line-height: 1.3;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .notification-time {
            color: #868e96;
            font-size: 11px;
        }

        .notification-delete {
            margin-left: 8px;
            cursor: pointer;
            color: #adb5bd;
            width: 24px;
            height: 24px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            transition: background-color 0.2s ease;
        }

        .notification-delete:hover {
            background-color: rgba(255, 255, 255, 0.1);
            color: #ff6b6b;
        }

        .notification-loading,
        .no-notifications {
            padding: 20px;
            text-align: center;
            color: #868e96;
            font-size: 13px;
        }

        .notification-loading::before {
            content: '';
            display: block;
            width: 30px;
            height: 30px;
            margin: 0 auto 10px;
            border: 2px solid rgba(255, 255, 255, 0.1);
            border-top: 2px solid #4dabf7;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }

        /* CSS cho sticky header và navbar */
        :root {
            --header-height: auto;
            --navbar-height: auto;
            --total-height: auto;
        }

        body {
            margin: 0;
            padding: 0;
        }

        .main-content {
            position: relative;
            z-index: 1;
        }

        /* Header styling */
        header#header {
            width: 100%;
            position: relative;
            z-index: 1000;
            will-change: transform;
            transition: transform 0.3s cubic-bezier(0.33, 1, 0.68, 1);
        }

        header#header.sticky {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            box-shadow: 0 3px 15px rgba(0, 0, 0, 0.2);
            background-color: rgba(15, 23, 42, 0.98);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            padding: 5px 0;
        }

        /* Logo transitions */
        .site-logo .logo-image {
            transition: height 0.3s ease;
        }

        /* header#header.sticky .site-logo .logo-image {
            height: 40px;
        } */

        .logo-text {
            transition: font-size 0.3s ease;
        }

        header#header.sticky .logo-text {
            font-size: 1.5em;
        }

        /* Search box transition */
        .search-wrapper .search-box {
            transition: height 0.3s ease;
        }

        header#header.sticky .search-wrapper .search-box {
            height: 38px;
        }

        /* Navbar styling */
        .navbar-container {
            width: 100%;
            position: relative;
            will-change: transform;
            z-index: 999;
            transition: transform 0.3s cubic-bezier(0.33, 1, 0.68, 1);
        }

        .navbar-container.sticky {
            position: fixed;
            box-shadow: 0 3px 15px rgba(0, 0, 0, 0.15);
            background-color: rgba(15, 23, 42, 0.95);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
        }

        .navbar-container.sticky .nav-menu {
            padding: 5px 0;
        }

        /* Header hide animation */
        header#header.sticky-hide {
            transform: translateY(-100%);
        }

        /* Navbar hide animation */
        .navbar-container.sticky-hide {
            transform: translateY(-100%);
        }

        /* Hover effects */
        .navbar-container.sticky .nav-link:hover {
            color: #04c1e7;
        }

        /* Fix dropdown positioning */
        .navbar-container.sticky .dropdown-menu {
            top: 100%;
            bottom: auto;
        }

        /* Spacer to prevent content jump */
        .header-spacer {
            display: none;
            height: 0;
            transition: height 0.3s ease;
        }

        /* Kích thước và căn chỉnh modal đăng nhập */
        #loginModal .modal-dialog {
            max-width: 900px;
            margin: 1.75rem auto;
        }

        /* Container chính chia đôi màn hình */
        .login-container {
            display: flex;
            width: 100%;
            background-color: #1d2544;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.5);
            position: relative;
        }

        /* Phần bên trái - logo và tagline */
        .login-left {
            width: 50%;
            padding: 40px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            position: relative;
            background-color: rgba(0, 0, 0, 0.2);
        }

        /* Phần bên phải - form đăng nhập */
        .login-right {
            width: 50%;
            padding: 40px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        /* Phần logo */
        .site-logo {
            display: flex;
            flex-direction: row;
            align-items: center;
            height: 60px;
            /* margin-bottom: 30px; */
        }

        .site-logo img {
            width: 120px;
            height: 120px;
            margin-bottom: 15px;
        }

        .logo-text {
            font-size: 1.5em;
            font-weight: 700;
            color: #fff;
            margin: 0;
        }

        .logo-text span {
            color: red;
        }

        .login-tagline {
            font-size: 1.2em;
            color: rgba(255, 255, 255, 0.6);
            text-align: center;
        }

        /* Phần tiêu đề form */
        .login-title {
            font-size: 2em;
            color: #fff;
            margin-bottom: 20px;
        }

        .login-subtitle {
            color: rgba(255, 255, 255, 0.6);
            margin-bottom: 30px;
        }

        /* Form và input */
        .form-control {
            background-color: rgba(0, 0, 0, 0.2);
            border: 1px solid rgba(255, 255, 255, 0.1);
            color: #fff;
            height: 50px;
            padding: 0 20px;
            font-size: 16px;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            background-color: rgba(0, 0, 0, 0.3);
            border-color: #ffc107;
            box-shadow: 0 0 0 0.2rem rgba(255, 193, 7, 0.25);
            color: #fff;
        }

        /* Nút đăng nhập */
        .btn-login {
            background-color: #ffc107;
            color: #1d2544;
            height: 50px;
            font-size: 16px;
            font-weight: 700;
            text-transform: uppercase;
            border: none;
            border-radius: 5px;
            transition: all 0.3s ease;
        }

        .btn-login:hover {
            background-color: #ffca2c;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(255, 193, 7, 0.4);
        }

        /* Quên mật khẩu */
        .forgot-password {
            text-align: center;
            margin-top: 20px;
        }

        .forgot-password a {
            color: rgba(255, 255, 255, 0.6);
            transition: all 0.3s ease;
        }

        .forgot-password a:hover {
            color: #ffc107;
            text-decoration: none;
        }

        /* Thông báo form */
        .form-message {
            padding: 10px 15px;
            border-radius: 5px;
            margin-bottom: 20px;
            display: none;
        }

        .form-message.success {
            background: rgba(40, 167, 69, 0.2);
            border: 1px solid rgba(40, 167, 69, 0.3);
            color: #28a745;
        }

        .form-message.error {
            background: rgba(220, 53, 69, 0.2);
            border: 1px solid rgba(220, 53, 69, 0.3);
            color: #dc3545;
        }

        /* Link đăng ký */
        .register-link {
            color: #ffc107;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .register-link:hover {
            color: #ffca2c;
            text-decoration: underline;
        }

        /* Responsive */
        @media (max-width: 768px) {
            #loginModal .modal-dialog {
                max-width: 90%;
                margin: 1rem auto;
            }

            .login-container {
                flex-direction: column;
                height: auto;
            }

            .login-left,
            .login-right {
                width: 100%;
                padding: 30px;
            }

            .login-left {
                padding-bottom: 0;
            }

            .site-logo img {
                width: 80px;
                height: 80px;
            }

            .logo-text {
                font-size: 2em;
            }
        }

        /* Patriotic Banner */
        .patriotic-banner {
            background-color: #da251d;
            color: #fff;
            text-align: center;
            padding: 10px 15px;
            border-radius: 30px;
            font-weight: bold;
            /* margin-bottom: 20px; */
            font-size: 16px;
            display: inline-block;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.2);
        }

        .patriotic-banner .star {
            color: #ffff00;
            margin-right: 5px;
        }

        /* Footer logo styling */
        .logo-container {
            text-align: center;
            margin: 15px 0 25px;
        }

        /* .footer-logo {
            max-height: 60px;
            width: auto;
        } */

        .footer-bottom {
            padding-top: 15px;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            text-align: center;
        }

        /* Banner and logo styling */
        .header-elements {
            display: flex;
            flex-direction: column;
            align-items: center;
            /* margin-bottom: 25px; */
            gap: 15px;
        }

        .patriotic-banner {
            background-color: #da251d;
            color: #fff;
            text-align: center;
            padding: 10px 15px;
            border-radius: 30px;
            font-weight: bold;
            font-size: 16px;
            display: inline-block;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.2);
        }

        .patriotic-banner .star {
            color: #ffff00;
            margin-right: 5px;
        }

        .logo-wrapper {
            text-align: center;
        }

        /* .footer-logo {
            max-height: 60px;
            width: auto;
        } */

        .footer-bottom {
            padding-top: 15px;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            text-align: center;
        }
    </style>

</head>

<body>


    {{-- @include('partials.loading-screen') --}}

    <!-- Header -->

    <header id="header">

        <div class="container">
            <div class="header-inner">
                <!-- Logo -->
                <a href="/" class="site-logo">
                    <img src="/imgs/logo.png" alt="NdnPhim" class="logo-image">
                    <h1 class="logo-text">Ndn<span>Phim</span></h1>
                </a>

                <!-- Search -->
                <div class="search-wrapper">
                    <form action="{{route('tim-kiem')}}" method="GET">

                        <div class="search-box">
                            <div type="button" class="search-icon">
                                <i class="fas fa-search"></i>
                            </div>
                            <input id="timkiem" name="search" type="text" placeholder="Tìm kiếm phim..."
                                autocomplete="off">
                            <div class="clear-search">
                                <i class="fas fa-times"></i>
                            </div>

                        </div>
                    </form>
                    <div class="search-results">
                        <ul id="result">
                            <!-- Search results will appear here -->
                        </ul>

                        <div id="no-results" style="display: none;">
                            <i class="fas fa-search"></i>
                            <p>Không tìm thấy kết quả phù hợp</p>
                            <small>Hãy thử tìm kiếm với từ khóa khác</small>
                        </div>

                        <div id="search-loading" style="display: none;">
                            <i class="fas fa-spinner"></i>
                            <p>Đang tìm kiếm...</p>
                            <small>Chờ một chút nhé</small>
                        </div>
                    </div>
                </div>

                <!-- User Actions -->
                <div class="user-actions">
                    @if(Auth::check())
                    <!-- Notification Icon -->
                    <div class="notification-icon-wrapper">
                        <div class="notification-icon" id="notification-icon">
                            <i class="fas fa-bell"></i>
                            <span class="notification-count" id="notification-count">0</span>
                        </div>
                        <div class="notification-dropdown" id="notification-dropdown">
                            <div class="notification-header">
                                <h3>Thông báo</h3>
                                <div class="notification-actions">
                                    <button id="mark-all-read" class="action-btn mark-btn">
                                        <i class="fas fa-check"></i> Đọc tất cả
                                    </button>
                                    <button id="delete-all-read" class="action-btn delete-btn">
                                        <i class="fas fa-trash"></i> Xóa đã đọc
                                    </button>
                                </div>
                            </div>
                            <div class="notification-list" id="notification-list">
                                <div class="notification-loading">Đang tải...</div>
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- User Account Menu -->
                    <div class="user-account-menu">
                        @if(Auth::check())
                        <div class="dropdown">
                            <a href="#" class="dropdown-toggle user-dropdown" data-toggle="dropdown">
                                <div class="user-avatar">
                                    <img src="{{Auth::user()->avatar ?? 'https://ui-avatars.com/api/?name='.urlencode(Auth::user()->name).'&background=random&color=fff'}}"
                                        alt="{{Auth::user()->name}}">
                                </div>
                                <span class="user-name">{{Auth::user()->name}}</span>
                                {{-- <i class="fas fa-chevron-down"></i> --}}
                            </a>
                            <ul class="dropdown-menu dropdown-menu-right">
                                <li class="dropdown-item-header">
                                    <div class="user-info">
                                        <div class="user-avatar">
                                            <img src="{{Auth::user()->avatar ?? 'https://ui-avatars.com/api/?name='.urlencode(Auth::user()->name).'&background=random&color=fff'}}"
                                                alt="{{Auth::user()->name}}">
                                        </div>
                                        <div class="user-details">
                                            <span class="user-name">{{Auth::user()->name}}</span>
                                            <span class="user-email">{{Auth::user()->email}}</span>
                                        </div>
                                    </div>
                                </li>
                                <li><a href="{{route('favorites')}}"><i class="fas fa-heart"></i> Phim yêu thích</a>
                                </li>
                                <li><a href="{{route('history')}}"><i class="fas fa-history"></i> Lịch sử xem</a>
                                </li>
                                <li><a href="{{route('chatbot')}}"><i class="fas fa-robot"></i> ChatBot AI</a>
                                </li>
                                <li><a href="{{route('account')}}"><i class="fas fa-user-cog"></i> Thông tin tài
                                        khoản</a>
                                </li>
                                <li class="dropdown-divider"></li>
                                <li><a href="{{route('logout')}}"
                                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();"><i
                                            class="fas fa-sign-out-alt"></i> Đăng xuất</a></li>
                                <form id="logout-form" action="{{route('logout')}}" method="POST"
                                    style="display: none;">
                                    @csrf
                                </form>
                            </ul>
                        </div>
                        @else
                        <div class="auth-buttons">
                            <a href="#" class="login-btn" data-toggle="modal" data-target="#loginModal">
                                <i class="fas fa-sign-in-alt"></i>
                                <span>Đăng nhập</span>
                            </a>
                            <a href="#" class="register-btn" data-toggle="modal" data-target="#registerModal">
                                <i class="fas fa-user-plus"></i>
                                <span>Đăng ký</span>
                            </a>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- Navigation -->
    <div class="navbar-container">

        <div class="container">
            <nav class="main-nav">
                <ul class="nav-menu">
                    <li class="nav-item active">
                        <a class="nav-link" href="{{route('homepage')}}">
                            <i class="fas fa-home"></i>Trang Chủ
                        </a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#">
                            <i class="fas fa-film"></i>Thể Loại
                        </a>
                        <ul class="dropdown-menu">
                            @foreach($genre as $gen)
                            <li><a class="dropdown-item" href="{{route('genre', $gen->slug)}}">{{$gen->title}}</a></li>
                            @endforeach
                        </ul>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#">
                            <i class="fas fa-globe"></i>Quốc Gia
                        </a>
                        <ul class="dropdown-menu">
                            @foreach($country as $count)
                            <li><a class="dropdown-item" href="{{route('country', $count->slug)}}">{{$count->title}}</a>
                            </li>
                            @endforeach
                        </ul>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#">
                            <i class="fas fa-calendar-alt"></i>Năm
                        </a>
                        <ul class="dropdown-menu">
                            @for($year=2025; $year>=2000; $year--)
                            <li><a class="dropdown-item" href="{{url('nam/'.$year)}}">{{$year}}</a></li>
                            @endfor
                        </ul>
                    </li>
                    @foreach($category as $cate)
                    <li class="nav-item">
                        <a class="nav-link" href="{{route('category',$cate->slug)}}">{{$cate->title}}</a>
                    </li>
                    @endforeach
                </ul>
                <a href="{{ route('advancedfilter') }}" class="filter-btn">
                    <i class="fas fa-filter"></i>Lọc Phim
                </a>
            </nav>
        </div>
    </div>

    <!-- Spacer element to prevent content jump -->
    <div class="header-spacer" id="header-spacer"></div>

    <!-- Main Content -->
    <div class="main-content">
        <div class="container">
            @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle"></i> {{ session('success') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            @endif

            @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            @endif

            @yield('content')
        </div>
    </div>

    <!-- Footer -->
    <footer class="site-footer">
        <div class="container">
            <div class="header-elements">
                <div class="patriotic-banner">
                    <span class="star">★</span> Hoàng Sa & Trường Sa là của Việt Nam!
                </div>

                <div class="logo-wrapper">
                    <img src="/imgs/logo.png" alt="NdnPhim" class="footer-logo">
                </div>
            </div>

            <div class="footer-content">
                <div class="footer-column">
                    <h3>Về chúng tôi</h3>
                    <p>NdnPhim cung cấp kho phim HD chất lượng cao miễn phí. Chúng tôi liên tục cập nhật những bộ phim
                        mới nhất với chất lượng tốt nhất.</p>

                    <div class="footer-social">
                        <a href="https://www.facebook.com/ducnguyen.232001/" class="social-icon"><i
                                class="fab fa-facebook-f"></i></a>
                        <a href="#" class="social-icon"><i class="fab fa-twitter"></i></a>
                        <a href="#" class="social-icon"><i class="fab fa-instagram"></i></a>
                        <a href="#" class="social-icon"><i class="fab fa-youtube"></i></a>
                    </div>
                </div>
                <div class="footer-column">
                    <h3>Liên kết hữu ích</h3>
                    <ul class="footer-links">
                        <li><a href="#">Phim mới</a></li>
                        <li><a href="#">Phim lẻ</a></li>
                        <li><a href="#">Phim bộ</a></li>
                        <li><a href="#">Phim chiếu rạp</a></li>
                        <li><a href="#">TV Shows</a></li>
                    </ul>
                </div>
                <div class="footer-column">
                    <h3>Thông tin</h3>
                    <ul class="footer-links">
                        <li><a href="#">Điều khoản sử dụng</a></li>
                        <li><a href="#">Chính sách bảo mật</a></li>
                        <li><a href="#">FAQ</a></li>
                        <li><a href="#">Liên hệ</a></li>
                    </ul>
                </div>
                <div class="footer-column">
                    <h3>Liên hệ</h3>
                    <p>Liên hệ QC: <a href="mailto:kocoten001a@gmail.com">kocoten001a@gmail.com</a></p>
                    <p>Hỗ trợ: <a href="mailto:kocoten001a@gmail.com">kocoten001a@gmail.com</a></p>
                </div>
            </div>

            <div class="footer-bottom">
                <p>&copy; 2025 NdnPhim. Tất cả quyền được bảo lưu.</p>
            </div>
        </div>
    </footer>

    <!-- Back to Top Button -->
    <div id="back-to-top">
        <i class="fas fa-chevron-up"></i>
    </div>

    <!-- Toast Container -->
    <div class="toast-container"></div>

    <!-- Scripts -->
    <script type='text/javascript'>
        $(document).ready(function() {
           $.ajax({
              url: "{{url('/filter-topview-default')}}",
              method: "GET",
              
              success: function(data) {
                    $('#show_data_default').html(data);
              }
           });
        
     
        $('.filter-sidebar').click(function() {
           var href = $(this).attr('href');
           
           // Xác định giá trị dựa trên href
           if (href === '#ngay') {
              var value = 0;
           } else if (href === '#tuan') {
              var value = 1;
           } else {
              var value = 2;
           }
           
           // Thực hiện yêu cầu AJAX
           $.ajax({
              url: "{{url('/filter-topview-phim')}}",
              method: "POST",
              
              data: { value: value },
              headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},

              success: function(data) {
                    $('#halim-ajax-popular-post-default').css("display", "none");

                    $('#show_data').html(data);
              }
           });
        });
     })

    </script>


    <script src='{{asset(' js/bootstrap.min.js')}}'></script>
    <script src='{{asset(' js/owl.carousel.min.js')}}'></script>
    <script src='{{asset(' js/search.js')}}'></script>
    <script src='{{asset(' js/favorites.js')}}'></script>
    <script>
        $(document).ready(function() {
        // Khởi tạo hệ thống đánh giá popup
        if ($('#movie-rating-system-popup').length) {
            initPopupRatingSystem();
        }
        
        function initPopupRatingSystem() {
            const ratingSystem = $('#movie-rating-system-popup');
            const movieId = ratingSystem.data('movie-id');
            const stars = ratingSystem.find('.star');
            const feedback = $('#rating-feedback-popup');
            const currentRating = parseInt($('.rating-stats .average').text()) || 0;
            
            // Hiệu ứng hover
            stars.on('mouseenter', function() {
            const value = $(this).data('value');
            resetStars();
            highlightStars(value);
            $(this).addClass('pulse');
            });
            
            ratingSystem.on('mouseleave', function() {
            resetStars();
            highlightStars(currentRating);
            stars.removeClass('pulse');
            });
            
            // Click để đánh giá
            stars.on('click', function() {
            const value = $(this).data('value');
            submitRating(movieId, value);
            });
            
            // Các hàm tiện ích
            function resetStars() {
            stars.removeClass('hover');
            }
            
            function highlightStars(count) {
            stars.each(function(index) {
                if (index < count) {
                $(this).addClass('hover');
                }
            });
            }
            
            function submitRating(movieId, rating) {
            $.ajax({
                url: '/add-rating',
                method: 'POST',
                data: {
                movie_id: movieId,
                index: rating
                },
                headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                beforeSend: function() {
                feedback.removeClass('success error')
                        .addClass('loading')
                        .text('Đang xử lý...')
                        .fadeIn();
                },
                success: function(response) {
                if (response === 'done') {
                    feedback.removeClass('loading error')
                        .addClass('success')
                        .html('<i class="fas fa-check-circle"></i> Cảm ơn bạn đã đánh giá ' + rating + ' sao!');
                    
                    // Cập nhật UI mà không cần tải lại trang
                    updateRatingDisplay(rating);
                    
                    // Đóng modal sau 2 giây
                    setTimeout(function() {
                    $('#ratingModal').modal('hide');
                    }, 2000);
                } else if (response === 'exist') {
                    feedback.removeClass('loading success')
                        .addClass('error')
                        .html('<i class="fas fa-exclamation-circle"></i> Bạn đã đánh giá phim này trước đó');
                } else {
                    feedback.removeClass('loading success')
                        .addClass('error')
                        .html('<i class="fas fa-exclamation-triangle"></i> Có lỗi xảy ra, vui lòng thử lại sau');
                }
                
                setTimeout(function() {
                    feedback.fadeOut();
                }, 5000);
                },
                // error: function() {
                // feedback.removeClass('loading success')
                //         .addClass('error')
                //         .html('<i class="fas fa-exclamation-triangle"></i> Lỗi kết nối, vui lòng thử lại sau');
                
                // setTimeout(function() {
                //     feedback.fadeOut();
                // }, 5000);
                // }
            });
            }
    
            function updateRatingDisplay(newRating) {
            // Cập nhật đánh giá trung bình trên trang và trong popup
            $('.rating-stats .average, .current-rating .average').text(newRating);
            
            // Cập nhật số lượt đánh giá
            let currentCount = parseInt($('.rating-stats .count').text().replace(/[()]/g, '')) || 0;
            $('.rating-stats .count, .current-rating small').text('(' + (currentCount + 1) + ' lượt đánh giá)');
            
            // Cập nhật sao trong popup
            resetStars();
            highlightStars(newRating);
            stars.removeClass('active');
            stars.each(function(index) {
                if (index < newRating) {
                $(this).addClass('active');
                }
            });
            }
        }
        
        // Hiển thị đánh giá khi mở modal
        $('#ratingModal').on('shown.bs.modal', function () {
            const currentRating = parseInt($('.rating-stats .average').text()) || 0;
            $('#movie-rating-system-popup .star').removeClass('active hover');
            $('#movie-rating-system-popup .star').each(function(index) {
            if (index < currentRating) {
                $(this).addClass('active');
            }
            });
        });
        });

    
// Đồng bộ lượt xem từ sessionStorage
$(document).ready(function() {
    // Lấy dữ liệu cập nhật lượt xem từ sessionStorage
    const viewUpdates = JSON.parse(sessionStorage.getItem('viewUpdates') || '{}');
    
    // Cập nhật các mục trong sidebar
    if (Object.keys(viewUpdates).length > 0) {
        $('#sidebar .popular-post .item').each(function() {
            const itemHref = $(this).find('a').attr('href');
            if (itemHref) {
                // Lấy slug từ href
                const slugMatch = itemHref.match(/\/phim\/([^\/]+)$/);
                if (slugMatch && slugMatch[1]) {
                    const movieSlug = slugMatch[1];
                    // Nếu có cập nhật cho phim này
                    if (viewUpdates[movieSlug]) {
                        $(this).find('.viewsCount').text(viewUpdates[movieSlug] + ' lượt xem');
                    }
                }
            }
        });
    }
});


    </script>

    <!-- Login Modal -->
    <div class="modal fade" id="loginModal" tabindex="-1" role="dialog" aria-labelledby="loginModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content p-0 bg-transparent border-0">
                <button type="button" class="close position-absolute" data-dismiss="modal" aria-label="Close"
                    style="right: 15px; top: 15px; z-index: 1050; color: #fff; font-size: 40px; text-shadow: 0 0 3px rgba(0,0,0,0.5);">
                    <span aria-hidden="true">&times;</span>
                </button>

                <div class="login-container d-flex">
                    <div class="login-left">
                        <div class="site-logo">
                            <img src="/imgs/logo.png" alt="NDN Phim" class="logo-image">
                            <h1 class="logo-text">Ndn<span>Phim</span></h1>
                        </div>
                        <p class="login-tagline">Phim hay mỗi ngày – không lo gián đoạn</p>
                    </div>

                    <div class="login-right">
                        <h2 class="login-title">Đăng nhập</h2>
                        <p class="login-subtitle">Nếu bạn chưa có tài khoản, <a href="#" class="register-link"
                                data-dismiss="modal" data-toggle="modal" data-target="#registerModal">đăng ký ngay</a>
                        </p>

                        <div id="login-message" class="form-message"></div>

                        <form method="POST" action="{{ route('login.post') }}" id="loginForm">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <div class="form-group">
                                <input type="text" class="form-control" id="login" name="login"
                                    placeholder="Email hoặc Tên đăng nhập" required>
                            </div>
                            <div class="form-group">
                                <input type="password" class="form-control" id="password" name="password"
                                    placeholder="Mật khẩu" required>
                            </div>
                            <div class="form-group form-check">
                                <input type="checkbox" class="form-check-input" id="remember" name="remember">
                                <label class="form-check-label" for="remember"
                                    style="color: rgba(255, 255, 255, 0.6);">Ghi nhớ đăng nhập</label>
                            </div>
                            <button type="submit" class="btn btn-login btn-block">Đăng nhập</button>
                        </form>

                        <div class="forgot-password">
                            <a href="#">Quên mật khẩu?</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Register Modal -->
    <div class="modal fade" id="registerModal" tabindex="-1" role="dialog" aria-labelledby="registerModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="registerModalLabel">Đăng Ký</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div id="register-alerts"></div>
                    <form method="POST" action="{{ route('register.post') }}" id="registerForm" class="ajax-form">
                        @csrf
                        <div class="form-group">
                            <label for="register_name">Họ tên</label>
                            <input type="text" class="form-control" id="register_name" name="name" required>
                        </div>
                        <div class="form-group">
                            <label for="register_email">Email</label>
                            <input type="email" class="form-control" id="register_email" name="email" required>
                        </div>
                        <div class="form-group">
                            <label for="register_password">Mật khẩu</label>
                            <input type="password" class="form-control" id="register_password" name="password" required>
                        </div>
                        <div class="form-group">
                            <label for="password_confirmation">Xác nhận mật khẩu</label>
                            <input type="password" class="form-control" id="password_confirmation"
                                name="password_confirmation" required>
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary btn-block">Đăng ký</button>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <p>Đã có tài khoản? <a href="#" data-dismiss="modal" data-toggle="modal"
                            data-target="#loginModal">Đăng nhập ngay</a></p>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Tự động mở modal đăng nhập khi truy cập từ đường dẫn /login
        $(document).ready(function() {
            // Kiểm tra nếu có tham số show_login=true trong URL
            const urlParams = new URLSearchParams(window.location.search);
            if (urlParams.get('show_login') === 'true') {
                // Mở modal đăng nhập
                $('#loginModal').modal('show');
                
                // Xóa tham số khỏi URL mà không làm tải lại trang
                const newUrl = window.location.pathname;
                history.pushState({}, document.title, newUrl);
            }
            
            // Xử lý form submit cho modal đăng nhập
            $('#loginForm').on('submit', function(e) {
                e.preventDefault();
                
                // Xóa các thông báo lỗi cũ
                $('#login-message').removeClass('success error').hide();
                
                $.ajax({
                    url: $(this).attr('action'),
                    method: $(this).attr('method'),
                    data: $(this).serialize(),
                    dataType: 'json',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    beforeSend: function() {
                        $('#loginForm button[type="submit"]').html('<i class="fas fa-spinner fa-spin"></i> Đang xử lý...').attr('disabled', true);
                    },
                    success: function(response) {
                        console.log('Login response:', response);
                        
                        if (response.success === false) {
                            // Hiển thị lỗi đăng nhập từ server
                            $('#loginForm button[type="submit"]').html('Đăng nhập').attr('disabled', false);
                            $('#login-message').addClass('error').html(response.message).slideDown();
                        } else {
                            // Thành công - hiển thị màn hình loading
                            $('#login-message').addClass('success').html('<i class="fas fa-check-circle"></i> Đăng nhập thành công! Đang chuyển hướng...').slideDown();
                            
                            // Kích hoạt màn hình loading
                            const pageLoader = document.getElementById('page-loader');
                            if (pageLoader) {
                                pageLoader.style.display = 'flex';
                                setTimeout(() => {
                                    pageLoader.classList.add('active');
                                }, 10);
                            }
                            
                            // Chuyển hướng dựa trên response
                            setTimeout(function() {
                                if (response.redirect) {
                                    window.location.href = response.redirect;
                                } else {
                                    window.location.href = '/';
                                }
                            }, 2000);
                        }
                    },
                    error: function(xhr) {
                        console.log('Login error:', xhr);
                        console.log('Status code:', xhr.status);
                        console.log('Response:', xhr.responseJSON);
                        
                        $('#loginForm button[type="submit"]').html('Đăng nhập').attr('disabled', false);
                        
                        // Làm mới CSRF token
                        function refreshCSRFToken() {
                            return $.ajax({
                                url: "{{ route('refresh-csrf') }}",
                                type: 'GET',
                                success: function(result) {
                                    $('meta[name="csrf-token"]').attr('content', result);
                                    $('input[name="_token"]').val(result);
                                }
                            });
                        }
                        
                        if (xhr.status === 419) {
                            // CSRF token mismatch - cập nhật token và hiển thị thông báo
                            refreshCSRFToken().done(function() {
                                // Kiểm tra nếu đang đăng nhập tài khoản bị khóa
                                if ($('#login').val()) {
                                    $('#login-message').addClass('error').html('Tài khoản của bạn đã bị khóa. Vui lòng liên hệ quản trị viên để được hỗ trợ.').slideDown();
                                } else {
                                    $('#login-message').addClass('error').html('Vui lòng thử đăng nhập lại.').slideDown();
                                }
                            });
                        } else if (xhr.status === 422) {
                            // Lỗi validation
                            refreshCSRFToken();
                            var errors = xhr.responseJSON.errors;
                            var errorMessages = '';
                            
                            $.each(errors, function(key, value) {
                                errorMessages += value[0] + '<br>';
                            });
                            
                            $('#login-message').addClass('error').html(errorMessages).slideDown();
                        } else if (xhr.status === 401) {
                            // Thông báo đăng nhập sai
                            refreshCSRFToken();
                            $('#login-message').addClass('error').html(
                                (xhr.responseJSON && xhr.responseJSON.message ? 
                                xhr.responseJSON.message : 'Tên đăng nhập/email hoặc mật khẩu không chính xác')
                            ).slideDown();
                        } else if (xhr.status === 403) {
                            // Tài khoản bị khóa
                            refreshCSRFToken();
                            $('#login-message').addClass('error').html(
                                (xhr.responseJSON && xhr.responseJSON.message ? 
                                xhr.responseJSON.message : 'Tài khoản của bạn đã bị khóa. Vui lòng liên hệ quản trị viên để được hỗ trợ.')
                            ).slideDown();
                        } else {
                            // Lỗi khác - hiển thị chi tiết hơn nếu có
                            refreshCSRFToken();
                            var errorMsg = 'Có lỗi xảy ra, vui lòng thử lại sau.';
                            if (xhr.responseJSON && xhr.responseJSON.message) {
                                errorMsg = xhr.responseJSON.message;
                            }
                            $('#login-message').addClass('error').html(errorMsg).slideDown();
                        }
                    }
                });
            });
            
            // Xử lý form submit cho modal đăng ký
            $('#registerForm').on('submit', function(e) {
                e.preventDefault();
                
                // Xóa các thông báo lỗi cũ
                $('.register-error').remove();
                $('#register-alerts').empty();
                
                $.ajax({
                    url: $(this).attr('action'),
                    method: $(this).attr('method'),
                    data: $(this).serialize(),
                    dataType: 'json',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    beforeSend: function() {
                        $('#registerForm button[type="submit"]').html('<i class="fas fa-spinner fa-spin"></i> Đang xử lý...');
                    },
                    success: function(response) {
                        console.log('Register response:', response);
                        
                        if (response.success === false) {
                            // Hiển thị lỗi đăng ký từ server
                            $('#registerForm button[type="submit"]').html('Đăng ký');
                            $('#register-alerts').html('<div class="alert alert-danger register-error">' + response.message + '</div>');
                        } else {
                            // Thành công - hiển thị màn hình loading
                            $('#registerModal').modal('hide');
                            
                            // Kích hoạt màn hình loading
                            const pageLoader = document.getElementById('page-loader');
                            pageLoader.style.display = 'flex';
                            setTimeout(() => {
                                pageLoader.classList.add('active');
                            }, 10);
                            
                            // Chuyển hướng sau khi đăng ký thành công
                            setTimeout(function() {
                                if (response.redirect) {
                                    window.location.href = response.redirect;
                                } else {
                                    window.location.href = '/';
                                }
                            }, 2000);
                        }
                    },
                    error: function(xhr) {
                        console.log('Register error:', xhr);
                        $('#registerForm button[type="submit"]').html('Đăng ký');
                        
                        if (xhr.status === 422) {
                            // Lỗi validation
                            var errors = xhr.responseJSON.errors;
                            
                            // Tạo thông báo tổng hợp
                            var errorMessages = '';
                            
                            $.each(errors, function(key, value) {
                                // Hiển thị lỗi dưới trường tương ứng
                                $('#register_' + key).after('<div class="text-danger register-error">' + value[0] + '</div>');
                                
                                // Kiểm tra lỗi trùng lặp
                                if (key === 'email' && value[0].includes('sử dụng')) {
                                    errorMessages += '<div>● Email này đã được sử dụng, vui lòng chọn email khác</div>';
                                }
                                if (key === 'name' && value[0].includes('sử dụng')) {
                                    errorMessages += '<div>● Tên đăng nhập này đã được sử dụng, vui lòng chọn tên khác</div>';
                                }
                            });
                            
                            // Hiển thị thông báo tổng hợp nếu có
                            if (errorMessages) {
                                $('#register-alerts').html('<div class="alert alert-danger register-error">' + errorMessages + '</div>');
                            }
                        } else {
                            // Lỗi khác
                            $('#register-alerts').html('<div class="alert alert-danger register-error">Có lỗi xảy ra, vui lòng thử lại sau.</div>');
                        }
                    }
                });
            });
        });
    </script>

    <script>
        // Xử lý sticky header và navbar với cách tiếp cận mới
        $(document).ready(function() {
            // Lưu tham chiếu DOM để cải thiện hiệu suất
            const $header = $('#header');
            const $navbar = $('.navbar-container');
            const $spacer = $('#header-spacer');
            
            // Biến để theo dõi trạng thái
            let lastScrollTop = 0;
            let scrollDirection = 'down';
            let ticking = false;
            let isSticky = false;
            
            // Thiết lập kích thước ban đầu
            function updateHeights() {
                // Lấy kích thước thực của các phần tử
                const headerHeight = $header.outerHeight();
                const navbarHeight = $navbar.outerHeight();
                const totalHeight = headerHeight + navbarHeight;
                
                // Thiết lập CSS variables
                document.documentElement.style.setProperty('--header-height', headerHeight + 'px');
                document.documentElement.style.setProperty('--navbar-height', navbarHeight + 'px');
                document.documentElement.style.setProperty('--total-height', totalHeight + 'px');
                
                return { headerHeight, navbarHeight, totalHeight };
            }
            
            // Gọi một lần để thiết lập kích thước ban đầu
            const { headerHeight, navbarHeight, totalHeight } = updateHeights();
            
            // Xử lý sự kiện cuộn trang
            function handleScroll() {
                if (!ticking) {
                    window.requestAnimationFrame(function() {
                        const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
                        
                        // Xác định hướng cuộn
                        scrollDirection = (scrollTop > lastScrollTop && scrollTop > 5) ? 'down' : 'up';
                        
                        // Xử lý bật/tắt sticky mode
                        if (scrollTop > 50) {
                            if (!isSticky) {
                                // Kích hoạt chế độ sticky
                                $header.addClass('sticky');
                                $navbar.addClass('sticky');
                                
                                // Cập nhật vị trí navbar dưới header
                                const stickyHeaderHeight = $header.outerHeight();
                                $navbar.css('top', stickyHeaderHeight + 'px');
                                
                                // Hiển thị và cập nhật chiều cao của spacer để giữ vị trí nội dung
                                $spacer.css('height', totalHeight + 'px').show();
                                
                                isSticky = true;
                            }
                            
                            // Xử lý ẩn/hiện khi cuộn
                            if (scrollDirection === 'down' && scrollTop > 300) {
                                $header.addClass('sticky-hide');
                                $navbar.addClass('sticky-hide');
                            } else {
                                $header.removeClass('sticky-hide');
                                $navbar.removeClass('sticky-hide');
                            }
                            
                            // Cập nhật vị trí navbar khi header ẩn/hiện
                            if ($header.hasClass('sticky-hide')) {
                                $navbar.css('top', '0');
                            } else {
                                const stickyHeaderHeight = $header.outerHeight();
                                $navbar.css('top', stickyHeaderHeight + 'px');
                            }
                        } else if (isSticky) {
                            // Tắt chế độ sticky
                            $header.removeClass('sticky sticky-hide');
                            $navbar.removeClass('sticky sticky-hide').css('top', '');
                            $spacer.css('height', '0').hide();
                            isSticky = false;
                        }
                        
                        // Lưu vị trí cuộn hiện tại
                        lastScrollTop = scrollTop <= 0 ? 0 : scrollTop;
                        ticking = false;
                    });
                    
                    ticking = true;
                }
            }
            
            // Sử dụng addEventListener với passive: true để cải thiện hiệu suất
            window.addEventListener('scroll', handleScroll, { passive: true });
            
            // Gọi lần đầu để xử lý trường hợp trang đã cuộn khi tải
            handleScroll();
            
            // Xử lý resize cửa sổ với debounce
            let resizeTimeout;
            window.addEventListener('resize', function() {
                clearTimeout(resizeTimeout);
                resizeTimeout = setTimeout(function() {
                    // Cập nhật lại kích thước
                    const newHeights = updateHeights();
                    
                    // Nếu đang ở chế độ sticky, cập nhật spacer và vị trí navbar
                    if (isSticky) {
                        $spacer.css('height', newHeights.totalHeight + 'px');
                        const stickyHeaderHeight = $header.outerHeight();
                        $navbar.css('top', stickyHeaderHeight + 'px');
                    }
                }, 200);
            }, { passive: true });
        });
    </script>

    <!-- Notification Script -->
    <script>
        $(document).ready(function() {
            // Biến lưu trạng thái dropdown
            let notificationDropdownOpen = false;
            
            // Xử lý sự kiện click vào icon thông báo
            $('#notification-icon').on('click', function(e) {
                e.stopPropagation();
                
                // Toggle dropdown
                if (notificationDropdownOpen) {
                    $('#notification-dropdown').hide();
                    notificationDropdownOpen = false;
                } else {
                    $('#notification-dropdown').show();
                    notificationDropdownOpen = true;
                    
                    // Load thông báo khi mở dropdown
                    loadNotifications();
                }
            });
            
            // Đóng dropdown khi click ra ngoài
            $(document).on('click', function() {
                if (notificationDropdownOpen) {
                    $('#notification-dropdown').hide();
                    notificationDropdownOpen = false;
                }
            });
            
            // Ngăn sự kiện click trong dropdown lan ra ngoài
            $('#notification-dropdown').on('click', function(e) {
                e.stopPropagation();
            });
            
            // Đánh dấu tất cả thông báo đã đọc
            $('#mark-all-read').on('click', function(e) {
                e.preventDefault();
                
                $.ajax({
                    url: "{{ route('notifications.mark-all-read') }}",
                    type: "POST",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        if (response.success) {
                            // Cập nhật UI sau khi đánh dấu đã đọc
                            $('.notification-item').removeClass('unread');
                            $('#notification-count').text('0');
                        }
                    }
                });
            });
            
            // Xóa tất cả thông báo đã đọc - không cần xác nhận
            $('#delete-all-read').on('click', function(e) {
                e.preventDefault();
                
                $.ajax({
                    url: "{{ route('notifications.delete-all') }}",
                    type: "DELETE",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        if (response.success) {
                            // Tải lại thông báo
                            loadNotifications();
                        }
                    }
                });
            });
            
            // Hàm tải thông báo
            function loadNotifications() {
                $.ajax({
                    url: "{{ route('notifications.get') }}",
                    type: "GET",
                    success: function(response) {
                        // Cập nhật số lượng thông báo chưa đọc
                        $('#notification-count').text(response.unreadCount);
                        
                        // Hiển thị danh sách thông báo
                        renderNotifications(response.notifications);
                    },
                    error: function() {
                        $('#notification-list').html('<div class="no-notifications">Không thể tải thông báo</div>');
                    }
                });
            }
            
            // Hàm render danh sách thông báo
            function renderNotifications(notifications) {
                if (!notifications || notifications.length === 0) {
                    $('#notification-list').html('<div class="no-notifications">Không có thông báo</div>');
                    return;
                }
                
                let html = '';
                
                notifications.forEach(function(notification) {
                    // Xác định đường dẫn ảnh
                    let imagePath = notification.image;
                    if (imagePath && !imagePath.startsWith('http')) {
                        imagePath = '{{ asset("uploads/movie") }}/' + imagePath;
                    }
                    
                    // Định dạng thời gian
                    let createdAt = new Date(notification.created_at);
                    let timeAgo = timeAgoFormat(createdAt);
                    
                    html += `
                    <div class="notification-item ${!notification.is_read ? 'unread' : ''}" data-id="${notification.id}">
                        <a href="${notification.link}" class="notification-link">
                            <img src="${imagePath || '{{ asset("images/default-movie.jpg") }}'}" class="notification-img" alt="${notification.title}">
                            <div class="notification-content">
                                <div class="notification-title">${notification.title}</div>
                                <div class="notification-message">${notification.message}</div>
                                <div class="notification-time">${timeAgo}</div>
                            </div>
                        </a>
                        <div class="notification-delete" data-id="${notification.id}">
                            <i class="fas fa-times"></i>
                        </div>
                    </div>`;
                });
                
                $('#notification-list').html(html);
                
                // Xử lý sự kiện click vào thông báo
                $('.notification-link').on('click', function() {
                    const notificationId = $(this).parent().data('id');
                    markAsRead(notificationId);
                });
                
                // Xử lý sự kiện click vào nút xóa thông báo
                $('.notification-delete').on('click', function(e) {
                    e.stopPropagation();
                    const notificationId = $(this).data('id');
                    deleteNotification(notificationId);
                });
            }
            
            // Hàm đánh dấu thông báo đã đọc
            function markAsRead(id) {
                $.ajax({
                    url: "{{ url('notifications/mark-as-read') }}/" + id,
                    type: "POST",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        if (response.success) {
                            // Giảm số lượng thông báo chưa đọc
                            const currentCount = parseInt($('#notification-count').text());
                            if (currentCount > 0) {
                                $('#notification-count').text(currentCount - 1);
                            }
                        }
                    }
                });
            }
            
            // Hàm xóa một thông báo
            function deleteNotification(id) {
                $.ajax({
                    url: "{{ url('notifications/delete') }}/" + id,
                    type: "DELETE",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        if (response.success) {
                            // Xóa phần tử khỏi DOM
                            $(`.notification-item[data-id="${id}"]`).fadeOut(300, function() {
                                $(this).remove();
                                
                                // Kiểm tra nếu không còn thông báo nào
                                if ($('.notification-item').length === 0) {
                                    $('#notification-list').html('<div class="no-notifications">Không có thông báo</div>');
                                }
                            });
                        }
                    }
                });
            }
            
            // Hàm định dạng thời gian (thời gian tương đối)
            function timeAgoFormat(date) {
                const now = new Date();
                const diffInSeconds = Math.floor((now - date) / 1000);
                
                if (diffInSeconds < 60) {
                    return 'Vừa xong';
                }
                
                const diffInMinutes = Math.floor(diffInSeconds / 60);
                if (diffInMinutes < 60) {
                    return `${diffInMinutes} phút trước`;
                }
                
                const diffInHours = Math.floor(diffInMinutes / 60);
                if (diffInHours < 24) {
                    return `${diffInHours} giờ trước`;
                }
                
                const diffInDays = Math.floor(diffInHours / 24);
                if (diffInDays < 30) {
                    return `${diffInDays} ngày trước`;
                }
                
                const diffInMonths = Math.floor(diffInDays / 30);
                if (diffInMonths < 12) {
                    return `${diffInMonths} tháng trước`;
                }
                
                const diffInYears = Math.floor(diffInMonths / 12);
                return `${diffInYears} năm trước`;
            }
            
            // Tải số lượng thông báo khi trang được tải
            @auth
                loadNotifications();
                
                // Kiểm tra thông báo mới mỗi 1 phút
                setInterval(function() {
                    if (!notificationDropdownOpen) { // Chỉ tải khi dropdown đóng
                        $.ajax({
                            url: "{{ route('notifications.get') }}",
                            type: "GET",
                            success: function(response) {
                                $('#notification-count').text(response.unreadCount);
                            }
                        });
                    }
                }, 60000); // 60 giây
            @endauth
        });
    </script>

</body>

</html>