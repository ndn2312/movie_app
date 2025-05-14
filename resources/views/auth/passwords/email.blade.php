<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>NDN Phim - Quên mật khẩu</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
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
                <p>Khôi phục mật khẩu</p>
            </div>
            
            <div class="login-body">
                @if (session('status'))
                    <div class="alert alert-success" role="alert">
                        {{ session('status') }}
                    </div>
                @endif
                
                <form method="POST" action="{{ route('password.email') }}">
                    @csrf
                    
                    <div class="mb-4">
                        <label for="email" class="form-label">Email</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" 
                                name="email" value="{{ old('email') }}" required autocomplete="email" autofocus 
                                placeholder="Nhập địa chỉ email của bạn">
                        </div>
                        @error('email')
                            <div class="error-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    
                    <button type="submit" class="login-btn">
                        <i class="fas fa-paper-plane me-2"></i> Gửi link khôi phục mật khẩu
                    </button>
                    
                    <a href="{{ route('login') }}" class="forgot-link mt-3">
                        <i class="fas fa-arrow-left me-1"></i> Quay lại trang đăng nhập
                    </a>
                </form>
            </div>
            
            <div class="login-footer">
                <p class="mb-0">© {{ date('Y') }} NDN Phim. Bản quyền thuộc về NDN Phim.</p>
            </div>
        </div>
    </div>
</body>
</html>
