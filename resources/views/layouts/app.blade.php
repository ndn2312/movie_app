<!--
## Author: W3layouts
## Author URL: http://w3layouts.com
## License: Creative Commons Attribution 3.0 Unported
## License URL: http://creativecommons.org/licenses/by/3.0/
-->

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <title>Admin NDN Phim</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="keywords" content="admin" />
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <script type="application/x-javascript">
        addEventListener("load", function() { setTimeout(hideURLbar, 0); }, false);
        function hideURLbar() { window.scrollTo(0, 1); }
    </script>

    <!-- ===== CSS LIBRARIES ===== -->
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- DataTables CSS - Sử dụng một phiên bản duy nhất -->
    <link href="//cdn.datatables.net/2.3.0/css/dataTables.dataTables.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.4.1/css/responsive.dataTables.min.css">

    <!-- Bootstrap Core CSS -->
    <link href="{{asset('backend/css/bootstrap.css')}}" rel="stylesheet" type="text/css" />

    <!-- Custom CSS -->
    <link href="{{asset('backend/css/style.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('backend/css/custom.css')}}" rel="stylesheet" />

    <!-- Font-awesome icons CSS -->
    <link href="{{asset('backend/css/font-awesome.css')}}" rel="stylesheet" />

    <!-- Side nav css file -->
    <link href="{{asset('backend/css/SidebarNav.min.css')}}" media="all" rel="stylesheet" type="text/css" />

    <!-- Web fonts -->
    <link href="//fonts.googleapis.com/css?family=PT+Sans:400,400i,700,700i&amp;subset=cyrillic,cyrillic-ext,latin-ext"
        rel="stylesheet" />

    <!-- Owl Carousel CSS -->
    <link href="{{asset('backend/css/owl.carousel.css')}}" rel="stylesheet" />

    <!-- AmCharts Export CSS -->
    <link rel="stylesheet" href="{{asset('backend/css/export.css')}}" type="text/css" media="all" />

    <!-- ===== CUSTOM CSS ===== -->
    <style>
        #chartdiv {
            width: 100%;
            height: 295px;
        }

        /* Modal container và nội dung */
        .custom-modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 9999;
            justify-content: center;
            align-items: center;
        }

        .modal-content {
            background-color: white;
            padding: 30px;
            border-radius: 10px;
            text-align: center;
            max-width: 450px;
            width: 100%;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
            animation: fadeInDown 0.3s ease-out;
        }

        @keyframes fadeInDown {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Icon và tiêu đề */
        .success-icon {
            width: 70px;
            height: 70px;
            border-radius: 50%;
            background-color: #f5f5f5;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 15px;
        }

        .success-icon i {
            color: #ffc107;
            font-size: 40px;
        }

        .success-title {
            color: #ffc107;
            font-size: 24px;
            margin-bottom: 15px;
        }

        /* Phần đếm ngược */
        .countdown {
            font-size: 14px;
            color: #666;
            margin: 15px 0;
        }

        .countdown i {
            margin-right: 5px;
        }

        /* Nút OK */
        .ok-button {
            background-color: #ffc107;
            color: #000;
            border: none;
            padding: 8px 40px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            font-weight: bold;
            transition: background-color 0.2s ease;
        }

        .ok-button:hover {
            background-color: #e0aa00;
        }

        /* Các phần highlight */
        .highlight,
        .highlight-year,
        .highlight-name {
            padding: 2px 6px;
            font-weight: bold;
            border-radius: 4px;
        }

        .highlight {
            background-color: #ffc107;
            color: #000;
        }

        .highlight-year {
            background-color: #4CAF50;
            color: white;
        }

        .highlight-name {
            background-color: #2196F3;
            color: white;
        }

        /* Hiệu ứng nhấp nháy cho sắp xếp */
        @keyframes glowBlink {
            0% {
                background-color: white;
                box-shadow: 0 0 5px rgba(220, 53, 69, 0.2);
            }

            50% {
                background-color: rgba(220, 53, 69, 0.5);
                box-shadow: 0 0 15px rgba(220, 53, 69, 0.5);
            }

            100% {
                background-color: rgba(220, 53, 69, 0.2);
                box-shadow: 0 0 5px rgba(220, 53, 69, 0.2);
            }
        }

        .ui-state-highlight {
            border: 2px dashed #dc3545;
            border-radius: 8px;
            height: 50px;
            animation: glowBlink 0.8s infinite alternate;
            transition: background-color 0.3s ease-in-out;
        }

        /* Tùy chỉnh cho các widget thống kê */
        .r3_counter_box {
            transition: all 0.3s ease;
            margin-bottom: 20px;
        }

        .r3_counter_box:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 15px rgba(0, 0, 0, 0.2) !important;
        }

        .r3_counter_box .stats h5 {
            font-size: 22px;
            font-weight: 600 !important;
            margin-bottom: 8px;
            color: #444;
        }

        .r3_counter_box .stats span {
            font-size: 14px;
            color: #777;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .icon-rounded {
            width: 50px !important;
            height: 50px !important;
            line-height: 50px !important;
            border-radius: 50%;
            text-align: center;
            font-size: 22px !important;
            transition: all 0.3s ease;
        }

        .r3_counter_box:hover .icon-rounded {
            transform: rotate(360deg);
        }
    </style>
</head>

<body class="cbp-spmenu-push">
    @if(Auth::check())
    <div class="main-content">
        <div class="cbp-spmenu cbp-spmenu-vertical cbp-spmenu-left" id="cbp-spmenu-s1">
            <!--left-fixed -navigation-->
            <aside class="sidebar-left">
                <nav class="navbar navbar-inverse">
                    <div class="navbar-header">
                        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse"
                            data-target=".collapse" aria-expanded="false">
                            <span class="sr-only">Toggle navigation</span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                        </button>
                        <h1>
                            <a class="navbar-brand" href="{{url('/home')}}">
                                <span class="fa fa-area-chart"></span> Glance
                                <span class="dashboard_text">Design dashboard</span>
                            </a>
                        </h1>
                    </div>
                    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                        <ul class="sidebar-menu">
                            <li class="header">Quản lý</li>
                            <li class="treeview">
                                <a href="{{url('/home')}}">
                                    <i class="fa fa-dashboard"></i> <span>Dashboard</span>
                                </a>
                            </li>
                            <li class="treeview">
                                <a href="#">
                                    <i class="fa-solid fa-list"></i>
                                    <span>Danh mục phim</span>
                                    <i class="fa fa-angle-left pull-right"></i>
                                </a>
                                <ul class="treeview-menu">
                                    <li>
                                        <a href="{{route('category.create')}}"><i class="fa fa-angle-right"></i> Thêm
                                            danh mục</a>
                                    </li>
                                    <li>
                                        <a href="{{route('category.index')}}"><i class="fa fa-angle-right"></i> Liệt kê
                                            danh mục</a>
                                    </li>
                                </ul>
                            </li>
                            <li class="treeview">
                                <a href="#">
                                    <i class="fas fa-masks-theater"></i>
                                    <span>Thể loại phim</span>
                                    <i class="fa fa-angle-left pull-right"></i>
                                </a>
                                <ul class="treeview-menu">
                                    <li>
                                        <a href="{{route('genre.create')}}"><i class="fa fa-angle-right"></i> Thêm thể
                                            loại</a>
                                    </li>
                                    <li>
                                        <a href="{{route('genre.index')}}"><i class="fa fa-angle-right"></i> Liệt kê thể
                                            loại</a>
                                    </li>
                                </ul>
                            </li>
                            <li class="treeview">
                                <a href="#">
                                    <i class="fa-solid fa-globe"></i>
                                    <span>Quốc gia phim</span>
                                    <i class="fa fa-angle-left pull-right"></i>
                                </a>
                                <ul class="treeview-menu">
                                    <li>
                                        <a href="{{route('country.create')}}"><i class="fa fa-angle-right"></i> Thêm
                                            quốc gia</a>
                                    </li>
                                    <li>
                                        <a href="{{route('country.index')}}"><i class="fa fa-angle-right"></i> Liệt kê
                                            quốc gia</a>
                                    </li>
                                </ul>
                            </li>
                            <li class="treeview">
                                <a href="#">
                                    <i class="fa-solid fa-film"></i>
                                    <span>Phim</span>
                                    <i class="fa fa-angle-left pull-right"></i>
                                </a>
                                <ul class="treeview-menu">
                                    <li>
                                        <a href="{{route('movie.create')}}"><i class="fa fa-angle-right"></i> Thêm
                                            phim</a>
                                    </li>
                                    <li>
                                        <a href="{{route('movie.index')}}"><i class="fa fa-angle-right"></i> Liệt kê
                                            phim</a>
                                    </li>
                                    <li>
                                        <a href="{{route('leech-movie')}}"><i class="fa fa-angle-right"></i> API
                                            phim</a>
                                    </li>
                                </ul>
                            </li>
                            <li class="treeview">
                                <a href="#">
                                    <i class="fa-solid fa-circle-play"></i>
                                    <span>Quản lý tập phim</span>
                                    <i class="fa fa-angle-left pull-right"></i>
                                </a>
                                <ul class="treeview-menu">
                                    <li>
                                        <a href="{{route('episode.create')}}"><i class="fa fa-angle-right"></i> Thêm tập
                                            phim</a>
                                    </li>
                                    <li>
                                        <a href="{{route('episode.index')}}"><i class="fa fa-angle-right"></i> Liệt kê
                                            tập phim</a>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                    </div>
                    <!-- /.navbar-collapse -->
                </nav>
            </aside>
        </div>
        <!--left-fixed -navigation-->

        <!-- header-starts -->
        <div class="sticky-header header-section">
            <div class="header-left">
                <!--toggle button start-->
                <button id="showLeftPush"><i class="fa fa-bars"></i></button>
                <!--toggle button end-->
                <div class="profile_details_left">
                    <!--notifications of menu start -->
                    <ul class="nofitications-dropdown">
                        <li class="dropdown head-dpdn">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i
                                    class="fa fa-envelope"></i><span class="badge">4</span></a>
                            <ul class="dropdown-menu">
                                <li>
                                    <div class="notification_header">
                                        <h3>You have 3 new messages</h3>
                                    </div>
                                </li>
                                <!-- ... Menu items ... -->
                            </ul>
                        </li>
                        <!-- ... Other dropdown items ... -->
                    </ul>
                    <div class="clearfix"></div>
                </div>
                <!--notification menu end -->
                <div class="clearfix"></div>
            </div>
            <div class="header-right">
                <!--search-box-->
                <div class="search-box">
                    <form class="input">
                        <input class="sb-search-input input__field--madoka" placeholder="Search..." type="search"
                            id="input-31" />
                        <label class="input__label" for="input-31">
                            <svg class="graphic" width="100%" height="100%" viewBox="0 0 404 77"
                                preserveAspectRatio="none">
                                <path d="m0,0l404,0l0,77l-404,0l0,-77z" />
                            </svg>
                        </label>
                    </form>
                </div>
                <!--//end-search-box-->
                <div class="profile_details">
                    <ul>
                        <li class="dropdown profile_details_drop">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                                <div class="profile_img">
                                    <span class="prfil-img"><img src="{{asset('backend/images/2.jpg')}}" alt="" />
                                    </span>
                                    <div class="user-name">
                                        <p>{{ Auth::user()->name }}</p>
                                        <span>Administrator</span>
                                    </div>
                                    <i class="fa fa-angle-down lnr"></i>
                                    <i class="fa fa-angle-up lnr"></i>
                                    <div class="clearfix"></div>
                                </div>
                            </a>
                            <ul class="dropdown-menu drp-mnu">
                                <li>
                                    <a href="#"><i class="fa fa-cog"></i> Settings</a>
                                </li>
                                <li>
                                    <a href="#"><i class="fa fa-user"></i> My Account</a>
                                </li>
                                <li>
                                    <a href="{{ route('password.change') }}"><i class="fa fa-key"></i> Đổi mật khẩu</a>
                                </li>
                                <li>
                                    <a href="#"><i class="fa fa-suitcase"></i> Profile</a>
                                </li>
                                <li>
                                    <!-- <a href="#"><i class="fa fa-sign-out"></i> Logout</a> -->
                                    <form action="{{ route('logout') }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn btn-danger">
                                            <i class="fa fa-sign-out"></i> Logout
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </div>
                <div class="clearfix"></div>
            </div>
            <div class="clearfix"></div>
        </div>
        <!-- //header-ends -->

        <!-- main content start-->
        <div id="page-wrapper">
            <div class="main-page">
                <div class="dashboard-header"
                    style="margin-bottom: 20px; padding-bottom: 10px; border-bottom: 2px solid #f1f1f1;">
                    <h3 style="font-size: 24px; color: #333; font-weight: 600;"><i class="fa fa-dashboard"
                            style="margin-right: 10px; color: #3498db;"></i>Thống Kê Tổng Quan</h3>
                    <p style="color: #777; margin-top: 5px;">Thông tin tổng quan về dữ liệu hệ thống</p>
                </div>

                <div class="stats-container"
                    style="background-color: #fff; border-radius: 10px; padding: 20px; box-shadow: 0 0 15px rgba(0,0,0,0.05); margin-bottom: 30px;">
                    <div class="row" style="margin-bottom: 20px;">
                        <div class="col-md-3">
                            <div class="r3_counter_box"
                                style="background-color: #f8f9fa; border-radius: 10px; box-shadow: 0 4px 8px rgba(0,0,0,0.1); border-left: 5px solid #3498db;">
                                <i class="pull-left fa fa-film icon-rounded" style="background-color: #3498db;"></i>
                                <div class="stats">
                                    <h5><strong>{{ isset($statistics['total_movies']) ? $statistics['total_movies'] :
                                            '0' }}</strong></h5>
                                    <span>Tổng Số Phim</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="r3_counter_box"
                                style="background-color: #f8f9fa; border-radius: 10px; box-shadow: 0 4px 8px rgba(0,0,0,0.1); border-left: 5px solid #2ecc71;">
                                <i class="pull-left fa fa-list-alt icon-rounded" style="background-color: #2ecc71;"></i>
                                <div class="stats">
                                    <h5><strong>{{ isset($statistics['total_episodes']) ? $statistics['total_episodes']
                                            : '0' }}</strong></h5>
                                    <span>Tổng Số Tập Phim</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="r3_counter_box"
                                style="background-color: #f8f9fa; border-radius: 10px; box-shadow: 0 4px 8px rgba(0,0,0,0.1); border-left: 5px solid #e74c3c;">
                                <i class="pull-left fa fa-eye icon-rounded" style="background-color: #e74c3c;"></i>
                                <div class="stats">
                                    <h5><strong>{{ isset($statistics['total_views']) ?
                                            number_format($statistics['total_views']) : '0' }}</strong></h5>
                                    <span>Tổng Lượt Xem</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="r3_counter_box"
                                style="background-color: #f8f9fa; border-radius: 10px; box-shadow: 0 4px 8px rgba(0,0,0,0.1); border-left: 5px solid #9b59b6;">
                                <i class="pull-left fa fa-users icon-rounded" style="background-color: #9b59b6;"></i>
                                <div class="stats">
                                    <h5><strong>{{ isset($statistics['total_users']) ? $statistics['total_users'] : '0'
                                            }}</strong></h5>
                                    <span>Tổng Người Dùng</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="r3_counter_box"
                                style="background-color: #f8f9fa; border-radius: 10px; box-shadow: 0 4px 8px rgba(0,0,0,0.1); border-left: 5px solid #f39c12;">
                                <i class="pull-left fa fa-th-list icon-rounded" style="background-color: #f39c12;"></i>
                                <div class="stats">
                                    <h5><strong>{{ isset($statistics['total_categories']) ?
                                            $statistics['total_categories'] : '0' }}</strong></h5>
                                    <span>Tổng Danh Mục</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="r3_counter_box"
                                style="background-color: #f8f9fa; border-radius: 10px; box-shadow: 0 4px 8px rgba(0,0,0,0.1); border-left: 5px solid #1abc9c;">
                                <i class="pull-left fa fa-tags icon-rounded" style="background-color: #1abc9c;"></i>
                                <div class="stats">
                                    <h5><strong>{{ isset($statistics['total_genres']) ? $statistics['total_genres'] :
                                            '0' }}</strong></h5>
                                    <span>Tổng Thể Loại</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="r3_counter_box"
                                style="background-color: #f8f9fa; border-radius: 10px; box-shadow: 0 4px 8px rgba(0,0,0,0.1); border-left: 5px solid #34495e;">
                                <i class="pull-left fa fa-globe icon-rounded" style="background-color: #34495e;"></i>
                                <div class="stats">
                                    <h5><strong>{{ isset($statistics['total_countries']) ?
                                            $statistics['total_countries'] : '0' }}</strong></h5>
                                    <span>Tổng Quốc Gia</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row-one widgettable">
                    <!-- Widgets go here -->
                </div>

                <div class="col-md-12">
                    @yield('content')
                </div>
            </div>
            <div class="clearfix"></div>
        </div>
        <!-- Modal thông báo tùy chỉnh -->
        <div id="success-modal" class="custom-modal">
            <div class="modal-content">
                <div class="success-icon">
                    <i class="fas fa-check"></i>
                </div>
                <h3 class="success-title">Thành công!</h3>
                <p class="success-message"></p>
                <p class="countdown"><i class="fas fa-clock"></i> tự động đóng sau: <span id="countdown-timer">3</span>
                    giây
                </p>
                <button class="ok-button">OK</button>
            </div>
        </div>

        <!--footer-->
        <div>
            <div class="footer">
                <p>&copy; 2025 Admin NDN Phim. All Rights Reserved | Design by</p>
            </div>
        </div>
        <!--//footer-->
    </div>
    @else
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{ __('Login') }}</div>

                    <div class="card-body">
                        <form method="POST" action="{{ route('login') }}">
                            @csrf

                            <div class="row mb-3">
                                <label for="email" class="col-md-4 col-form-label text-md-end">{{ __('Email Address')
                                    }}</label>

                                <div class="col-md-6">
                                    <input id="email" type="email"
                                        class="form-control @error('email') is-invalid @enderror" name="email"
                                        value="{{ old('email') }}" required autocomplete="email" autofocus>

                                    @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="password" class="col-md-4 col-form-label text-md-end">{{ __('Password')
                                    }}</label>

                                <div class="col-md-6">
                                    <input id="password" type="password"
                                        class="form-control @error('password') is-invalid @enderror" name="password"
                                        required autocomplete="current-password">

                                    @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6 offset-md-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="remember" id="remember" {{
                                            old('remember') ? 'checked' : '' }}>

                                        <label class="form-check-label" for="remember">
                                            {{ __('Remember Me') }}
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="row mb-0">
                                <div class="col-md-8 offset-md-4">
                                    <button type="submit" class="btn btn-primary">
                                        {{ __('Login') }}
                                    </button>

                                    @if (Route::has('password.request'))
                                    <a class="btn btn-link" href="{{ route('password.request') }}">
                                        {{ __('Forgot Your Password?') }}
                                    </a>
                                    @endif
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif


    <!-- ===== CORE JAVASCRIPT LIBRARIES ===== -->
    <!-- jQuery (chỉ sử dụng một phiên bản) -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

    <!-- jQuery UI -->
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="{{asset('backend/js/bootstrap.js')}}"></script>

    <!-- DataTables (chỉ sử dụng một phiên bản) -->
    <script src="//cdn.datatables.net/2.3.0/js/dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.4.1/js/dataTables.responsive.min.js"></script>

    <!-- Modernizr -->
    <script src="{{asset('backend/js/modernizr.custom.js')}}"></script>

    <!-- Chart JS -->
    <script src="{{asset('backend/js/Chart.js')}}"></script>
    <script src="{{asset('backend/js/Chart.bundle.js')}}"></script>
    <script src="{{asset('backend/js/utils.js')}}"></script>

    <!-- AmCharts -->
    <script src="{{asset('backend/js/amcharts.js')}}"></script>
    <script src="{{asset('backend/js/serial.js')}}"></script>
    <script src="{{asset('backend/js/export.min.js')}}"></script>
    <script src="{{asset('backend/js/light.js')}}"></script>
    <script src="{{asset('backend/js/index1.js')}}"></script>

    <!-- Metis Menu -->
    <script src="{{asset('backend/js/metisMenu.min.js')}}"></script>
    <script src="{{asset('backend/js/custom.js')}}"></script>

    <!-- Pie Chart -->
    <script src="{{asset('backend/js/pie-chart.js')}}" type="text/javascript"></script>

    <!-- Owl Carousel -->
    <script src="{{asset('backend/js/owl.carousel.js')}}"></script>

    <!-- Classie for toggle left push menu -->
    <script src="{{asset('backend/js/classie.js')}}"></script>

    <!-- Scrolling JS -->
    {{-- <script src="{{asset('backend/js/jquery.nicescroll.js')}}"></script> --}}
    {{-- <script src="{{asset('backend/js/scripts.js')}}"></script> --}}

    <!-- Side Nav JS -->
    <script src="{{asset('backend/js/SidebarNav.min.js')}}" type="text/javascript"></script>

    <!-- SimpleChart -->
    <script src="{{asset('backend/js/SimpleChart.js')}}"></script>

    <!-- ===== UNIFIED CUSTOM SCRIPTS ===== -->
    <script type="text/javascript">
        // Biến toàn cục cho bộ đếm thời gian
        let modalCountdownTimer;
        
        $(document).ready(function() {
            // Khởi tạo DataTables với tùy chọn ngôn ngữ tiếng Việt
            // $('#tablephim').DataTable({
            //     "scrollX": true,
                
            //     "responsive": true,
            //     "language": {
            //         // Sử dụng đường dẫn phù hợp với phiên bản 2.3.0
            //         "url": "//cdn.datatables.net/plug-ins/2.0.0/i18n/vi.json"
            //     }
                
            // });

            
            // Khởi tạo Owl Carousel
            $('#owl-demo').owlCarousel({
                items: 3,
                lazyLoad: true,
                autoPlay: true,
                pagination: true,
                nav: true,
            });
            
            // Khởi tạo Pie Charts
            $('#demo-pie-1').pieChart({
                barColor: '#2dde98',
                trackColor: '#eee',
                lineCap: 'round',
                lineWidth: 8,
                onStep: function(from, to, percent) {
                    $(this.element)
                        .find('.pie-value')
                        .text(Math.round(percent) + '%');
                },
            });
            $('#demo-pie-2').pieChart({
                barColor: '#8e43e7',
                trackColor: '#eee',
                lineCap: 'butt',
                lineWidth: 8,
                onStep: function(from, to, percent) {
                    $(this.element)
                        .find('.pie-value')
                        .text(Math.round(percent) + '%');
                },
            });
            $('#demo-pie-3').pieChart({
                barColor: '#ffc168',
                trackColor: '#eee',
                lineCap: 'square',
                lineWidth: 8,
                onStep: function(from, to, percent) {
                    $(this.element)
                        .find('.pie-value')
                        .text(Math.round(percent) + '%');
                },
            });
            
            // Khôi phục trạng thái của các treeview từ localStorage
            $('.treeview').each(function() {
                var treeviewId = $(this).index();
                if (localStorage.getItem('treeview_' + treeviewId) === 'active') {
                    $(this).addClass('active');
                    $(this).find('.treeview-menu').css('display', 'block');
                }
            });
            
            // Lưu trạng thái khi click vào treeview
            $('.treeview').click(function() {
                var treeviewId = $(this).index();
                // Xóa trạng thái tất cả treeview trước
                for (var i = 0; i < $('.treeview').length; i++) {
                    localStorage.removeItem('treeview_' + i);
                }
                // Lưu trạng thái cho treeview hiện tại
                localStorage.setItem('treeview_' + treeviewId, 'active');
            });
            
            // Khởi tạo SidebarNav
            $('.sidebar-menu').SidebarNav();
            
            // Xử lý chức năng sắp xếp
            var originalOrder = [];
            $(".order_position").sortable({
                placeholder: "ui-state-highlight",
                start: function() {
                    originalOrder = $(".order_position tr").map(function() {
                        return $(this).attr("id");
                    }).get();
                },
                update: function() {
                    let newOrder = [];
                    $(".order_position tr").each(function() {
                        newOrder.push($(this).attr("id"));
                    });
                    
                    // Kiểm tra thay đổi
                    if (JSON.stringify(originalOrder) === JSON.stringify(newOrder)) {
                        console.log("Không có thay đổi, không gửi AJAX");
                        return;
                    }
                    
                    $.ajax({
                        headers: {
                            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
                        },
                        url: "{{ route('resorting') }}",
                        method: "POST",
                        data: {
                            array_id: newOrder
                        },
                        success: function() {
                            showSuccessMessage();
                        },
                        error: function(xhr) {
                            console.error("Lỗi sắp xếp:", xhr);
                            showErrorMessage();
                        }
                    });
                }
            });
            
            // ===== AJAX XỬ LÝ CHO CÁC DROPDOWN =====
            
            // ----- Xử lý cập nhật danh mục phim -----
            $(document).on('change', '.category_choose', function() {
                var category_id = $(this).val();
                var movie_id = $(this).attr('id');
                var ten_phim = $(this).attr('title');
                var category_text = $(this).find(':selected').text();
                
                $.ajax({
                    url: "{{route('category-choose')}}",
                    method: "GET",
                    data: {
                        category_id: category_id,
                        movie_id: movie_id
                    },
                    success: function(data) {
                        const message = 'Phim "<span class="highlight-name">' + ten_phim +
                            '</span>" đã được <span class="highlight">CẬP NHẬT</span> thành danh mục ' +
                            '<span class="highlight-year">' + category_text + '</span> thành công!';
                        showCustomModal(message);
                    },
                    error: function(xhr) {
                        console.error("Lỗi khi cập nhật danh mục:", xhr.responseText);
                        showErrorAlert("Có lỗi xảy ra khi cập nhật danh mục phim!");
                    }
                });
            });
            
            // ----- Xử lý cập nhật quốc gia phim -----
            $(document).on('change', '.country_choose', function() {
                var country_id = $(this).val();
                var movie_id = $(this).attr('id');
                var ten_phim = $(this).attr('title');
                var country_text = $(this).find(':selected').text();
                
                $.ajax({
                    url: "{{route('country-choose')}}",
                    method: "GET",
                    data: {
                        country_id: country_id,
                        movie_id: movie_id
                    },
                    success: function(data) {
                        const message = 'Phim "<span class="highlight-name">' + ten_phim +
                            '</span>" đã được <span class="highlight">CẬP NHẬT</span> thành quốc gia ' +
                            '<span class="highlight-year">' + country_text + '</span> thành công!';
                        showCustomModal(message);
                    },
                    error: function(xhr) {
                        console.error("Lỗi khi cập nhật quốc gia:", xhr.responseText);
                        showErrorAlert("Có lỗi xảy ra khi cập nhật quốc gia phim!");
                    }
                });
            });
            
            // ----- Xử lý cập nhật phim hot -----
            $(document).on('change', '.phimhot_choose', function() {
                var phimhot_val = $(this).val();
                var movie_id = $(this).attr('id');
                var ten_phim = $(this).attr('title');
                var phimhot_text = (phimhot_val == 1) ? 'Hot' : 'Không Hot';
                
                $.ajax({
                    url: "{{route('phimhot-choose')}}",
                    method: "GET",
                    data: {
                        phimhot_val: phimhot_val,
                        movie_id: movie_id
                    },
                    success: function(data) {
                        const message = 'Phim "<span class="highlight-name">' + ten_phim +
                            '</span>" đã được <span class="highlight">CẬP NHẬT</span> thành trạng thái ' +
                            '<span class="highlight-year">' + phimhot_text + '</span> thành công!';
                        showCustomModal(message);
                    },
                    error: function(xhr) {
                        console.error("Lỗi khi cập nhật phim hot:", xhr.responseText);
                        showErrorAlert("Có lỗi xảy ra khi cập nhật trạng thái phim hot!");
                    }
                });
            });
            
            // ----- Xử lý cập nhật tm,pd phim -----
            $(document).on('change', '.phude_choose', function() {
                var phude_val = $(this).val();
                var movie_id = $(this).attr('id');
                var ten_phim = $(this).attr('title');
                var phude_text = (phude_val == 1) ? 'Thuyết minh' : 'Phụ đề';
                
                $.ajax({
                    url: "{{route('phude-choose')}}",
                    method: "GET",
                    data: {
                        phude_val: phude_val,
                        movie_id: movie_id
                    },
                    success: function(data) {
                        const message = 'Phim "<span class="highlight-name">' + ten_phim +
                            '</span>" đã được <span class="highlight">CẬP NHẬT</span> thành phiên bản ' +
                            '<span class="highlight-year">' + phude_text + '</span> thành công!';
                        showCustomModal(message);
                    },
                    error: function(xhr) {
                        console.error("Lỗi khi cập nhật phụ đề:", xhr.responseText);
                        showErrorAlert("Có lỗi xảy ra khi cập nhật phụ đề phim!");
                    }
                });
            });
            
            // ----- Xử lý cập nhật hiển thị phim -----
            $(document).on('change', '.status_choose', function() {
                var status_val = $(this).val();
                var movie_id = $(this).attr('id');
                var ten_phim = $(this).attr('title');
                var status_text = (status_val == 1) ? 'Hiển thị' : 'Ẩn';
                
                $.ajax({
                    url: "{{route('status-choose')}}",
                    method: "GET",
                    data: {
                        status_val: status_val,
                        movie_id: movie_id
                    },
                    success: function(data) {
                        const message = 'Phim "<span class="highlight-name">' + ten_phim +
                            '</span>" đã được <span class="highlight">CẬP NHẬT</span> thành trạng thái ' +
                            '<span class="highlight-year">' + status_text + '</span> thành công!';
                        showCustomModal(message);
                    },
                    error: function(xhr) {
                        console.error("Lỗi khi cập nhật trạng thái hiển thị:", xhr.responseText);
                        showErrorAlert("Có lỗi xảy ra khi cập nhật trạng thái hiển thị phim!");
                    }
                });
            });
            
            // ----- Xử lý cập nhật độ phân giải phim -----
            $(document).on('change', '.resolution_choose', function() {
                var resolution_val = $(this).val();
                var movie_id = $(this).attr('id');
                var ten_phim = $(this).attr('title');
                var resolution_text;
                
                if(resolution_val == 0) {
                    resolution_text = 'HD';
                } else if(resolution_val == 1) {
                    resolution_text = 'SD';
                } else if(resolution_val == 2) {
                    resolution_text = 'HDCam';
                } else if(resolution_val == 3) {
                    resolution_text = 'Cam';
                } else if(resolution_val == 4) {
                    resolution_text = 'FullHD';
                } else {
                    resolution_text = 'Trailer';
                }
                
                $.ajax({
                    url: "{{route('resolution-choose')}}",
                    method: "GET",
                    data: {
                        resolution_val: resolution_val,
                        movie_id: movie_id
                    },
                    success: function(data) {
                        const message = 'Phim "<span class="highlight-name">' + ten_phim +
                            '</span>" đã được <span class="highlight">CẬP NHẬT</span> thành độ phân giải ' +
                            '<span class="highlight-year">' + resolution_text + '</span> thành công!';
                        showCustomModal(message);
                    },
                    error: function(xhr) {
                        console.error("Lỗi khi cập nhật độ phân giải:", xhr.responseText);
                        showErrorAlert("Có lỗi xảy ra khi cập nhật độ phân giải phim!");
                    }
                });
            });
            
            // ----- Xử lý cập nhật thuộc phim -----
            $(document).on('change', '.thuocphim_choose', function() {
                var thuocphim_val = $(this).val();
                var movie_id = $(this).attr('id');
                var ten_phim = $(this).attr('title');
                var thuocphim_text = (thuocphim_val == 1) ? 'Phim lẻ' : 'Phim bộ';
                
                $.ajax({
                    url: "{{route('thuocphim-choose')}}",
                    method: "GET",
                    data: {
                        thuocphim_val: thuocphim_val,
                        movie_id: movie_id
                    },
                    success: function(data) {
                        const message = 'Phim "<span class="highlight-name">' + ten_phim +
                            '</span>" đã được <span class="highlight">CẬP NHẬT</span> thành thuộc phim ' +
                            '<span class="highlight-year">' + thuocphim_text + '</span> thành công!';
                        showCustomModal(message);
                    },
                    error: function(xhr) {
                        console.error("Lỗi khi cập nhật thuộc phim:", xhr.responseText);
                        showErrorAlert("Có lỗi xảy ra khi cập nhật thuộc phim!");
                    }
                });
            });
            
            // ----- Xử lý cập nhật năm phim -----
            $(document).on('change', '.select-year', function() {
                var year = $(this).val();
                var id_phim = $(this).attr('id');
                var ten_phim = $(this).attr('title');
                
                $.ajax({
                    url: "{{url('/update-year-phim')}}",
                    method: "GET",
                    data: {
                        year: year,
                        id_phim: id_phim,
                        ten_phim: ten_phim
                    },
                    success: function(data) {
                        const message = 'Phim "<span class="highlight-name">' + ten_phim +
                            '</span>" đã được <span class="highlight">CẬP NHẬT</span> thành năm ' +
                            '<span class="highlight-year">' + year + '</span> thành công!';
                        showCustomModal(message);
                    },
                    error: function(xhr) {
                        console.error("Lỗi khi cập nhật năm phim:", xhr.responseText);
                        showErrorAlert("Có lỗi xảy ra khi cập nhật năm phim!");
                    }
                });
            });
            
            // ----- Xử lý cập nhật topview phim -----
            $(document).on('change', '.select-topview', function() {
                var topview = $(this).val();
                var id_phim = $(this).attr('id');
                var ten_phim = $(this).attr('title');
                var textTopview;
                
                if(topview == 0) {
                    textTopview = 'Ngày';
                } else if(topview == 1) {
                    textTopview = 'Tuần';
                } else if(topview == 2) {
                    textTopview = 'Tháng';
                }
                
                $.ajax({
                    url: "{{url('/update-topview-phim')}}",
                    method: "GET",
                    data: {
                        topview: topview,
                        id_phim: id_phim,
                        ten_phim: ten_phim
                    },
                    success: function(data) {
                        const message = 'Phim "<span class="highlight-name">' + ten_phim +
                            '</span>" đã được <span class="highlight">CẬP NHẬT</span> theo topview ' +
                            '<span class="highlight-year">' + textTopview + '</span> thành công!';
                        showCustomModal(message);
                    },
                    error: function(xhr) {
                        console.error("Lỗi khi cập nhật topview:", xhr.responseText);
                        showErrorAlert("Có lỗi xảy ra khi cập nhật topview phim!");
                    }
                });
            });
            
            // ----- Xử lý cập nhật season phim -----
            $(document).on('change', '.select-season', function() {
                var season = $(this).val();
                var id_phim = $(this).attr('id');
                var ten_phim = $(this).attr('title');
                
                $.ajax({
                    url: "{{url('/update-season-phim')}}",
                    method: "POST",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        season: season,
                        id_phim: id_phim,
                        ten_phim: ten_phim
                    },
                    success: function(data) {
                        const message = 'Phim "<span class="highlight-name">' + ten_phim +
                            '</span>" đã được <span class="highlight">CẬP NHẬT</span> thành season ' +
                            '<span class="highlight-year">' + season + '</span> thành công!';
                        showCustomModal(message);
                    },
                    error: function(xhr) {
                        console.error("Lỗi khi cập nhật season:", xhr.responseText);
                        showErrorAlert("Có lỗi xảy ra khi cập nhật season phim!");
                    }
                });
            });
            
            // ----- Xử lý cập nhật hình ảnh phim -----
            $(document).on('change', '.file_image', function() {
                var movie_id = $(this).data('movie_id');
                var files = $("#file-"+movie_id)[0].files;
                var ten_phim = $(this).data('movie_title');// Lấy tên phim
                
                if(files.length > 0) {
                    var form_data = new FormData();
                    form_data.append('file',document.getElementById('file-'+movie_id).files[0]);
                    form_data.append('movie_id',movie_id);
                    
                    // Hiển thị trạng thái đang xử lý
                    var loadingElement = $('<span class="text-info ms-2"><i class="fas fa-spinner fa-spin"></i> Đang tải...</span>');
                    $(this).after(loadingElement);
                    
                    $.ajax({
                        url: "{{route('update-image-movie-ajax')}}",
                        method: "POST",
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        data: form_data,
                        contentType: false,
                        cache: false,
                        processData: false,
                        success: function(){
                            // Xóa thông báo đang tải
                            loadingElement.remove();
                            
                            // Hiển thị modal thông báo thành công
                            const message = 'Hình ảnh của phim "<span class="highlight-name">' + ten_phim +
                                '</span>" đã được <span class="highlight">CẬP NHẬT</span> thành công!';
                            showCustomModal(message);
                            
                            // Tải lại trang sau khi đóng modal
                            setTimeout(function() {
                                location.reload();
                            }, 3500);
                        },
                        error: function(xhr, status, error) {
                            // Xóa thông báo đang tải
                            loadingElement.remove();
                            
                            // Hiển thị thông báo lỗi
                            showErrorAlert("Có lỗi xảy ra khi cập nhật hình ảnh phim: " + error);
                        }
                    });
                }
            });
            
            // ----- Xử lý chọn phim cho tập phim -----
            $('.select-movie').change(function(){
                var id = $(this).val();
                $.ajax({
                    url: "{{route('select-movie')}}",
                    method: "GET",
                    data: {id:id},
                    success: function(data){
                        $('#episode').html(data);
                    }
                });
            });
            
            // ----- Sự kiện click nút OK cho modal -----
            $(document).on('click', '.ok-button', function() {
                closeCustomModal();
            });
            
            // Thêm CSS cho hiệu ứng
            addCustomEffects();
        });
        
        // ===== MENU TOGGLE =====
        var menuLeft = document.getElementById('cbp-spmenu-s1'),
            showLeftPush = document.getElementById('showLeftPush'),
            body = document.body;
            
        showLeftPush.onclick = function() {
            classie.toggle(this, 'active');
            classie.toggle(body, 'cbp-spmenu-push-toright');
            classie.toggle(menuLeft, 'cbp-spmenu-open');
            disableOther('showLeftPush');
        };
        
        function disableOther(button) {
            if (button !== 'showLeftPush') {
                classie.toggle(showLeftPush, 'disabled');
            }
        }
        
        // ===== CÁC HÀM XỬ LÝ MODAL =====
        
        // Hàm hiển thị modal tùy chỉnh
        function showCustomModal(messageHtml, seconds = 3) {
            // Thiết lập nội dung thông báo
            $('.success-message').html(messageHtml);
            
            // Dừng bộ đếm cũ nếu có
            if (modalCountdownTimer) {
                clearInterval(modalCountdownTimer);
            }
            
            // Hiển thị modal
            $('#success-modal').css('display', 'flex');
            
            // Thiết lập bộ đếm
            $('#countdown-timer').text(seconds);
            
            // Bắt đầu đếm ngược mới
            modalCountdownTimer = setInterval(function() {
                seconds--;
                $('#countdown-timer').text(seconds);
                
                if (seconds <= 0) {
                    clearInterval(modalCountdownTimer);
                    closeCustomModal();
                }
            }, 1000);
        }
        
        // Hàm đóng modal
        function closeCustomModal() {
            $('#success-modal').css('display', 'none');
            if (modalCountdownTimer) {
                clearInterval(modalCountdownTimer);
            }
        }
        
        // Hàm hiển thị thông báo lỗi với SweetAlert
        function showErrorAlert(message) {
            Swal.fire({
                title: "Lỗi!",
                text: message,
                icon: "error",
                confirmButtonText: "Đóng",
                timer: 3000,
                timerProgressBar: true
            });
        }
        
        // Thông báo thành công khi sắp xếp - sử dụng SweetAlert
        function showSuccessMessage() {
            let timeLeft = 3;
            let timerInterval;
            
            Swal.fire({
                title: "✅ Thành công!",
                html: `Sắp xếp thứ tự đã được cập nhật.<br><b>Tự động đóng sau <span id="countdown">${timeLeft}</span> giây</b>`,
                icon: "success",
                timer: timeLeft * 1000,
                timerProgressBar: true,
                showConfirmButton: true,
                didOpen: () => {
                    const countdownEl = document.getElementById("countdown");
                    timerInterval = setInterval(() => {
                        timeLeft--;
                        countdownEl.textContent = timeLeft;
                        if (timeLeft <= 0) {
                            clearInterval(timerInterval);
                        }
                    }, 1000);
                },
                willClose: () => {
                    clearInterval(timerInterval);
                }
            });
        }
        
        // Thông báo lỗi khi sắp xếp - sử dụng SweetAlert
        function showErrorMessage() {
            Swal.fire({
                title: "❌ Lỗi!",
                text: "Đã có lỗi xảy ra, vui lòng thử lại.",
                icon: "error",
                timer: 2500,
                timerProgressBar: true,
                showConfirmButton: true
            });
        }
        
        // Thêm hiệu ứng CSS
        function addCustomEffects() {
            const css = `
                .custom-modal.preparing {
                    opacity: 0;
                }
                .custom-modal {
                    transition: opacity 0.3s ease;
                }
                .ok-button {
                    position: relative;
                    overflow: hidden;
                }
                .ok-button:after {
                    content: '';
                    position: absolute;
                    top: 50%;
                    left: 50%;
                    width: 5px;
                    height: 5px;
                    background: rgba(255, 255, 255, 0.5);
                    opacity: 0;
                    border-radius: 100%;
                    transform: scale(1, 1) translate(-50%);
                    transform-origin: 50% 50%;
                }
                .ok-button:focus:not(:active)::after {
                    animation: ripple 1s ease-out;
                }
                @keyframes ripple {
                    0% {
                        transform: scale(0, 0);
                        opacity: 0.5;
                    }
                    20% {
                        transform: scale(25, 25);
                        opacity: 0.5;
                    }
                    100% {
                        opacity: 0;
                        transform: scale(40, 40);
                    }
                }`;
                
            const style = document.createElement('style');
            style.textContent = css;
            document.head.appendChild(style);
        }
        
        // ===== HÀM XỬ LÝ TẠO SLUG =====
        function ChangeToSlug() {
            var slug;
            
            // Lấy text từ thẻ input title
            title = document.getElementById("slug").value;
            
            // Đổi chữ hoa thành chữ thường
            slug = title.toLowerCase();
            
            // Đổi ký tự có dấu thành không dấu
            slug = slug.replace(/á|à|ả|ạ|ã|ă|ắ|ằ|ẳ|ẵ|ặ|â|ấ|ầ|ẩ|ẫ|ậ/gi, 'a');
            slug = slug.replace(/é|è|ẻ|ẽ|ẹ|ê|ế|ề|ể|ễ|ệ/gi, 'e');
            slug = slug.replace(/i|í|ì|ỉ|ĩ|ị/gi, 'i');
            slug = slug.replace(/ó|ò|ỏ|õ|ọ|ô|ố|ồ|ổ|ỗ|ộ|ơ|ớ|ờ|ở|ỡ|ợ/gi, 'o');
            slug = slug.replace(/ú|ù|ủ|ũ|ụ|ư|ứ|ừ|ử|ữ|ự/gi, 'u');
            slug = slug.replace(/ý|ỳ|ỷ|ỹ|ỵ/gi, 'y');
            slug = slug.replace(/đ/gi, 'd');
            
            // Xóa các ký tự đặt biệt
            slug = slug.replace(/\`|\~|\!|\@|\#|\||\$|\%|\^|\&|\*|\(|\)|\+|\=|\,|\.|\/|\?|\>|\<|\'|\"|\:|\;|_/gi, '');
            
            // Đổi khoảng trắng thành ký tự gạch ngang
            slug = slug.replace(/ /gi, "-");
            
            // Đổi nhiều ký tự gạch ngang liên tiếp thành 1 ký tự gạch ngang
            slug = slug.replace(/\-\-\-\-\-/gi, '-');
            slug = slug.replace(/\-\-\-\-/gi, '-');
            slug = slug.replace(/\-\-\-/gi, '-');
            slug = slug.replace(/\-\-/gi, '-');
            
            // Xóa các ký tự gạch ngang ở đầu và cuối
            slug = '@' + slug + '@';
            slug = slug.replace(/\@\-|\-\@|\@/gi, '');
            
            // In slug ra textbox có id "slug"
            document.getElementById('convert_slug').value = slug;
        }
    </script>

</body>

</html>