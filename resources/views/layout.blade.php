<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <meta content="width=device-width,initial-scale=1.0, maximum-scale=1.0, user-scalable=no" name="viewport">
    <meta name="theme-color" content="#0f1824">
    <meta http-equiv="Content-Language" content="vi">
    <meta content="VN" name="geo.region">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="shortcut icon" href="/favicon.png" type="image/png">

    <title>Ndn Phim 2025 - Xem phim hay nhất</title>
    <meta name="description" content="Ndn Phim 2025 - Kho phim chất lượng cao, cập nhật nhanh nhất, xem phim online miễn phí HD">
    <link rel="canonical" href="">

    <!-- SEO Meta Tags -->
    <meta property="og:locale" content="vi_VN">
    <meta property="og:title" content="Ndn Phim 2025 - Xem phim hay nhất">
    <meta property="og:description" content="Kho phim chất lượng cao 2025 - Phim lẻ, phim bộ, phim chiếu rạp. Tuyển chọn phim hay nhất Việt Nam, Hàn Quốc, Trung Quốc, Mỹ...">
    <meta property="og:url" content="">
    <meta property="og:site_name" content="Phim hay 2025">
    <meta property="og:image" content="">

    <!-- CSS Files -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <link rel='stylesheet' href='{{asset('css/bootstrap.min.css')}}'>
    <link rel='stylesheet' href='{{asset('css/style.css')}}'>

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&family=Be+Vietnam+Pro:wght@400;500;600;700&display=swap" rel="stylesheet">
    <div id="fb-root"></div>
    <script async defer crossorigin="anonymous" src="https://connect.facebook.net/vi_VN/sdk.js#xfbml=1&version=v22.0"></script>
    <script src='{{asset('js/jquery.min.js')}}'></script>

    <style>
        :root {
            /* Main Colors */
            --primary-dark: #0f1824;
            --primary-medium: #1b2e43;
            --primary-light: #253b53;
            --accent-color: #ff6b6b;
            --accent-hover: #ff8585;
            --accent-soft: rgba(255, 107, 107, 0.1);
            --text-light: #ffffff;
            --text-muted: rgba(255, 255, 255, 0.7);
            --text-faded: rgba(255, 255, 255, 0.5);
            --rating-color: #ffce54;
            --genre-color: #4cd964;
            --year-color: rgba(0, 0, 0, 0.926);

            /* Shadows & Effects */
            --card-shadow: 0 10px 20px rgba(0, 0, 0, 0.19), 0 6px 6px rgba(0, 0, 0, 0.23);
            --hover-shadow: 0 14px 28px rgba(0, 0, 0, 0.25), 0 10px 10px rgba(0, 0, 0, 0.22);
            --nav-shadow: 0 3px 10px rgba(0, 0, 0, 0.15);
            --btn-shadow: 0 3px 6px rgba(0, 0, 0, 0.16);
            --glass-effect: rgba(25, 39, 58, 0.8);
            --glass-blur: blur(10px);

            /* Transitions */
            --transition-fast: all 0.2s ease;
            --transition-normal: all 0.3s ease;
            --transition-slow: all 0.5s ease;
            --transition-bounce: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        }

        /* -- Base Styles -- */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background-color: var(--primary-dark);
            color: var(--text-light);
            font-family: 'Roboto', sans-serif;
            line-height: 1.6;
            overflow-x: hidden;
        }

        a {
            color: var(--text-light);
            text-decoration: none;
            transition: var(--transition-normal);
        }

        a:hover {
            color: var(--accent-color);
        }

        h1, h2, h3, h4, h5, h6 {
            font-family: 'Be Vietnam Pro', sans-serif;
            font-weight: 600;
        }

        .container {
            width: 100%;
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 15px;
        }

        /* --- Header Styles --- */
        #header {
            background-color: var(--primary-medium);
            position: sticky;
            top: 0;
            z-index: 100;
            padding: 15px 0;
            box-shadow: var(--nav-shadow);
            backdrop-filter: var(--glass-blur);
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
        }

        .header-inner {
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .site-logo {
            display: flex;
            align-items: center;
        }

        .logo-image {
            height: 70px;
            margin-right: 10px;
        }

        .logo-text {
            font-size: 1.8rem;
            font-weight: 700;
            color: var(--text-light);
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .logo-text span {
            color: var(--accent-color);
        }

        /* -- Navigation -- */
        .navbar-container {
            background-color: var(--primary-light);
            padding: 5px 0;
            box-shadow: var(--nav-shadow);
        }

        .main-nav {
            display: flex;
            justify-content: space-between;
        }

        .nav-menu {
            display: flex;
            list-style: none;
            margin-bottom: 0;
        }

        .nav-item {
            position: relative;
            margin-right: 5px;
        }

        .nav-link {
            display: block;
            padding: 10px 15px;
            font-weight: 500;
            color: var(--text-light);
            border-radius: 4px;
            transition: var(--transition-normal);
        }

        .nav-link:hover,
        .nav-item.active .nav-link {
            background: var(--accent-soft);
            color: var(--accent-color);
        }

        .nav-link i {
            margin-right: 5px;
        }

        .dropdown-toggle::after {
            content: '▼';
            display: inline-block;
            font-size: 0.6em;
            margin-left: 5px;
            transition: var(--transition-normal);
        }

        .dropdown:hover .dropdown-toggle::after {
            transform: rotate(180deg);
        }

        .dropdown-menu {
            position: absolute;
            top: 100%;
            left: 0;
            background: var(--glass-effect);
            backdrop-filter: var(--glass-blur);
            min-width: 200px;
            border-radius: 8px;
            box-shadow: var(--card-shadow);
            padding: 10px 0;
            z-index: 999;
            opacity: 0;
            visibility: hidden;
            transform: translateY(10px);
            transition: var(--transition-normal);
            border: 1px solid rgba(255, 255, 255, 0.05);
            max-height: 400px;
            overflow-y: auto;
        }

        .dropdown-menu.show,
        .dropdown:hover .dropdown-menu {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
        }

        .dropdown-item {
            position: relative;
            padding-left: 15px;
        }

        .dropdown-item:before {
            content: '';
            position: absolute;
            left: 0;
            top: 50%;
            width: 5px;
            height: 5px;
            background: var(--accent-color);
            border-radius: 50%;
            transform: translateY(-50%) scale(0);
            opacity: 0;
            transition: transform 0.3s ease, opacity 0.3s ease;
        }

        .dropdown-item:hover:before {
            transform: translateY(-50%) scale(1);
            opacity: 1;
        }

        /* Hiệu ứng hover cho dropdown toggle */
        .nav-link.dropdown-toggle:after {
            margin-left: 5px;
            transition: transform 0.3s ease;
        }

        .nav-item.dropdown:hover .nav-link.dropdown-toggle:after,
        .nav-link.dropdown-toggle.active:after {
            transform: rotate(180deg);
        }

        .filter-btn {
            display: inline-flex;
            align-items: center;
            background: var(--accent-color);
            color: var(--primary-dark) !important;
            font-weight: 600;
            padding: 8px 20px;
            border-radius: 50px;
            transition: var(--transition-normal);
            box-shadow: var(--btn-shadow);
            margin-top: 5px;
        }

        .filter-btn:hover {
            background: var(--accent-hover);
            transform: translateY(-2px);
            box-shadow: var(--hover-shadow);
        }

        .filter-btn i {
            margin-right: 8px;
        }

        /* -- Search Box -- */
        .search-wrapper {
            flex: 1;
            max-width: 500px;
            margin: 0 30px;
        }

        .search-box {
            position: relative;
            border-radius: 50px;
            overflow: hidden;
            background: rgba(15, 24, 36, 0.6);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2), inset 0 0 0 1px rgba(255, 255, 255, 0.05);
            backdrop-filter: var(--glass-blur);
            transition: var(--transition-bounce);
            display: flex;
            align-items: center;
        }

        .search-box:focus-within {
            box-shadow: 0 6px 18px rgba(0, 0, 0, 0.25), 0 0 0 2px rgba(255, 107, 107, 0.3);
        }

        .search-box input {
            flex: 1;
            background: transparent;
            border: none;
            color: var(--text-light);
            height: 50px;
            font-size: 16px;
            padding: 0 20px;
            outline: none;
        }

        .search-box input::placeholder {
            color: var(--text-faded);
            font-style: italic;
            transition: var(--transition-normal);
        }

        .search-box:focus-within input::placeholder {
            opacity: 0.7;
            transform: translateX(5px);
        }

        .search-icon,
        .clear-search {
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--text-muted);
            font-size: 18px;
            width: 50px;
            height: 50px;
            cursor: pointer;
            transition: var(--transition-normal);
        }

        .search-icon {
            background: rgba(255, 107, 107, 0.1);
        }

        .search-box:focus-within .search-icon {
            color: var(--accent-color);
        }

        .clear-search {
            opacity: 0;
            visibility: hidden;
            transform: scale(0.8);
            transition: var(--transition-normal);
        }

        .clear-search.visible {
            opacity: 1;
            visibility: visible;
            transform: scale(1);
        }

        .clear-search:hover {
            color: var(--accent-color);
            transform: scale(1.1) rotate(90deg);
        }

        /* --- Search Results --- */
        .search-results {
            position: absolute;
            z-index: 999;
            width: 50%;
            top: 100%;
            left: 50%;
            margin-top: 10px;
            border-radius: 12px;
            background: var(--glass-effect);
            backdrop-filter: var(--glass-blur);
            overflow: hidden;
            box-shadow: var(--card-shadow);
            border: 1px solid rgba(255, 255, 255, 0.05);
            transition: var(--transition-normal);
            visibility: hidden;
            opacity: 0;
            transform: translate(-50%, -10px);
        }

        .search-results.show {
            visibility: visible;
            opacity: 1;
            transform: translate(-50%, 0);
        }

        #result {
            max-height: 450px;
            overflow-y: auto;
            list-style: none;
            margin: 0;
            padding: 0;
        }

        .search-result-item {
            display: flex;
            align-items: center;
            padding: 15px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
            transition: var(--transition-normal);
            cursor: pointer;
            animation: fadeInUp 0.3s ease;
            animation-fill-mode: both;
        }

        .search-result-item:hover {
            background: rgba(255, 255, 255, 0.05);
        }

        .search-result-item.active {
            background: var(--accent-soft);
        }

        .search-movie-poster {
            position: relative;
            width: 60px;
            height: 90px;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: var(--card-shadow);
            flex-shrink: 0;
            transition: var(--transition-normal);
        }

        .search-result-item:hover .search-movie-poster {
            transform: scale(1.05);
        }

        .search-movie-poster img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: var(--transition-normal);
        }

        .movie-badge {
            position: absolute;
            padding: 3px 5px;
            font-size: 10px;
            font-weight: 600;
            border-radius: 4px;
            z-index: 1;
        }

        .badge-rating {
            top: 5px;
            right: 5px;
            background: var(--rating-color);
            color: #333;
        }

        .badge-year {
            bottom: 5px;
            right: 5px;
            background: var(--year-color);
            color: #fff9f9;
        }

        .movie-info {
            flex: 1;
            margin-left: 15px;
            overflow: hidden;
        }

        .movie-title {
            font-weight: 600;
            font-size: 16px;
            margin-bottom: 5px;
            color: var(--text-light);
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .search-result-item:hover .movie-title {
            color: var(--accent-color);
        }

        .movie-meta {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 5px;
        }

        .movie-genre {
            color: var(--genre-color);
            font-size: 12px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .movie-desc {
            color: var(--text-muted);
            font-size: 13px;
            display: -webkit-box;
            -webkit-box-orient: vertical;
            -webkit-line-clamp: 2;
            overflow: hidden;
            line-height: 1.4;
        }

        .movie-action {
            margin-left: 10px;
            color: var(--text-faded);
            font-size: 24px;
            transition: var(--transition-normal);
        }

        .search-result-item:hover .movie-action {
            color: var(--accent-color);
            transform: scale(1.2);
        }

        #no-results,
        #search-loading {
            padding: 25px;
            text-align: center;
        }

        #no-results i,
        #search-loading i {
            font-size: 32px;
            margin-bottom: 15px;
            display: block;
            color: var(--accent-color);
        }

        #search-loading i {
            animation: spin 1s infinite linear;
        }

        #no-results p,
        #search-loading p {
            font-size: 16px;
            margin-bottom: 5px;
        }

        #no-results small,
        #search-loading small {
            color: var(--text-muted);
        }

        /* -- User Actions Area -- */
        .user-actions {
            display: flex;
            align-items: center;
        }

        .bookmark-btn {
            display: flex;
            align-items: center;
            background: rgba(255, 255, 255, 0.1);
            padding: 8px 15px;
            border-radius: 50px;
            cursor: pointer;
            transition: var(--transition-normal);
        }

        .bookmark-btn:hover {
            background: rgba(255, 255, 255, 0.2);
        }

        .bookmark-btn i {
            color: var(--accent-color);
            margin-right: 8px;
        }

        .bookmark-count {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: var(--primary-dark);
            color: var(--text-light);
            width: 20px;
            height: 20px;
            border-radius: 50%;
            font-size: 12px;
            margin-left: 8px;
        }

        /* -- Content Area -- */
        .main-content {
            padding: 30px 0;
        }

        /* -- Movie Cards -- */
        .movie-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
            gap: 20px;
            margin: 20px 0;
        }

        .movie-card {
            position: relative;
            border-radius: 12px;
            overflow: hidden;
            background: var(--primary-medium);
            box-shadow: var(--card-shadow);
            transition: var(--transition-normal);
            transform: translateY(0);
        }

        .movie-card:hover {
            transform: translateY(-10px);
            box-shadow: var(--hover-shadow);
        }

        .movie-card-poster {
            position: relative;
            height: 250px;
            overflow: hidden;
        }

        .movie-card-poster img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: var(--transition-slow);
        }

        .movie-card:hover .movie-card-poster img {
            transform: scale(1.1);
        }

        .movie-card-overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(to bottom, rgba(0,0,0,0) 0%, rgba(0,0,0,0.8) 100%);
            opacity: 0;
            transition: var(--transition-normal);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .movie-card:hover .movie-card-overlay {
            opacity: 1;
        }

        .play-btn {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 60px;
            height: 60px;
            background: var(--accent-color);
            color: white;
            border-radius: 50%;
            font-size: 24px;
            transform: scale(0);
            transition: var(--transition-bounce);
        }

        .movie-card:hover .play-btn {
            transform: scale(1);
        }

        .play-btn:hover {
            background: var(--accent-hover);
            transform: scale(1.1);
        }

        .movie-card-badge {
            position: absolute;
            padding: 5px 10px;
            font-size: 12px;
            font-weight: 600;
            border-radius: 4px;
            z-index: 2;
        }

        .card-badge-rating {
            top: 10px;
            right: 10px;
            background: var(--rating-color);
            color: #333;
        }

        .card-badge-quality {
            top: 10px;
            left: 10px;
            background: var(--accent-color);
            color: white;
        }

        .movie-card-content {
            padding: 15px;
        }

        .movie-card-title {
            font-size: 15px;
            font-weight: 600;
            margin-bottom: 5px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .movie-card-meta {
            display: flex;
            justify-content: space-between;
            color: var(--text-muted);
            font-size: 12px;
        }

        /* -- Star Rating Component -- */
        .stars-rating {
            display: inline-flex;
            align-items: center;
            font-size: 14px;
            margin-right: 8px;
            margin-bottom: 8px;
        }

        .stars-container {
    display: inline-flex;
    position: relative;
    margin-right: 5px;
    color: rgba(255, 255, 255, 0.2);
    letter-spacing: -2px; /* Thêm giá trị này để sao gần nhau hơn */
}

        .stars-empty {
            display: inline-flex;
        }

        .stars-filled {
            position: absolute;
            top: 0;
            left: 0;
            white-space: nowrap;
            overflow: hidden;
            color: var(--rating-color);
        }

        .movie-card .stars-rating {
            margin-top: 5px;
        }

        .search-result-item .stars-rating {
            font-size: 12px;
        }

        .rating-number {
            font-weight: 600;
            font-size: 0.9em;
            margin-left: 5px;
            color: var(--rating-color);
        }

        /* -- Section Titles -- */
        .section-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 20px;
            border-bottom: 1px solid rgba(255,255,255,0.1);
            padding-bottom: 10px;
        }

        .section-title {
            font-size: 24px;
            font-weight: 700;
            color: var(--text-light);
            position: relative;
            padding-left: 15px;
        }

        .section-title::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            bottom: 0;
            width: 4px;
            background: var(--accent-color);
            border-radius: 4px;
        }

        .view-all {
            color: var(--accent-color);
            font-size: 14px;
            font-weight: 500;
            transition: var(--transition-normal);
        }

        .view-all:hover {
            transform: translateX(5px);
        }

        /* -- Footer -- */
        .site-footer {
            background: var(--primary-medium);
            padding: 40px 0 20px;
            margin-top: 40px;
            border-top: 1px solid rgba(255,255,255,0.05);
        }

        .footer-logo {
            height: 60px;
            margin-bottom: 20px;
        }

        .footer-content {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 30px;
        }

        .footer-column h3 {
            font-size: 18px;
            margin-bottom: 20px;
            position: relative;
            padding-bottom: 10px;
        }

        .footer-column h3::after {
            content: '';
            position: absolute;
            left: 0;
            bottom: 0;
            width: 40px;
            height: 2px;
            background: var(--accent-color);
        }

        .footer-links {
            list-style: none;
            padding: 0;
        }

        .footer-links li {
            margin-bottom: 10px;
        }

        .footer-links a {
            color: var(--text-muted);
            transition: var(--transition-normal);
        }

        .footer-links a:hover {
            color: var(--accent-color);
            padding-left: 5px;
        }

        .footer-social {
            display: flex;
            gap: 15px;
            margin-top: 20px;
        }

        .social-icon {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
            background: rgba(255,255,255,0.1);
            border-radius: 50%;
            color: var(--text-light);
            font-size: 18px;
            transition: var(--transition-normal);
        }

        .social-icon:hover {
            background: var(--accent-color);
            color: white;
            transform: translateY(-5px);
        }

        .footer-bottom {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid rgba(255,255,255,0.05);
            text-align: center;
            color: var(--text-muted);
            font-size: 14px;
        }

        /* --- Back to Top Button --- */
        #back-to-top {
            position: fixed;
            bottom: 30px;
            right: 30px;
            width: 50px;
            height: 50px;
            background: var(--accent-color);
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            cursor: pointer;
            opacity: 0;
            visibility: hidden;
            transform: translateY(20px);
            transition: var(--transition-normal);
            z-index: 99;
            box-shadow: 0 3px 10px rgba(0,0,0,0.3);
        }

        #back-to-top.visible {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
        }

        #back-to-top:hover {
            background: var(--accent-hover);
            transform: translateY(-5px);
        }

        /* -- Toast Notifications -- */
        .toast-container {
            position: fixed;
            bottom: 20px;
            left: 50%;
            transform: translateX(-50%);
            z-index: 9999;
        }

        .toast {
            background: var(--glass-effect);
            backdrop-filter: var(--glass-blur);
            color: var(--text-light);
            padding: 15px 25px;
            border-radius: 8px;
            box-shadow: var(--card-shadow);
            margin-bottom: 10px;
            display: flex;
            align-items: center;
            border-left: 4px solid var(--accent-color);
            animation: toastIn 0.5s forwards, toastOut 0.5s forwards 3s;
        }

        .toast-icon {
            margin-right: 15px;
            font-size: 20px;
            color: var(--accent-color);
        }

        /* -- Animations -- */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes spin {
            from {
                transform: rotate(0deg);
            }
            to {
                transform: rotate(360deg);
            }
        }

        @keyframes toastIn {
            from {
                transform: translateY(100px);
                opacity: 0;
            }
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        @keyframes toastOut {
            from {
                transform: translateY(0);
                opacity: 1;
            }
            to {
                transform: translateY(-20px);
                opacity: 0;
            }
        }

        /* -- Responsive Styles -- */
        @media (max-width: 992px) {
            .search-wrapper {
                margin: 0 15px;
            }
            .movie-grid {
                grid-template-columns: repeat(auto-fill, minmax(160px, 1fr));
            }
        }

        @media (max-width: 768px) {
            .header-inner {
                flex-direction: column;
            }
            .search-wrapper {
                width: 100%;
                max-width: 100%;
                margin: 15px 0;
            }
            .nav-menu {
                flex-wrap: wrap;
            }
            .movie-card-poster {
                height: 220px;
            }
            .movie-desc {
                display: none;
            }
            #result {
                max-height: 350px;
            }
            .search-movie-poster {
                width: 50px;
                height: 75px;
            }
        }

        @media (max-width: 576px) {
            .movie-grid {
                grid-template-columns: repeat(auto-fill, minmax(140px, 1fr));
                gap: 15px;
            }
            .movie-card-poster {
                height: 200px;
            }
            .section-title {
                font-size: 20px;
            }
            .search-result-item {
                padding: 10px;
            }
            .movie-meta {
                flex-direction: column;
                align-items: flex-start;
                gap: 2px;
            }
        }
    </style>
</head>
<body>
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
                    <div class="search-box">
                        <div class="search-icon">
                            <i class="fas fa-search"></i>
                        </div>
                        <input id="timkiem" type="text" placeholder="Tìm kiếm phim..." autocomplete="off">
                        <div class="clear-search">
                            <i class="fas fa-times"></i>
                        </div>
                    </div>

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
                    <div class="bookmark-btn" id="get-bookmark">
                        <i class="fas fa-bookmark"></i>
                        <span>Bookmarks</span>
                        <span class="bookmark-count">0</span>
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
                                <li><a class="dropdown-item" href="{{route('country', $count->slug)}}">{{$count->title}}</a></li>
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
                <a href="#" class="filter-btn" onclick="locphim()">
                    <i class="fas fa-filter"></i>Lọc Phim
                </a>
            </nav>
        </div>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <div class="container">
            @yield('content')
        </div>
    </div>

    <!-- Footer -->
    <footer class="site-footer">
        <div class="container">
            <img src="https://example.com/logo.png" alt="PhimHay" class="footer-logo">
            <div class="footer-content">
                <div class="footer-column">
                    <h3>Về chúng tôi</h3>
                    <p>PhimHay cung cấp kho phim HD chất lượng cao miễn phí. Chúng tôi liên tục cập nhật những bộ phim mới nhất với chất lượng tốt nhất.</p>

                    <div class="footer-social">
                        <a href="#" class="social-icon"><i class="fab fa-facebook-f"></i></a>
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
                    <p>Liên hệ QC: <a href="mailto:contact@example.com">contact@example.com</a></p>
                    <p>Hỗ trợ: <a href="mailto:support@example.com">support@example.com</a></p>
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
    <script src='{{asset('js/bootstrap.min.js')}}'></script>
    <script src='{{asset('js/owl.carousel.min.js')}}'></script>
    <script>
        $(document).ready(function() {
            // Variables
            let searchTimeout = null;
            let isSearching = false;
            let currentFocus = -1;

            // Initialize search box
            initializeSearch();

            // Initialize back to top button
            initBackToTop();

            // Hàm tạo HTML đánh giá sao
function generateStarRating(rating) {
    // Đảm bảo rating trong phạm vi 0-5
    let validRating = parseFloat(rating);
    validRating = isNaN(validRating) ? 0 : validRating;
    validRating = Math.min(5, Math.max(0, validRating));
    
    const ratingPercentage = validRating * 22; // Chuyển đổi thành phần trăm
    
    return `
        <div class="stars-rating">
            <div class="stars-container">
                <div class="stars-empty">
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    
                </div>
                <div class="stars-filled" style="width: ${ratingPercentage}%">
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                </div>
            </div>
            <span class="rating-number">${validRating.toFixed(1)}</span>
        </div>
    `;
}


            // Search functions
            function initializeSearch() {
                // Handle search input
                $("#timkiem").on('input focus', function(e) {
                    const searchTerm = $(this).val().trim();
                    
                    // Clear previous timeout
                    clearTimeout(searchTimeout);
                    
                    // Toggle clear button visibility
                    toggleClearButton(searchTerm);

                    if (searchTerm.length > 0) {
                        if (e.type === 'input') {
                            // Add ripple effect
                            createRippleEffect();
                            
                            // Show loading state
                            showLoading();
                            
                            // Set timeout for search
                            searchTimeout = setTimeout(function() {
                                performSearch(searchTerm);
                            }, 400);
                        } else if (e.type === 'focus' && $("#result li").length > 0) {
                            $(".search-results").addClass("show");
                        }
                    } else {
                        resetSearchState();
                    }
                });

                // Keyboard navigation
                $("#timkiem").on('keydown', handleKeyboardNavigation);

                // Click on search result
                $("#result").on('click', 'li', handleResultClick);

                // Clear search button
                $(".clear-search").on('click', clearSearch);

                // Click search icon focuses input
                $(".search-icon").on('click', function() {
                    $("#timkiem").focus();
                });

                // Close search results when clicking outside
                $(document).on('click', function(e) {
                    if (!$(e.target).closest('.search-box, .search-results').length) {
                        resetSearchState();
                    }
                });
            }

            // Toggle clear button visibility
            function toggleClearButton(searchTerm) {
                if (searchTerm.length > 0) {
                    $(".clear-search").addClass("visible");
                } else {
                    $(".clear-search").removeClass("visible");
                }
            }

            // Create ripple effect when typing
            function createRippleEffect() {
                const ripple = $('<span class="search-ripple"></span>');
                $(".search-icon").append(ripple);
                setTimeout(() => {
                    ripple.remove();
                }, 600);
            }

            // Show loading state
            function showLoading() {
                $("#result, #no-results").hide();
                isSearching = true;
                $("#search-loading").show();
                $(".search-results").addClass("show");
            }

            // Handle keyboard navigation
            function handleKeyboardNavigation(e) {
                const resultItems = $("#result li");

                // Down arrow
                if (e.keyCode === 40 && resultItems.length > 0) {
                    currentFocus++;
                    if (currentFocus >= resultItems.length) currentFocus = 0;
                    setActiveSuggestion(resultItems);
                    e.preventDefault();
                }
                // Up arrow
                else if (e.keyCode === 38 && resultItems.length > 0) {
                    currentFocus--;
                    if (currentFocus < 0) currentFocus = resultItems.length - 1;
                    setActiveSuggestion(resultItems);
                    e.preventDefault();
                }
                // Enter key
                else if (e.keyCode === 13 && currentFocus > -1 && resultItems.length > 0) {
                    $(resultItems[currentFocus]).trigger('click');
                    e.preventDefault();
                }
                // Escape key
                else if (e.keyCode === 27) {
                    resetSearchState();
                }
            }

            // Set active suggestion for keyboard navigation
            function setActiveSuggestion(items) {
                items.removeClass("active");

                if (currentFocus >= 0) {
                    $(items[currentFocus]).addClass("active");

                    // Scroll to active item
                    const container = $("#result");
                    const item = $(items[currentFocus]);

                    container.scrollTop(
                        item.offset().top - container.offset().top + container.scrollTop() - (container.height() / 2 - item.height() / 2)
                    );
                }
            }

            // Perform search
            function performSearch(query) {
                // Log search for analytics
                logSearch(query);

                $.getJSON("/json/movies.json", function(data) {
                    $("#result").empty();
                    let resultCount = 0;
                    const searchRegex = new RegExp(query, "i");

                    // Sort results to prioritize title matches
                    data.sort((a, b) => {
                        const aTitle = a.title.search(searchRegex) !== -1;
                        const bTitle = b.title.search(searchRegex) !== -1;

                        if (aTitle && !bTitle) return -1;
                        if (!aTitle && bTitle) return 1;
                        return 0;
                    });

                    // Process results
                    $.each(data, function(key, movie) {
                        // Limit to 8 results for better UX
                        if (resultCount >= 8) return false;

                        if (movie.title.search(searchRegex) !== -1 ||
                            (movie.description && movie.description.search(searchRegex) !== -1)) {
                            resultCount++;
                            addSearchResult(movie, key, query);
                        }
                    });

                    // Reset focus
                    currentFocus = -1;

                    // Display results or no results message
                    $("#search-loading").hide();

                    if (resultCount > 0) {
                        $("#result").show();
                        $("#no-results").hide();

                        // Add animation delay to results
                        $("#result li").each(function(index) {
                            $(this).css({ 'animation-delay': (index * 0.05) + 's' });
                        });
                    } else {
                        $("#result").hide();
                        $("#no-results").html(`
                            <i class="fas fa-search"></i>
                            <p>Không tìm thấy kết quả cho <strong>"${query}"</strong></p>
                            <small>Hãy thử tìm kiếm với từ khóa khác</small>
                        `).show();
                    }

                    isSearching = false;

                }).fail(function() {
                    handleSearchError();
                });
            }

            // Add a search result to the list
            function addSearchResult(movie, key, query) {
                const highlightedTitle = highlightText(movie.title, query);
                const genres = movie.genres ? movie.genres.join(", ") : "";
                const rating = movie.rating ? movie.rating : "5.0";
                const starRating = generateStarRating(rating);
                const year = movie.year || 'N/A';

                let description = '';
                if (movie.description) {
                    description = highlightText(
                        movie.description.length > 80 ? movie.description.substring(0, 80) + '...' : movie.description,
                        query
                    );
                }

                const resultItem = $(`
                    <li class="search-result-item" data-id="${movie.id || key}">
                        <div class="search-movie-poster">
                            <img src="/uploads/movie/${movie.image}" alt="${movie.title}">
                            <span class="movie-badge badge-year">${year}</span>
                        </div>
                        <div class="movie-info">
                            <div class="movie-title">${highlightedTitle}</div>
                            <div class="movie-meta">
                                ${starRating}
                                ${genres ? `<span class="movie-genre">${genres}</span>` : ''}
                            </div>
                            ${description ? `<div class="movie-desc">${description}</div>` : ''}
                        </div>
                        <div class="movie-action">
                            <i class="fas fa-play-circle"></i>
                        </div>
                    </li>
                `);

                $("#result").append(resultItem);
            }

            // Highlight search terms in text
            function highlightText(text, query) {
                const regex = new RegExp(`(${query})`, 'gi');
                return text.replace(regex, '<span class="highlight">$1</span>');
            }

            // Handle search error
            function handleSearchError() {
                isSearching = false;
                $("#search-loading").hide();
                $("#result").hide();
                $("#no-results").html(`
                    <i class="fas fa-exclamation-triangle"></i>
                    <p>Đã xảy ra lỗi khi tìm kiếm</p>
                    <small>Vui lòng thử lại sau</small>
                `).show();
            }

            // Reset search state
            function resetSearchState() {
                $(".search-results").removeClass("show");
                isSearching = false;
                currentFocus = -1;
            }

            // Handle click on search result
            function handleResultClick() {
                const movieId = $(this).data('id');
                const movieTitle = $(this).find('.movie-title').text();

                // Add selected effect
                $(this).addClass('selected');

                // Set value
                $("#timkiem").val(movieTitle);

                // Show toast notification
                showToast(`Bạn đã chọn phim "${movieTitle}"`);

                setTimeout(() => {
                    $(".search-results").removeClass("show");
                    // In production, redirect to movie page
                    // window.location.href = `/phim/${movieId}`;
                }, 300);
            }

            // Clear search
            function clearSearch() {
                $("#timkiem").val('').focus();
                $(this).removeClass("visible");
                resetSearchState();
            }

            // Show toast notification
            function showToast(message) {
                const toast = $(`
                    <div class="toast">
                        <div class="toast-icon">
                            <i class="fas fa-film"></i>
                        </div>
                        <div class="toast-content">${message}</div>
                    </div>
                `);

                $(".toast-container").append(toast);

                // Remove toast after animation
                setTimeout(() => {
                    toast.remove();
                }, 3500);
            }

            // Log search for analytics
            function logSearch(term) {
                if (term.trim().length > 1) {
                    console.log("Search logged:", term);
                    // Here you would typically send this data to your analytics service
                }
            }

            // Initialize back to top button
            function initBackToTop() {
                const backToTopBtn = $("#back-to-top");

                $(window).scroll(function() {
                    if ($(this).scrollTop() > 300) {
                        backToTopBtn.addClass("visible");
                    } else {
                        backToTopBtn.removeClass("visible");
                    }
                });

                backToTopBtn.click(function() {
                    $("html, body").animate({scrollTop: 0}, 500);
                    return false;
                });
            }

            // Filter functionality
            window.locphim = function() {
                showToast("Tính năng lọc phim đang được cập nhật");
            };

            // Load top view movies by default
            $.ajax({
                url: "{{url('/filter-topview-default')}}",
                method: "GET",
                success: function(data) {
                    $('#show_data_default').html(data);
                }
            });

            // Filter sidebar click handler
            $('.filter-sidebar').click(function() {
                var href = $(this).attr('href');
                var value;

                // Determine value based on href
                if (href === '#ngay') {
                    value = 0;
                } else if (href === '#tuan') {
                    value = 1;
                } else {
                    value = 2;
                }

                // AJAX request
                $.ajax({
                    url: "{{url('/filter-topview-phim')}}",
                    method: "POST",
                    data: {value: value},
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    success: function(data) {
                        $('#halim-ajax-popular-post-default').css("display", "none");
                        $('#show_data').html(data);
                    }
                });
            });
        });
    </script>
    <script>
        $(document).ready(function() {
            // Fix cho dropdown menu - hỗ trợ cả hover và click
            $('.nav-item.dropdown').each(function() {
                // Xử lý cho thiết bị desktop (hover)
                $(this).on('mouseenter', function() {
                    $(this).find('.dropdown-menu').addClass('show');
                }).on('mouseleave', function() {
                    $(this).find('.dropdown-menu').removeClass('show');
                });

                // Xử lý cho thiết bị di động (click)
                $(this).find('.nav-link').on('click', function(e) {
                    // Chỉ ngăn mặc định nếu dropdown chưa mở
                    if (!$(this).siblings('.dropdown-menu').hasClass('show')) {
                        e.preventDefault();

                        // Đóng tất cả các dropdown khác
                        $('.dropdown-menu.show').removeClass('show');

                        // Mở dropdown này
                        $(this).siblings('.dropdown-menu').addClass('show');
                    }
                });
            });

            // Đóng dropdown khi click bên ngoài
            $(document).on('click', function(e) {
                if (!$(e.target).closest('.nav-item.dropdown').length) {
                    $('.dropdown-menu.show').removeClass('show');
                }
            });
        });
    </script>
</body>
</html>
