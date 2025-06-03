@extends('layouts.app')

@section('content')
<style>
    /* CSS cho layout grid */
    .user-card {
        transition: all 0.3s ease;
        margin-bottom: 20px;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
        position: relative;
        background: #fff;
        height: 100%;
        display: flex;
        flex-direction: column;
    }

    .user-card:hover {
        transform: translateY(-6px);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
    }

    .user-header {
        padding: 20px 15px 10px;
        position: relative;
        background: linear-gradient(135deg, #f8f9fa, #e9ecef);
        text-align: center;
        border-bottom: 1px solid rgba(0, 0, 0, 0.05);
    }

    .avatar-container {
        position: relative;
        margin: 0 auto;
        width: 100px;
        height: 100px;
    }

    .user-avatar {
        width: 100px;
        height: 100px;
        border-radius: 50%;
        object-fit: cover;
        border: 4px solid #fff;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        transition: all 0.3s ease;
    }

    .user-card:hover .user-avatar {
        transform: scale(1.05);
        box-shadow: 0 6px 15px rgba(0, 0, 0, 0.15);
    }

    .user-role {
        position: absolute;
        bottom: -5px;
        right: -5px;
        padding: 5px 12px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 600;
        text-transform: uppercase;
        box-shadow: 0 3px 8px rgba(0, 0, 0, 0.1);
    }

    .role-admin {
        background-color: #cce5ff;
        color: #004085;
    }

    .role-user {
        background-color: #fff3cd;
        color: #856404;
    }

    .user-info {
        padding: 20px 15px;
        text-align: center;
        flex-grow: 1;
        display: flex;
        flex-direction: column;
    }

    .user-name {
        margin: 0 0 5px;
        font-size: 20px;
        font-weight: 600;
        color: #333;
    }

    .user-email {
        color: #6c757d;
        font-size: 14px;
        margin: 5px 0 12px;
        overflow: hidden;
        text-overflow: ellipsis;
        padding: 0 10px;
    }

    .user-date {
        font-size: 13px;
        color: #888;
        margin-bottom: 15px;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 5px;
    }

    .status-container {
        display: flex;
        align-items: center;
        justify-content: center;
        background-color: #f8f9fa;
        padding: 10px;
        border-radius: 8px;
        margin: 10px 0;
    }

    .status-container .form-check-input {
        margin-right: 10px;
    }

    .user-actions {
        display: flex;
        justify-content: center;
        gap: 10px;
        margin-top: auto;
        padding-top: 15px;
    }

    .user-checkbox {
        position: absolute;
        top: 10px;
        left: 10px;
        z-index: 10;
    }

    .form-check-input.status-switch {
        width: 45px;
        height: 22px;
        cursor: pointer;
    }

    .status-active {
        color: #198754;
        font-weight: 600;
    }

    .status-inactive {
        color: #dc3545;
        font-weight: 600;
    }

    .select-all-container {
        background-color: #f8f9fa;
        padding: 10px 15px;
        border-radius: 8px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
    }

    .btn-card-action {
        padding: 8px 16px;
        border-radius: 8px;
        font-weight: 500;
        transition: all 0.2s ease;
        min-width: 80px;
    }

    .btn-card-action:hover {
        transform: translateY(-2px);
    }

    /* Pagination styling */
    .pagination {
        justify-content: center;
        margin-top: 30px;
    }

    .pagination .page-link {
        border-radius: 8px;
        margin: 0 3px;
        color: #495057;
    }

    .pagination .page-item.active .page-link {
        background-color: #0d6efd;
        border-color: #0d6efd;
    }

    /* Fix for Bootstrap switch alignment */
    .form-check-input:checked {
        background-color: #0d6efd;
        border-color: #0d6efd;
    }

    /* Checkbox custom styling */
    .custom-checkbox {
        transform: scale(1.3);
        margin-right: 5px;
        cursor: pointer;
    }

    /* Card highlight khi được chọn */
    .user-card.selected {
        box-shadow: 0 0 0 3px #0d6efd, 0 8px 25px rgba(0, 0, 0, 0.15);
    }

    /* Animation cho card */
    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(20px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .col-md-3 {
        animation: fadeIn 0.5s ease forwards;
        opacity: 0;
    }

    @media (max-width: 768px) {
        .user-actions {
            flex-direction: column;
            gap: 5px;
        }

        .btn-card-action {
            width: 100%;
        }
    }
</style>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                {{-- <div class="d-flex mb-3" style="gap: 15px;">
                    <a href="{{ route('movie.index') }}" class="button-custom">
                        <i>🎬 DANH SÁCH PHIM</i>
                    </a>
                </div> --}}

                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Quản lý người dùng</h5>
                    <span class="badge bg-primary">{{ $users->total() }} người dùng</span>
                </div>

                <div class="card-body">
                    @if(session('success') || session('error'))
                    <div class="position-fixed top-0 end-0 mt-3 me-3 alert alert-{{ session('success') ? 'success' : 'danger' }} alert-dismissible fade show small p-2"
                        role="alert" style="max-width: 250px;">
                        <strong>{{ session('success') ? '✔' : '✖' }}</strong> {{ session('success') ?? session('error')
                        }}
                    </div>
                    @endif

                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <button id="bulk-delete" class="btn btn-danger" disabled>
                            <i class="fas fa-trash-alt"></i> Xóa người dùng đã chọn
                        </button>

                        <div class="select-all-container">
                            <div class="form-check">
                                <input class="form-check-input custom-checkbox" type="checkbox" id="select-all-users">
                                <label class="form-check-label" for="select-all-users">
                                    <strong>Chọn tất cả</strong>
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="row" id="users-grid">
                        @foreach($users as $key => $user)
                        <div class="col-md-3 col-sm-6 mb-4" style="animation-delay: {{ $key * 0.05 }}s">
                            <div class="user-card" id="user-{{ $user->id }}">
                                <div class="user-checkbox">
                                    <input type="checkbox" class="form-check-input custom-checkbox user-select-box"
                                        data-user-id="{{ $user->id }}" {{ $user->id == Auth::id() ? 'disabled' : '' }}>
                                </div>

                                <div class="user-header">
                                    <div class="avatar-container">
                                        <img src="{{ $user->avatar ?? 'https://ui-avatars.com/api/?name='.urlencode($user->name).'&background=random&color=fff' }}"
                                            class="user-avatar" alt="{{ $user->name }}">
                                        <span
                                            class="user-role {{ $user->role == 'admin' ? 'role-admin' : 'role-user' }}">
                                            {{ $user->role == 'admin' ? 'Admin' : 'User' }}
                                        </span>
                                    </div>
                                </div>

                                <div class="user-info">
                                    <h5 class="user-name">{{ $user->name }}</h5>
                                    <div class="user-email" title="{{ $user->email }}">{{ $user->email }}</div>
                                    <div class="user-date">
                                        <i class="fas fa-calendar-alt"></i>
                                        <span>{{ $user->created_at->format('d/m/Y') }}</span>
                                    </div>

                                    <div class="status-container">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input status-switch" type="checkbox"
                                                id="status-{{ $user->id }}" data-user-id="{{ $user->id }}" {{
                                                isset($user->status) && $user->status ? 'checked' : '' }}>
                                            <label
                                                class="form-check-label {{ isset($user->status) && $user->status ? 'status-active' : 'status-inactive' }}"
                                                for="status-{{ $user->id }}">
                                                {{ isset($user->status) && $user->status ? 'Hoạt động' : 'Bị khóa' }}
                                            </label>
                                        </div>
                                    </div>

                                    <div class="user-actions">
                                        <a href="{{ route('user.edit', $user->id) }}"
                                            class="btn btn-primary btn-sm btn-card-action">
                                            <i class="fas fa-edit"></i> Sửa
                                        </a>

                                        <button type="button"
                                            class="btn btn-danger btn-sm btn-card-action delete-user-btn" {{ $user->id
                                            == Auth::id() ? 'disabled' : '' }}
                                            data-user-id="{{ $user->id }}"
                                            data-user-name="{{ $user->name }}">
                                            <i class="fas fa-trash-alt"></i> Xóa
                                        </button>

                                        {!! Form::open(['method'=>'DELETE', 'route'=>['user.destroy', $user->id],
                                        'class'=>'d-none delete-form', 'id' => 'delete-form-'.$user->id]) !!}
                                        {!! Form::close() !!}
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    <div class="pagination-container">
                        {{ $users->links() }}
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

@if(session('action_type') == 'xóa' || session('action_type') == 'cập nhật')
<div class="success-notification-overlay"
    id="{{ session('action_type') == 'xóa' ? 'deleteSuccessPopup' : 'successPopup' }}">
    <div class="{{ session('action_type') == 'xóa' ? 'delete-notification-card' : 'success-notification-card' }}">
        <div class="{{ session('action_type') == 'xóa' ? 'delete-icon-container' : 'success-icon-container' }}">
            <svg class="{{ 
                    session('action_type') == 'xóa' ? 'delete-checkmark' : 
                    (session('action_type') == 'cập nhật' ? 'success-checkmark update-icon' : 'success-checkmark update-icon') 
                }}" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 52 52">
                <circle class="checkmark-circle" cx="26" cy="26" r="25" fill="none" />
                <path class="checkmark-check" fill="none" d="M14.1 27.2l7.1 7.2 16.7-16.8" />
            </svg>
        </div>

        <div
            class="{{ session('action_type') == 'xóa' ? 'delete-notification-content' : 'success-notification-content' }}">
            <h2 class="{{ 
                    session('action_type') == 'xóa' ? 'delete-title' : 
                    (session('action_type') == 'cập nhật' ? 'success-title update-title' : 'success-title') 
                }}">Thành công!</h2>

            <p class="{{ session('action_type') == 'xóa' ? 'delete-message' : 'success-message' }}">
                Người dùng "<span
                    class="{{ session('action_type') == 'xóa' ? 'highlighted-title-delete' : 'highlighted-title' }}">{{
                    session('user_name') }}</span>"
                {{ session('action_type') == 'xóa' ? session('delete_message') : session('success_message') }}
                <span class="action-highlight {{ 
                        session('action_type') == 'xóa' ? 'delete-action' : 
                        (session('action_type') == 'cập nhật' ? 'update-action' : 'update-action') 
                    }}">{{ session('action_type') }}</span>
                {{ session('action_type') == 'xóa' ? session('delete_end') : session('success_end') }}
            </p>

            <div class="{{ session('action_type') == 'xóa' ? 'delete-countdown-container' : 'countdown-container' }}">
                <span>Tự động đóng sau </span>
                <span class="countdown-number"
                    id="{{ session('action_type') == 'xóa' ? 'deleteCountdown' : 'countdown' }}">3</span>
                <span> giây</span>
            </div>

            <button class="{{ 
                    session('action_type') == 'xóa' ? 'delete-button' : 
                    (session('action_type') == 'cập nhật' ? 'success-button update-button' : 'success-button update-button') 
                }}" id="{{ session('action_type') == 'xóa' ? 'closeDeleteBtn' : 'closeSuccessBtn' }}">OK</button>
        </div>
    </div>
</div>
@endif

{{-- Định nghĩa các biến JavaScript cần thiết --}}
<script>
    // Biến dùng cho xử lý AJAX
    const csrfToken = "{{ csrf_token() }}";
    const currentAuthUserId = {{ Auth::id() }};
    const changeStatusUrl = "{{ route('user.change_status') }}";
    const deleteMultipleUrl = "{{ route('user.delete_multiple') }}";
</script>

{{-- Sử dụng JavaScript inline để xử lý sự kiện --}}
<script>
    document.addEventListener('DOMContentLoaded', function() {
    // Lấy tất cả các switch trạng thái
    const switches = document.querySelectorAll('.status-switch');
    
    // Đăng ký sự kiện cho mỗi switch
    switches.forEach(function(switchElement) {
        switchElement.addEventListener('change', function() {
            // Lấy thông tin từ switch
            const userId = this.dataset.userId;
            const status = this.checked ? 1 : 0;
            const label = this.nextElementSibling;
            
            console.log("Đang thay đổi trạng thái cho user ID:", userId, "Trạng thái mới:", status);
            
            // Vô hiệu hóa switch trong quá trình xử lý
            this.disabled = true;
            
            // Hiển thị loading
            const card = this.closest('.user-card');
            if (card) card.style.opacity = '0.7';
            
            // Chuẩn bị form data thay vì JSON để phù hợp với Laravel
            const formData = new FormData();
            formData.append('user_id', userId);
            formData.append('status', status);
            formData.append('_token', csrfToken);
            
            // Gửi request AJAX
            fetch(changeStatusUrl, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    // Không cần đặt Content-Type khi dùng FormData
                },
                body: formData
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                console.log("Thành công:", data);
                // Cập nhật nhãn
                label.textContent = status ? 'Hoạt động' : 'Bị khóa';
                
                // Cập nhật class cho nhãn
                if (status) {
                    label.classList.remove('status-inactive');
                    label.classList.add('status-active');
                } else {
                    label.classList.remove('status-active');
                    label.classList.add('status-inactive');
                }
                
                // Hiển thị thông báo thành công dạng toast
                Swal.fire({
                    icon: 'success',
                    title: 'Thành công!',
                    text: 'Đã cập nhật trạng thái người dùng',
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000
                });
            })
            .catch(error => {
                console.error("Lỗi:", error);
                // Khôi phục trạng thái switch
                this.checked = !status;
                
                // Hiển thị thông báo lỗi
                Swal.fire({
                    icon: 'error',
                    title: 'Lỗi!',
                    text: 'Đã xảy ra lỗi khi cập nhật trạng thái',
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000
                });
            })
            .finally(() => {
                // Kích hoạt lại switch và loại bỏ loading
                this.disabled = false;
                if (card) card.style.opacity = '1';
            });
        });
    });
    
    // Xử lý sự kiện khi nhấn nút xóa người dùng
    document.querySelectorAll('.delete-user-btn').forEach(function(button) {
        button.addEventListener('click', function() {
            const userId = this.dataset.userId;
            const userName = this.dataset.userName;
            
            Swal.fire({
                title: 'Xác nhận xóa?',
                html: `Bạn có chắc chắn muốn xóa người dùng <strong>${userName}</strong>?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Có, xóa ngay!',
                cancelButtonText: 'Hủy'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Submit form xóa
                    document.getElementById('delete-form-' + userId).submit();
                }
            });
        });
    });
    
    // Xử lý chọn/bỏ chọn người dùng
    const selectAllCheckbox = document.getElementById('select-all-users');
    const userCheckboxes = document.querySelectorAll('.user-select-box:not(:disabled)');
    const bulkDeleteBtn = document.getElementById('bulk-delete');
    
    // Cập nhật trạng thái nút xóa hàng loạt
    function updateBulkDeleteButton() {
        const checkedCount = document.querySelectorAll('.user-select-box:checked').length;
        bulkDeleteBtn.disabled = checkedCount === 0;
        
        // Cập nhật text của nút để hiển thị số lượng đã chọn
        if (checkedCount > 0) {
            bulkDeleteBtn.innerHTML = `<i class="fas fa-trash-alt"></i> Xóa ${checkedCount} người dùng đã chọn`;
        } else {
            bulkDeleteBtn.innerHTML = `<i class="fas fa-trash-alt"></i> Xóa người dùng đã chọn`;
        }
    }
    
    // Xử lý khi click vào checkbox chọn tất cả
    selectAllCheckbox.addEventListener('change', function() {
        userCheckboxes.forEach(checkbox => {
            checkbox.checked = this.checked;
            
            // Highlight thẻ cha nếu được chọn
            const card = checkbox.closest('.user-card');
            if (card) {
                if (this.checked) {
                    card.classList.add('selected');
                } else {
                    card.classList.remove('selected');
                }
            }
        });
        
        updateBulkDeleteButton();
    });
    
    // Xử lý khi click vào mỗi checkbox đơn lẻ
    userCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            // Kiểm tra nếu tất cả đã được chọn
            const allChecked = Array.from(userCheckboxes).every(c => c.checked);
            const anyChecked = Array.from(userCheckboxes).some(c => c.checked);
            
            selectAllCheckbox.checked = allChecked;
            selectAllCheckbox.indeterminate = anyChecked && !allChecked;
            
            // Highlight thẻ cha nếu được chọn
            const card = this.closest('.user-card');
            if (card) {
                if (this.checked) {
                    card.classList.add('selected');
                } else {
                    card.classList.remove('selected');
                }
            }
            
            updateBulkDeleteButton();
        });
    });
    
    // Xử lý sự kiện xóa hàng loạt
    if (bulkDeleteBtn) {
        bulkDeleteBtn.addEventListener('click', function(e) {
            e.preventDefault();
            
            // Lấy danh sách IDs đã chọn
            const selectedUsers = Array.from(document.querySelectorAll('.user-select-box:checked'))
                                  .map(checkbox => checkbox.dataset.userId);
            
            if (selectedUsers.length === 0) {
                Swal.fire({
                    icon: 'info',
                    title: 'Chưa chọn người dùng',
                    text: 'Vui lòng chọn ít nhất một người dùng để xóa',
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000
                });
                return;
            }
            
            // Kiểm tra xem có đang cố gắng xóa tài khoản đang đăng nhập không
            const currentUserId = currentAuthUserId.toString();
            if (selectedUsers.includes(currentUserId)) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Cảnh báo!',
                    text: 'Bạn không thể xóa tài khoản đang đăng nhập!',
                    confirmButtonText: 'OK'
                });
                return;
            }
            
            Swal.fire({
                title: 'Xác nhận xóa hàng loạt?',
                html: `Bạn có chắc chắn muốn xóa <strong>${selectedUsers.length}</strong> người dùng đã chọn?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Có, xóa tất cả!',
                cancelButtonText: 'Hủy'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Hiển thị loading
                    Swal.fire({
                        title: 'Đang xử lý...',
                        text: 'Vui lòng đợi trong giây lát',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });
                    
                    // Thực hiện AJAX để xóa hàng loạt
                    const formData = new FormData();
                    formData.append('_token', csrfToken);
                    
                    // Thêm mỗi ID vào form data
                    selectedUsers.forEach(id => {
                        formData.append('ids[]', id);
                    });
                    
                    // Gửi AJAX request
                    fetch(deleteMultipleUrl, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': csrfToken,
                        },
                        body: formData
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Đã xóa thành công!',
                                text: data.message,
                                timer: 2000,
                                timerProgressBar: true
                            }).then(() => {
                                location.reload();
                            });
                        }
                    })
                    .catch(error => {
                        Swal.fire({
                            icon: 'error',
                            title: 'Lỗi!',
                            text: 'Đã có lỗi xảy ra trong quá trình xóa.',
                        });
                    });
                }
            });
        });
    }
});
</script>
@endsection