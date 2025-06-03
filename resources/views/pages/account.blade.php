@extends('layout')

@section('content')
<style>
    .account-page {
        padding: 30px 0;
    }

    .page-title {
        font-size: 24px;
        font-weight: 700;
        margin-bottom: 20px;
        color: #fff;
        border-bottom: 2px solid rgba(255, 255, 255, 0.1);
        padding-bottom: 15px;
    }

    .account-sidebar {
        background-color: rgba(0, 0, 0, 0.2);
        border-radius: 10px;
        padding: 20px;
        margin-bottom: 20px;
    }

    .profile-avatar-wrapper {
        display: flex;
        flex-direction: column;
        align-items: center;
        padding-bottom: 20px;
        margin-bottom: 20px;
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    }

    .profile-avatar {
        width: 120px;
        height: 120px;
        border-radius: 50%;
        margin-bottom: 15px;
        position: relative;
        overflow: hidden;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
    }

    .avatar-img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .avatar-overlay {
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        background-color: rgba(0, 0, 0, 0.6);
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        opacity: 0;
        transition: opacity 0.3s;
    }

    .profile-avatar:hover .avatar-overlay {
        opacity: 1;
    }

    .avatar-input {
        display: none;
    }

    .avatar-label {
        color: #fff;
        font-size: 18px;
        cursor: pointer;
        margin: 0;
        width: 100%;
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .profile-name {
        font-size: 18px;
        font-weight: 600;
        margin-bottom: 5px;
        color: #fff;
    }

    .profile-email {
        font-size: 14px;
        color: rgba(255, 255, 255, 0.6);
        margin-bottom: 0;
    }

    .account-menu {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .account-menu li {
        padding: 12px 15px;
        border-radius: 5px;
        margin-bottom: 5px;
        cursor: pointer;
        font-size: 16px;
        color: rgba(255, 255, 255, 0.8);
        transition: all 0.2s;
    }

    .account-menu li i {
        margin-right: 10px;
        width: 20px;
        text-align: center;
    }

    .account-menu li:hover {
        background-color: rgba(255, 255, 255, 0.1);
        color: #fff;
    }

    .account-menu li.active {
        background-color: #ffc107;
        color: #1d2544;
        font-weight: 600;
    }

    .account-menu li a {
        color: inherit;
        text-decoration: none;
        display: block;
    }

    .card {
        background-color: rgba(0, 0, 0, 0.2);
        border: none;
        border-radius: 10px;
        margin-bottom: 20px;
    }

    .card-header {
        background-color: rgba(0, 0, 0, 0.2);
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        padding: 15px 20px;
    }

    .card-header h5 {
        font-size: 18px;
        font-weight: 600;
        color: #fff;
        margin: 0;
    }

    .card-header h5 i {
        margin-right: 10px;
    }

    .card-body {
        padding: 20px;
    }

    .form-control {
        background-color: rgba(0, 0, 0, 0.2);
        border: 1px solid rgba(255, 255, 255, 0.1);
        color: #fff;
    }

    .form-control:focus {
        background-color: rgba(0, 0, 0, 0.3);
        border-color: #ffc107;
        box-shadow: 0 0 0 0.2rem rgba(255, 193, 7, 0.25);
        color: #fff;
    }

    .btn-primary {
        background-color: #ffc107;
        border-color: #ffc107;
        color: #1d2544;
        font-weight: 600;
    }

    .btn-primary:hover {
        background-color: #ffca2c;
        border-color: #ffca2c;
        color: #1d2544;
    }

    .account-content-wrapper {
        position: relative;
    }

    .account-tab {
        display: none;
    }

    .account-tab.active {
        display: block;
    }

    .form-control-static {
        padding: 0.375rem 0;
        margin: 0;
        color: rgba(255, 255, 255, 0.8);
    }

    .avatar-loading {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.6);
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        display: none;
    }

    .avatar-loading.active {
        display: flex;
    }

    @media (max-width: 767px) {
        .form-group.row {
            flex-direction: column;
        }

        .col-form-label {
            padding-bottom: 5px;
        }
    }

    .custom-control-input {
        position: absolute;
        left: 0;
        z-index: -1;
        width: 1rem;
        height: 1.25rem;
        opacity: 0;
    }

    .custom-control {
        position: relative;
        z-index: 1;
        display: block;
        min-height: 1.5rem;
        padding-left: 1.5rem;
        color: #fff;
    }

    .custom-control-inline {
        display: inline-flex;
        margin-right: 1rem;
    }

    .custom-radio .custom-control-label::before {
        content: "";
        position: absolute;
        top: 0.25rem;
        left: -1.5rem;
        display: block;
        width: 1rem;
        height: 1rem;
        pointer-events: none;
        background-color: rgba(0, 0, 0, 0.3);
        border: 1px solid rgba(255, 255, 255, 0.2);
        border-radius: 50%;
    }

    .custom-radio .custom-control-input:checked~.custom-control-label::after {
        content: "";
        position: absolute;
        top: 0.35rem;
        left: -1.4rem;
        display: block;
        width: 0.8rem;
        height: 0.8rem;
        border-radius: 50%;
        background-color: #ffc107;
        transform: scale(0.7);
    }

    .custom-radio .custom-control-input:focus~.custom-control-label::before {
        box-shadow: 0 0 0 0.2rem rgba(255, 193, 7, 0.25);
    }

    .gender-options {
        display: flex;
        gap: 20px;
        margin: 10px 0;
    }

    .gender-radio {
        position: relative;
        display: flex;
        align-items: center;
        min-width: 80px;
        background-color: rgba(0, 0, 0, 0.2);
        border-radius: 30px;
        padding: 8px 15px;
        transition: all 0.2s;
        cursor: pointer;
        border: 2px solid transparent;
    }

    .gender-radio:hover {
        background-color: rgba(0, 0, 0, 0.3);
    }

    .gender-input {
        position: absolute;
        opacity: 0;
        cursor: pointer;
    }

    .gender-label {
        position: relative;
        margin: 0;
        padding-left: 28px;
        cursor: pointer;
        font-weight: 500;
        color: rgba(255, 255, 255, 0.8);
    }

    .gender-label:before {
        content: "";
        position: absolute;
        left: 0;
        top: 50%;
        transform: translateY(-50%);
        width: 18px;
        height: 18px;
        border-radius: 50%;
        border: 2px solid rgba(255, 255, 255, 0.4);
        background-color: transparent;
        transition: all 0.2s;
    }

    .gender-input:checked+.gender-label:before {
        border-color: #ffc107;
        background-color: transparent;
    }

    .gender-input:checked+.gender-label:after {
        content: "";
        position: absolute;
        left: 5px;
        top: 50%;
        transform: translateY(-50%);
        width: 8px;
        height: 8px;
        border-radius: 50%;
        background-color: #ffc107;
    }

    .gender-input:checked~.gender-label {
        color: #ffc107;
    }

    .gender-input:checked~.gender-radio {
        border-color: #ffc107;
    }

    /* Thiết kế mới cho thông báo toast */
    .success-toast {
        position: fixed;
        bottom: 100px;
        right: -400px;
        background: linear-gradient(135deg, #21273d, #111827);
        border-radius: 12px;
        max-width: 360px;
        width: 100%;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.3), 0 0 0 1px rgba(255, 255, 255, 0.07);
        z-index: 9999;
        overflow: hidden;
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.1);
        transform: translateX(0);
        transition: right 0.5s cubic-bezier(0.68, -0.55, 0.27, 1.55);
    }

    .success-toast.show {
        right: 20px;
        animation: toast-in 0.6s forwards;
    }

    @keyframes toast-in {
        0% {
            transform: translateX(100%);
        }

        80% {
            transform: translateX(-5%);
        }

        100% {
            transform: translateX(0);
        }
    }

    .toast-content {
        display: flex;
        align-items: center;
        padding: 16px;
    }

    .toast-icon {
        min-width: 40px;
        height: 40px;
        margin-right: 12px;
        position: relative;
    }

    .toast-icon .check {
        width: 100%;
        height: 100%;
        color: #4ade80;
        font-size: 22px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        background: rgba(74, 222, 128, 0.2);
        animation: icon-scale 0.3s ease-in-out 0.15s both;
    }

    @keyframes icon-scale {
        0% {
            transform: scale(0);
            opacity: 0;
        }

        50% {
            transform: scale(1.2);
        }

        100% {
            transform: scale(1);
            opacity: 1;
        }
    }

    .toast-details {
        flex: 1;
    }

    .toast-title {
        color: #fff;
        font-weight: 600;
        font-size: 16px;
        display: block;
        margin-bottom: 5px;
    }

    .toast-message {
        color: rgba(255, 255, 255, 0.7);
        font-size: 14px;
        margin: 0;
        line-height: 1.3;
    }

    .toast-close {
        background: transparent;
        border: none;
        color: rgba(255, 255, 255, 0.5);
        font-size: 14px;
        cursor: pointer;
        padding: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        position: absolute;
        top: 12px;
        right: 12px;
        transition: all 0.2s;
        border-radius: 50%;
    }

    .toast-close:hover {
        color: #fff;
        background-color: rgba(255, 255, 255, 0.1);
    }

    .toast-progress {
        position: absolute;
        left: 0;
        bottom: 0;
        height: 4px;
        background: linear-gradient(90deg, #4ade80, #22c55e);
        width: 100%;
        animation: toast-progress 3s linear forwards;
        border-radius: 0 0 0 12px;
    }

    @keyframes toast-progress {
        0% {
            width: 100%;
        }

        100% {
            width: 0%;
        }
    }

    .btn-loading {
        position: relative;
        color: transparent !important;
    }

    .btn-loading::after {
        content: "";
        position: absolute;
        left: 50%;
        top: 50%;
        width: 20px;
        height: 20px;
        border: 2px solid rgba(255, 255, 255, 0.2);
        border-top-color: #fff;
        border-radius: 50%;
        transform: translate(-50%, -50%);
        animation: button-loading-spinner 0.8s linear infinite;
    }

    @keyframes button-loading-spinner {
        from {
            transform: translate(-50%, -50%) rotate(0deg);
        }

        to {
            transform: translate(-50%, -50%) rotate(360deg);
        }
    }
</style>
<div class="account-page">
    <div class="row">
        <div class="col-md-12">
            <h1 class="page-title">Thông Tin Tài Khoản</h1>

            @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                <i class="fas fa-check-circle"></i> {{ session('success') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            @endif
        </div>
    </div>

    <div class="row">
        <div class="col-md-3">
            <div class="account-sidebar">
                <div class="profile-avatar-wrapper">
                    <div class="profile-avatar">
                        <img src="{{$user->avatar ?? 'https://ui-avatars.com/api/?name='.urlencode($user->name).'&background=random&color=fff'}}"
                            alt="{{$user->name}}" class="avatar-img">

                        <div class="avatar-overlay">
                            <form id="avatar-form" action="{{ route('account.upload-avatar') }}" method="POST"
                                enctype="multipart/form-data">
                                @csrf
                                <input type="file" name="avatar" id="avatar-upload" class="avatar-input">
                                <label for="avatar-upload" class="avatar-label">
                                    <i class="fas fa-camera"></i>
                                </label>
                            </form>
                        </div>
                    </div>
                    <h4 class="profile-name">{{ $user->name }}</h4>
                    <p class="profile-email">{{ $user->email }}</p>
                </div>

                <ul class="account-menu">
                    <li class="active" data-tab="info">
                        <i class="fas fa-user"></i> Thông tin cá nhân
                    </li>
                    <li data-tab="password">
                        <i class="fas fa-lock"></i> Đổi mật khẩu
                    </li>
                    <li>
                        <a href="{{ route('favorites') }}">
                            <i class="fas fa-heart"></i> Phim yêu thích
                        </a>
                    </li>
                </ul>
            </div>
        </div>

        <div class="col-md-9">
            <div class="account-content-wrapper">
                <!-- Thông tin cá nhân -->
                <div class="account-tab active" id="info-tab">
                    <div class="card">
                        <div class="card-header">
                            <h5><i class="fas fa-user"></i> Thông Tin Cá Nhân</h5>
                        </div>
                        <div class="card-body">
                            <form id="account-form" action="{{ route('account.update') }}" method="POST"
                                class="ajax-form">
                                @csrf

                                <div class="form-group row">
                                    <label for="name" class="col-md-3 col-form-label">Họ tên</label>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control @error('name') is-invalid @enderror"
                                            id="name" name="name" value="{{ old('name', $user->name) }}">
                                        @error('name')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="email" class="col-md-3 col-form-label">Email</label>
                                    <div class="col-md-9">
                                        <input type="email" class="form-control @error('email') is-invalid @enderror"
                                            id="email" name="email" value="{{ old('email', $user->email) }}">
                                        @error('email')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="gender" class="col-md-3 col-form-label">Giới tính</label>
                                    <div class="col-md-9">
                                        <div class="gender-options">
                                            <div class="gender-radio">
                                                <input type="radio" id="gender-male" name="gender" value="male"
                                                    class="gender-input" {{ old('gender', $user->gender) == 'male' ?
                                                'checked' : '' }}>
                                                <label class="gender-label" for="gender-male">Nam</label>
                                            </div>
                                            <div class="gender-radio">
                                                <input type="radio" id="gender-female" name="gender" value="female"
                                                    class="gender-input" {{ old('gender', $user->gender) == 'female' ?
                                                'checked' : '' }}>
                                                <label class="gender-label" for="gender-female">Nữ</label>
                                            </div>
                                            <div class="gender-radio">
                                                <input type="radio" id="gender-other" name="gender" value="other"
                                                    class="gender-input" {{ old('gender', $user->gender) == 'other' ?
                                                'checked' : '' }}>
                                                <label class="gender-label" for="gender-other">Khác</label>
                                            </div>
                                        </div>
                                        @error('gender')
                                        <span class="invalid-feedback d-block">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-md-3 col-form-label">Ngày tham gia</label>
                                    <div class="col-md-9">
                                        <p class="form-control-static">{{ $user->created_at->format('d/m/Y') }}</p>
                                    </div>
                                </div>

                                <div class="form-group text-right">
                                    <button type="submit" class="btn btn-primary" id="save-account-btn">
                                        <i class="fas fa-save"></i> Lưu thay đổi
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Đổi mật khẩu -->
                <div class="account-tab" id="password-tab">
                    <div class="card">
                        <div class="card-header">
                            <h5><i class="fas fa-lock"></i> Đổi Mật Khẩu</h5>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('account.change-password') }}" method="POST">
                                @csrf

                                <div class="form-group row">
                                    <label for="current_password" class="col-md-3 col-form-label">Mật khẩu hiện
                                        tại</label>
                                    <div class="col-md-9">
                                        <input type="password"
                                            class="form-control @error('current_password') is-invalid @enderror"
                                            id="current_password" name="current_password">
                                        @error('current_password')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="new_password" class="col-md-3 col-form-label">Mật khẩu mới</label>
                                    <div class="col-md-9">
                                        <input type="password"
                                            class="form-control @error('new_password') is-invalid @enderror"
                                            id="new_password" name="new_password">
                                        @error('new_password')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="new_password_confirmation" class="col-md-3 col-form-label">Xác nhận mật
                                        khẩu</label>
                                    <div class="col-md-9">
                                        <input type="password" class="form-control" id="new_password_confirmation"
                                            name="new_password_confirmation">
                                    </div>
                                </div>

                                <div class="form-group text-right">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save"></i> Đổi mật khẩu
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Thông báo thành công kiểu Toast ở góc dưới bên phải -->
<div class="success-toast" id="success-toast">
    <div class="toast-content">
        <div class="toast-icon">
            <div class="check">
                <i class="fas fa-check-circle"></i>
            </div>
        </div>
        <div class="toast-details">
            <span class="toast-title">Thành công!</span>
            <p class="toast-message">Đã cập nhật thông tin tài khoản.</p>
        </div>
    </div>
    <button class="toast-close">
        <i class="fas fa-times"></i>
    </button>
    <div class="toast-progress"></div>
</div>





<!-- Script xử lý trang tài khoản -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
    // Xử lý chuyển tab
    const menuItems = document.querySelectorAll('.account-menu li[data-tab]');
    const tabs = document.querySelectorAll('.account-tab');
    
    menuItems.forEach(item => {
        item.addEventListener('click', function() {
            const tabId = this.getAttribute('data-tab');
            
            // Xóa active class từ tất cả menu items và tabs
            menuItems.forEach(mi => mi.classList.remove('active'));
            tabs.forEach(tab => tab.classList.remove('active'));
            
            // Thêm active class cho item được click và tab tương ứng
            this.classList.add('active');
            document.getElementById(`${tabId}-tab`).classList.add('active');
        });
    });
    
    // Xử lý upload avatar
    const avatarInput = document.getElementById('avatar-upload');
    const avatarForm = document.getElementById('avatar-form');
    
    if (avatarInput) {
        avatarInput.addEventListener('change', function() {
            if (this.files && this.files[0]) {
                // Tạo loading indicator
                const avatar = document.querySelector('.profile-avatar');
                const loadingDiv = document.createElement('div');
                loadingDiv.className = 'avatar-loading active';
                loadingDiv.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
                avatar.appendChild(loadingDiv);
                
                // Upload avatar
                avatarForm.submit();
            }
        });
    }
    
    // Xử lý radio buttons
    const genderRadios = document.querySelectorAll('.gender-radio');
    genderRadios.forEach(radio => {
        radio.addEventListener('click', function() {
            const input = this.querySelector('input[type="radio"]');
            input.checked = true;
        });
    });
    
    // Xử lý form submit bằng AJAX
    const accountForm = document.getElementById('account-form');
    const saveBtn = document.getElementById('save-account-btn');
    const toast = document.getElementById('success-toast');
    const closeBtn = document.querySelector('.toast-close');
    
    if (accountForm) {
        accountForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Hiển thị trạng thái đang lưu
            saveBtn.classList.add('btn-loading');
            saveBtn.disabled = true;
            
            // Lấy CSRF token từ meta tag
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            
            // Lấy dữ liệu form
            const formData = new FormData(this);
            
            // Gửi request AJAX
            fetch(this.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': csrfToken
                }
            })
            .then(response => response.json())
            .then(data => {
                // Khôi phục nút
                saveBtn.classList.remove('btn-loading');
                saveBtn.disabled = false;
                
                if (data.success) {
                    // Hiển thị thông báo thành công
                    showToast();
                } else {
                    // Xử lý lỗi nếu có
                    console.error('Lỗi:', data.errors);
                    alert('Đã xảy ra lỗi. Vui lòng kiểm tra lại thông tin.');
                }
            })
            .catch(error => {
                // Khôi phục nút
                saveBtn.classList.remove('btn-loading');
                saveBtn.disabled = false;
                console.error('Lỗi:', error);
                alert('Đã xảy ra lỗi khi kết nối đến máy chủ.');
            });
        });
    }
    
    // Hiển thị toast thông báo
    function showToast() {
        toast.classList.add('show');
        
        // Tự động ẩn sau 3 giây (đồng bộ với animation)
        setTimeout(function() {
            toast.classList.remove('show');
        }, 3000);
    }
    
    // Xử lý nút đóng thông báo
    if (closeBtn) {
        closeBtn.addEventListener('click', function() {
            toast.classList.remove('show');
        });
    }
    
    // Hiển thị thông báo thành công nếu có
    @if(session('success'))
        showToast();
    @endif
});
</script>

@endsection