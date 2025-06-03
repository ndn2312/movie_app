/**
 * NDN Phim - Quản lý người dùng JavaScript
 */

$(document).ready(function () {
    // Cấu hình AJAX để gửi CSRF token trong mọi request
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    // Khởi tạo DataTable với checkbox
    var table = $('#usersTable').DataTable({
        responsive: true,
        language: {
            url: '//cdn.datatables.net/plug-ins/1.13.4/i18n/vi.json',
        },
        columnDefs: [{
            targets: 0,
            checkboxes: {
                selectRow: true
            }
        }],
        select: {
            style: 'multi'
        },
        order: [
            [1, 'asc']
        ]
    });

    // Xử lý trạng thái nút xóa hàng loạt
    table.on('select', function () {
        let selectedRows = table.column(0).checkboxes.selected();
        $('#bulk-delete').prop('disabled', selectedRows.length === 0);
    });

    table.on('deselect', function () {
        let selectedRows = table.column(0).checkboxes.selected();
        $('#bulk-delete').prop('disabled', selectedRows.length === 0);
    });

    // Xử lý xóa hàng loạt
    $('#bulk-delete').on('click', function (e) {
        e.preventDefault();

        let selectedRows = table.column(0).checkboxes.selected().toArray();
        let currentUserId = currentAuthUserId; // Được định nghĩa trong view

        // Kiểm tra xem có đang cố gắng xóa tài khoản hiện tại không
        if (selectedRows.includes(currentUserId.toString())) {
            Swal.fire({
                icon: 'warning',
                title: 'Cảnh báo!',
                text: 'Bạn không thể xóa tài khoản đang đăng nhập!',
                confirmButtonText: 'OK'
            });
            return;
        }

        if (selectedRows.length > 0) {
            Swal.fire({
                title: 'Xác nhận xóa?',
                text: `Bạn có chắc chắn muốn xóa ${selectedRows.length} người dùng đã chọn?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Xác nhận xóa',
                cancelButtonText: 'Hủy'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: deleteMultipleUrl, // Được định nghĩa trong view
                        type: 'POST',
                        data: {
                            _token: csrfToken, // Được định nghĩa trong view
                            ids: selectedRows
                        },
                        success: function (response) {
                            if (response.success) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Đã xóa thành công!',
                                    text: response.message,
                                    timer: 2000,
                                    timerProgressBar: true
                                }).then(() => {
                                    location.reload();
                                });
                            }
                        },
                        error: function (xhr) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Lỗi!',
                                text: 'Đã có lỗi xảy ra trong quá trình xóa.',
                            });
                        }
                    });
                }
            });
        }
    });

    // Xử lý thay đổi trạng thái người dùng
    $('.status-switch').on('change', function () {
        const userId = $(this).data('user-id');
        const status = this.checked ? 1 : 0;
        const label = $(this).next('label');
        const switchElement = $(this); // Lưu tham chiếu đến switch hiện tại

        console.log("Thay đổi trạng thái cho user ID:", userId, "Trạng thái mới:", status);
        console.log("URL gửi request:", changeStatusUrl); // Log URL để kiểm tra

        // Vô hiệu hóa switch trong quá trình AJAX request
        switchElement.prop('disabled', true);

        $.ajax({
            url: changeStatusUrl, // Được định nghĩa trong view
            type: 'POST',
            data: {
                _token: csrfToken, // Được định nghĩa trong view
                user_id: userId,
                status: status
            },
            success: function (response) {
                console.log("Response success:", response);

                // Cập nhật label
                label.text(status ? 'Hoạt động' : 'Bị khóa');

                // Hiển thị thông báo mini
                Swal.fire({
                    position: 'top-end',
                    icon: 'success',
                    title: response.success,
                    showConfirmButton: false,
                    timer: 1500,
                    toast: true
                });
            },
            error: function (xhr, status, error) {
                console.error("Error response:", xhr);
                console.error("Status:", status);
                console.error("Error:", error);

                // Khôi phục trạng thái switch nếu có lỗi
                switchElement.prop('checked', !status);

                Swal.fire({
                    icon: 'error',
                    title: 'Lỗi!',
                    text: 'Đã có lỗi xảy ra trong quá trình cập nhật trạng thái. Chi tiết: ' + error,
                });
            },
            complete: function () {
                // Cho phép tương tác lại với switch
                switchElement.prop('disabled', false);
            }
        });
    });
});
