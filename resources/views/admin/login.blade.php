<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>NDN Phim - Đăng nhập quản trị</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Bootstrap -->
    <link href="{{asset('backend/css/bootstrap.css')}}" rel="stylesheet" type="text/css" />

    <!-- Custom CSS -->
    <link href="{{asset('backend/css/login.css')}}" rel="stylesheet" type="text/css" />
</head>

<body>
    <div class="login-container">
        <div class="login-card">
            <div class="login-header">
                <div class="brand-icon">
                    <i class="fas fa-film"></i>
                </div>
                <h1>NDN Phim Admin</h1>
                <p>Đăng nhập hệ thống quản trị</p>
                <div class="alert alert-info">
                    <i class="fas fa-info-circle"></i> Đây là trang đăng nhập dành riêng cho quản trị viên.
                </div>

                @if(session('info'))
                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-triangle"></i> {{ session('info') }}
                </div>
                @endif
            </div>

            <div class="login-body">
                @if(session('status'))
                <div class="alert alert-success">
                    {{ session('status') }}
                </div>
                @endif

                @if(session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
                @endif

                <form method="POST" action="{{ route('admin.login.post') }}">
                    @csrf

                    <div class="mb-3">
                        <label for="login" class="form-label">Email hoặc Tên đăng nhập</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-user"></i></span>
                            <input id="login" type="text" class="form-control @error('login') is-invalid @enderror"
                                name="login" value="{{ old('login') }}" required autocomplete="login" autofocus
                                placeholder="Nhập email hoặc tên đăng nhập">
                        </div>
                        @error('login')
                        <div class="error-feedback">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label">Mật khẩu</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-lock"></i></span>
                            <input id="password" type="password"
                                class="form-control @error('password') is-invalid @enderror" name="password" required
                                autocomplete="current-password" placeholder="Nhập mật khẩu">
                            <span class="password-toggle" onclick="togglePassword()">
                                <i class="fas fa-eye"></i>
                            </span>
                        </div>
                        @error('password')
                        <div class="error-feedback">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>

                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember')
                            ? 'checked' : '' }}>
                        <label class="form-check-label" for="remember">
                            Ghi nhớ đăng nhập
                        </label>
                    </div>

                    <button type="submit" class="login-btn">
                        <i class="fas fa-sign-in-alt me-2"></i> Đăng nhập
                    </button>
                </form>
            </div>

            <div class="login-footer">
                <a href="{{ route('homepage') }}" class="back-to-site">
                    <i class="fas fa-arrow-left"></i> Quay lại trang chủ
                </a>
                <p class="mb-0">© {{ date('Y') }} NDN Phim. Bản quyền thuộc về NDN Phim.</p>
            </div>
        </div>
    </div>

    <script>
        function togglePassword() {
            const passwordField = document.getElementById('password');
            const icon = document.querySelector('.password-toggle i');
            
            if (passwordField.type === 'password') {
                passwordField.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                passwordField.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        }
    </script>
</body>

</html>