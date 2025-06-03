{{-- <!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width,initial-scale=1.0, maximum-scale=1.0, user-scalable=no" name="viewport">
    <meta name="theme-color" content="#0f1824">
    <meta http-equiv="Content-Language" content="vi">
    <meta content="VN" name="geo.region">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="shortcut icon" href="/favicon.png" type="image/png">

    <title>NDN Phim - Đăng nhập</title>

    <!-- CSS Files -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <link rel='stylesheet' href='{{asset(' css/bootstrap.min.css')}}'>
    <link rel='stylesheet' href='{{asset(' css/style.css')}}'>

    <!-- Google Fonts -->
    <link
        href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&family=Be+Vietnam+Pro:wght@400;500;600;700&display=swap"
        rel="stylesheet">

    <script src='{{asset(' js/jquery.min.js')}}'></script>

    <style>
        body {
            margin: 0;
            padding: 0;
            height: 100vh;
            background-color: #1d2544;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            position: relative;
        }

        body::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-image: url('/imgs/movie-posters-bg.jpg');
            background-size: cover;
            background-position: center;
            opacity: 0.1;
            z-index: -1;
        }

        .login-container {
            display: flex;
            width: 100%;
            max-width: 900px;
            height: 600px;
            background-color: #1d2544;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.5);
            position: relative;
        }

        .login-left {
            width: 50%;
            padding: 40px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            position: relative;
        }

        .login-right {
            width: 50%;
            padding: 40px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .site-logo {
            display: flex;
            flex-direction: column;
            align-items: center;
            margin-bottom: 30px;
        }

        .site-logo img {
            width: 120px;
            height: 120px;
            margin-bottom: 15px;
        }

        .logo-text {
            font-size: 2.5em;
            font-weight: 700;
            color: #fff;
            margin: 0;
        }

        .logo-text span {
            color: #ffc107;
        }

        .login-tagline {
            font-size: 1.2em;
            color: rgba(255, 255, 255, 0.6);
            text-align: center;
        }

        .login-title {
            font-size: 2em;
            color: #fff;
            margin-bottom: 20px;
        }

        .login-subtitle {
            color: rgba(255, 255, 255, 0.6);
            margin-bottom: 30px;
        }

        .form-group {
            margin-bottom: 20px;
        }

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

        .close-btn {
            position: absolute;
            top: 20px;
            right: 20px;
            color: rgba(255, 255, 255, 0.6);
            font-size: 20px;
            cursor: pointer;
            transition: all 0.3s ease;
            background: none;
            border: none;
            z-index: 100;
        }

        .close-btn:hover {
            color: #fff;
        }

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

        @media (max-width: 768px) {
            .login-container {
                flex-direction: column;
                height: auto;
                max-width: 400px;
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
    </style>
</head>

<body>
    <div class="login-container">
        <a href="/" class="close-btn">
            <i class="fas fa-times"></i>
        </a>

        <div class="login-left">
            <div class="site-logo">
                <img src="/imgs/logo.png" alt="NDN Phim" class="logo-image">
                <h1 class="logo-text">Ndn<span>Phim</span></h1>
            </div>
            <p class="login-tagline">Phim hay cả rổ</p>
        </div>

        <div class="login-right">
            <h2 class="login-title">Đăng nhập</h2>
            <p class="login-subtitle">Nếu bạn chưa có tài khoản, <a href="{{ route('register.post') }}"
                    class="register-link">đăng ký ngay</a></p>

            <div id="login-message" class="form-message"></div>

            <form method="POST" action="{{ route('login.post') }}" id="loginForm">
                @csrf
                <div class="form-group">
                    <input type="text" class="form-control" id="login" name="login"
                        placeholder="Email hoặc Tên đăng nhập" required>
                </div>
                <div class="form-group">
                    <input type="password" class="form-control" id="password" name="password" placeholder="Mật khẩu"
                        required>
                </div>
                <button type="submit" class="btn btn-login btn-block">Đăng nhập</button>
                </form>

            <div class="forgot-password">
                <a href="#">Quên mật khẩu?</a>
            </div>
        </div>
    </div>

    <script src="{{asset('js/bootstrap.min.js')}}"></script>
    <script>
        $(document).ready(function() {
            // Xử lý form submit
            $('#loginForm').on('submit', function(e) {
                e.preventDefault();
                
                // Xóa các thông báo cũ
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
                        $('.btn-login').html('<i class="fas fa-spinner fa-spin"></i> Đang xử lý...').attr('disabled', true);
                    },
                    success: function(response) {
                        if (response.success === false) {
                            // Hiển thị lỗi
                            $('#login-message').addClass('error').html(response.message).slideDown();
                            $('.btn-login').html('Đăng nhập').attr('disabled', false);
                        } else {
                            // Thành công
                            $('#login-message').addClass('success').html('Đăng nhập thành công! Đang chuyển hướng...').slideDown();
                            
                            // Chuyển hướng
                            setTimeout(function() {
                                if (response.redirect) {
                                    window.location.href = response.redirect;
                                } else {
                                    window.location.href = '/';
                                }
                            }, 1500);
                        }
                    },
                    error: function(xhr) {
                        $('.btn-login').html('Đăng nhập').attr('disabled', false);
                        
                        if (xhr.status === 422) {
                            // Lỗi validation
                            var errors = xhr.responseJSON.errors;
                            var errorMessage = '';
                            
                            $.each(errors, function(key, value) {
                                errorMessage += value[0] + '<br>';
                            });
                            
                            $('#login-message').addClass('error').html(errorMessage).slideDown();
                        } else if (xhr.status === 401) {
                            // Thông báo đăng nhập sai
                            $('#login-message').addClass('error').html(
                                xhr.responseJSON && xhr.responseJSON.message ? 
                                xhr.responseJSON.message : 'Tên đăng nhập/email hoặc mật khẩu không chính xác'
                            ).slideDown();
            } else {
                            // Lỗi khác
                            $('#login-message').addClass('error').html('Có lỗi xảy ra, vui lòng thử lại sau.').slideDown();
            }
        }
                });
            });
        });
    </script>
</body>

</html> --}}