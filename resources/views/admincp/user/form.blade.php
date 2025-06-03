@extends('layouts.app')

@section('content')
<style>
    .form-group {
        margin-bottom: 15px;
    }

    .form-group label {
        font-weight: 600;
        margin-bottom: 5px;
    }

    .form-group .form-control {
        border-radius: 0.25rem;
        font-size: 14px;
        padding: 10px;
    }

    .card {
        border: none;
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
    }

    .card-header {
        background-color: #f8f9fa;
        border-bottom: 1px solid #e3e6f0;
        font-weight: 600;
    }

    .btn-submit {
        background-color: #4e73df;
        border-color: #4e73df;
        color: white;
        padding: 10px 20px;
        font-weight: 600;
        letter-spacing: 0.5px;
    }

    .btn-submit:hover {
        background-color: #2e59d9;
        border-color: #2653d4;
    }

    .btn-cancel {
        background-color: #858796;
        border-color: #858796;
        padding: 10px 20px;
        font-weight: 600;
        letter-spacing: 0.5px;
    }

    .password-note {
        font-size: 13px;
        color: #6c757d;
    }

    .avatar-preview {
        width: 150px;
        height: 150px;
        border-radius: 50%;
        object-fit: cover;
        margin-bottom: 15px;
        border: 2px solid #eaeaea;
    }

    .required-star {
        color: red;
        margin-left: 5px;
    }

    .role-select {
        height: 45px;
        border-radius: 0.25rem;
    }
</style>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header">
                    Cập nhật thông tin người dùng
                </div>

                <div class="card-body">
                    @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif

                    <form action="{{ route('user.update', $user->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="col-md-4 text-center">
                                <div class="form-group">
                                    <img id="avatar-preview"
                                        src="{{ $user->avatar ? asset($user->avatar) : 'https://ui-avatars.com/api/?name='.urlencode($user->name).'&background=random&color=fff' }}"
                                        class="avatar-preview" alt="Avatar">

                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input" name="avatar" id="avatar"
                                            accept="image/*">
                                        <label class="btn btn-outline-secondary w-100" for="avatar">
                                            <i class="fas fa-upload mr-2"></i> Tải hình đại diện
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-8">
                                <div class="form-group">
                                    <label for="name">Tên đầy đủ <span class="required-star">*</span></label>
                                    <input type="text" class="form-control" id="name" name="name"
                                        value="{{ old('name', $user->name) }}" required>
                                </div>

                                <div class="form-group">
                                    <label for="email">Email <span class="required-star">*</span></label>
                                    <input type="email" class="form-control" id="email" name="email"
                                        value="{{ old('email', $user->email) }}" required>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="password">Mật khẩu mới</label>
                                            <input type="password" class="form-control" id="password" name="password">
                                            <small class="password-note">Để trống nếu không muốn thay đổi mật
                                                khẩu.</small>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="password_confirmation">Xác nhận mật khẩu mới</label>
                                            <input type="password" class="form-control" id="password_confirmation"
                                                name="password_confirmation">
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="role">Vai trò <span class="required-star">*</span></label>
                                    <select class="form-control role-select" id="role" name="role" required>
                                        <option value="user" {{ (old('role', $user->role) == 'user') ? 'selected' : ''
                                            }}>Người dùng</option>
                                        <option value="admin" {{ (old('role', $user->role) == 'admin') ? 'selected' : ''
                                            }}>Quản trị viên</option>
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label>Trạng thái</label>
                                    <div class="form-check form-switch mt-2">
                                        <input class="form-check-input" type="checkbox" id="status" name="status"
                                            value="1" {{ old('status', $user->status ? true : false) ? 'checked' : ''
                                        }}>
                                        <label class="form-check-label" for="status">Hoạt động</label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <hr>

                        <div class="text-end">
                            <a href="{{ route('user.index') }}" class="btn btn-cancel text-white">Hủy</a>
                            <button type="submit" class="btn btn-submit">
                                <i class="fas fa-save me-1"></i> Cập nhật
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        // Preview hình ảnh trước khi upload
        $('#avatar').on('change', function() {
            const file = this.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    $('#avatar-preview').attr('src', e.target.result);
                }
                reader.readAsDataURL(file);
            }
        });
    });
</script>
@endsection