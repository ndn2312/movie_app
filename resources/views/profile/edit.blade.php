@extends('layout')

@section('content')
<div class="profile-container">
    <div class="card">
        <div class="card-header">
            <h2><i class="fas fa-user-circle"></i> Hồ sơ cá nhân</h2>
        </div>
        <div class="card-body">
            @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
            @endif

            <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="form-group avatar-upload">
                    <label for="avatar">Ảnh đại diện</label>
                    <div class="current-avatar">
                        <img src="{{ $user->avatar ?? '/imgs/default-avatar.png' }}" alt="{{ $user->name }}">
                    </div>
                    <div class="custom-file">
                        <input type="file" class="custom-file-input" id="avatar" name="avatar">
                        <label class="custom-file-label" for="avatar">Chọn ảnh...</label>
                    </div>
                    @error('avatar')
                    <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="name">Họ tên</label>
                    <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $user->name) }}"
                        required>
                    @error('name')
                    <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" class="form-control" id="email" name="email"
                        value="{{ old('email', $user->email) }}" required>
                    @error('email')
                    <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <hr>
                <h4>Đổi mật khẩu</h4>
                <p class="text-muted">Để trống nếu bạn không muốn đổi mật khẩu</p>

                <div class="form-group">
                    <label for="current_password">Mật khẩu hiện tại</label>
                    <input type="password" class="form-control" id="current_password" name="current_password">
                    @error('current_password')
                    <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="password">Mật khẩu mới</label>
                    <input type="password" class="form-control" id="password" name="password">
                    @error('password')
                    <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="password_confirmation">Xác nhận mật khẩu mới</label>
                    <input type="password" class="form-control" id="password_confirmation" name="password_confirmation">
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Lưu thay đổi
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection