@extends('layout')
@section('content')
<style>
    .history-card {
        margin-bottom: 20px;
        background: #1c1c1c;
        border-radius: 8px;
        overflow: hidden;
        position: relative;
        transition: all 0.3s ease;
        height: 100%;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.4);
        max-width: 200px;
        margin-left: auto;
        margin-right: auto;
    }

    .history-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.5);
    }

    .poster-container {
        position: relative;
        width: 100%;
    }

    .poster-container img {
        width: 100%;
        height: 220px;
        object-fit: cover;
        display: block;
    }

    .progress-container {
        margin-top: 10px;
    }

    .progress {
        height: 5px;
        margin: 5px 0 0;
        background: rgba(0, 0, 0, 0.5);
        border-radius: 0;
    }

    .history-info {
        padding: 12px;
    }

    .movie-title {
        color: #fff;
        font-size: 16px;
        font-weight: 600;
        margin: 0 0 5px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .history-meta {
        display: flex;
        justify-content: space-between;
        color: #aaa;
        font-size: 13px;
        margin-bottom: 8px;
    }

    .remove-history {
        position: absolute;
        top: 10px;
        right: 10px;
        background: rgba(0, 0, 0, 0.6);
        color: #fff;
        border: none;
        width: 30px;
        height: 30px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        z-index: 5;
        opacity: 0;
        transition: opacity 0.2s ease, background 0.2s ease;
    }

    .history-card:hover .remove-history {
        opacity: 1;
    }

    .remove-history:hover {
        background: rgba(255, 0, 0, 0.7) !important;
    }

    .pagination {
        justify-content: center;
        margin-top: 20px;
    }

    .watch-time {
        color: #aaa;
        font-size: 12px;
        font-weight: 400;
        margin-bottom: 2px;
        text-align: right;
        font-family: 'Roboto Mono', monospace;
    }

    /* Modal Styles */
    .custom-modal {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        z-index: 9999;
        visibility: hidden;
        opacity: 0;
        transition: visibility 0.3s, opacity 0.3s;
    }

    .custom-modal.show {
        display: block;
        visibility: visible;
        opacity: 1;
    }

    .modal-overlay {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0, 0, 0, 0.7);
        backdrop-filter: blur(3px);
    }

    .modal-container {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -60%);
        background: #1e1e1e;
        border-radius: 10px;
        width: 90%;
        max-width: 450px;
        box-shadow: 0 15px 30px rgba(0, 0, 0, 0.3);
        transition: transform 0.3s, opacity 0.3s;
        opacity: 0;
    }

    .modal-container.show {
        transform: translate(-50%, -50%);
        opacity: 1;
    }

    .modal-header {
        padding: 15px 20px;
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        display: flex;
        justify-content: space-between;
        align-items: center;
        background: linear-gradient(to right, #04c1e7, #0f6060);
        border-radius: 10px 10px 0 0;
    }

    .modal-header h3 {
        margin: 0;
        color: #fff;
        font-size: 18px;
        font-weight: 600;
        display: flex;
        align-items: center;
    }

    .modal-header h3 i {
        margin-right: 10px;
    }

    .modal-close {
        background: none;
        border: none;
        color: rgba(255, 255, 255, 0.7);
        font-size: 18px;
        cursor: pointer;
        padding: 0;
        width: 30px;
        height: 30px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        transition: all 0.2s;
    }

    .modal-close:hover {
        background: rgba(255, 255, 255, 0.1);
        color: #fff;
    }

    .modal-content {
        padding: 25px 20px;
        display: flex;
        align-items: center;
    }

    .modal-icon {
        font-size: 40px;
        color: #04c1e7;
        margin-right: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .modal-icon.warning {
        color: #f1c40f;
    }

    .modal-text {
        flex: 1;
    }

    .modal-text p {
        margin: 0 0 10px;
        color: #fff;
        font-size: 16px;
    }

    .modal-text p:last-child {
        margin-bottom: 0;
    }

    .modal-note {
        color: #999 !important;
        font-size: 14px !important;
        font-style: italic;
    }

    .modal-actions {
        display: flex;
        justify-content: flex-end;
        padding: 15px 20px;
        border-top: 1px solid rgba(255, 255, 255, 0.1);
        gap: 10px;
    }

    .btn-cancel {
        background: rgba(255, 255, 255, 0.1);
        border: none;
        padding: 10px 20px;
        color: #fff;
        font-weight: 500;
        border-radius: 5px;
        cursor: pointer;
        transition: all 0.2s;
    }

    .btn-cancel:hover {
        background: rgba(255, 255, 255, 0.2);
    }

    .btn-confirm {
        background: linear-gradient(to right, #e74c3c, #c0392b);
        border: none;
        padding: 10px 20px;
        color: #fff;
        font-weight: 500;
        border-radius: 5px;
        cursor: pointer;
        transition: all 0.2s;
    }

    .btn-confirm:hover {
        box-shadow: 0 3px 8px rgba(0, 0, 0, 0.2);
        transform: translateY(-1px);
    }

    .btn-confirm:disabled {
        background: #999;
        cursor: not-allowed;
        transform: none !important;
        box-shadow: none !important;
    }

    /* Toast notifications */
    .toast-notification {
        position: fixed;
        bottom: 20px;
        right: 20px;
        background: #333;
        color: #fff;
        padding: 10px 15px;
        border-radius: 5px;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
        display: flex;
        align-items: center;
        max-width: 350px;
        transform: translateY(100px);
        opacity: 0;
        transition: transform 0.3s, opacity 0.3s;
        z-index: 9999;
    }

    .toast-notification.show {
        transform: translateY(0);
        opacity: 1;
    }

    .toast-notification.success {
        background: linear-gradient(to right, #2ecc71, #27ae60);
    }

    .toast-notification.error {
        background: linear-gradient(to right, #e74c3c, #c0392b);
    }

    .toast-notification.warning {
        background: linear-gradient(to right, #f39c12, #d35400);
    }

    .toast-notification.info {
        background: linear-gradient(to right, #3498db, #2980b9);
    }

    .toast-icon {
        margin-right: 12px;
        font-size: 18px;
    }

    .toast-message {
        flex: 1;
        font-size: 14px;
    }

    .toast-close {
        background: none;
        border: none;
        color: rgba(255, 255, 255, 0.7);
        cursor: pointer;
        margin-left: 10px;
        padding: 0;
    }

    /* Style for body when modal is open */
    body.modal-open {
        overflow: hidden;
    }

    /* Responsive adjustments */
    @media (max-width: 1200px) {
        .history-card {
            max-width: 180px;
        }
    }

    @media (max-width: 767px) {
        .col-sm-6.col-xs-12.history-item {
            padding-left: 7px;
            padding-right: 7px;
        }

        .history-card {
            max-width: 160px;
        }

        .poster-container img {
            height: 180px;
        }

        .modal-container {
            width: 95%;
        }
    }

    @media (max-width: 480px) {
        .col-xs-12.history-item {
            width: 50%;
            float: left;
        }

        .history-card {
            max-width: 140px;
        }

        .poster-container img {
            height: 160px;
        }

        .movie-title {
            font-size: 14px;
        }

        .modal-content {
            flex-direction: column;
            text-align: center;
        }

        .modal-icon {
            margin-right: 0;
            margin-bottom: 15px;
        }

        .toast-notification {
            max-width: 90%;
            left: 5%;
            right: 5%;
        }
    }
</style>
<div class="container">
    <div class="section-bar clearfix">
        <h3 class="section-title"><span>Lịch sử xem phim</span></h3>
    </div>

    <div class="history-actions text-right" style="margin-bottom: 20px;">
        <button id="clear-history" class="btn btn-danger">
            <i class="fas fa-trash-alt"></i> Xóa tất cả lịch sử
        </button>
    </div>

    @if($histories->isEmpty())
    <div class="empty-history">
        <div class="text-center" style="padding: 40px 0; background: rgba(0, 0, 0, 0.1); border-radius: 8px;">
            <i class="fas fa-history" style="font-size: 50px; color: #666; margin-bottom: 15px;"></i>
            <h4 style="color: #fff;">Bạn chưa xem phim nào</h4>
            <p style="color: #aaa;">Các phim bạn đã xem sẽ hiển thị ở đây</p>
            <a href="{{ route('homepage') }}" class="btn btn-primary" style="margin-top: 15px;">
                <i class="fas fa-home"></i> Khám phá phim ngay
            </a>
        </div>
    </div>
    @else
    <div class="row history-list">
        @foreach($histories as $history)
        <div class="col-lg-3 col-md-4 col-sm-6 col-xs-12 history-item" data-id="{{ $history->id }}">
            <div class="history-card">
                <a href="{{ route('watch', ['slug' => $history->movie->slug, 'tap' => 'tap-'.$history->episode]) }}"
                    class="history-thumb">
                    <div class="poster-container">
                        @php
                        $image = $history->thumbnail;
                        if (!Str::startsWith($image, ['http://', 'https://'])) {
                        $image = asset('uploads/movie/'.$image);
                        }
                        @endphp
                        <img src="{{ $image }}" alt="{{ $history->movie->title }}" class="img-responsive">
                    </div>
                </a>

                <button class="remove-history" data-id="{{ $history->id }}" data-title="{{ $history->movie->title }}"
                    data-episode="{{ $history->episode }}">
                    <i class="fas fa-times"></i>
                </button>

                <div class="history-info">
                    <h4 class="movie-title">{{ $history->movie->title }}</h4>
                    <div class="history-meta">
                        <span>
                            @if($history->episode)
                            {{ $history->episode }}
                            @else
                            Full
                            @endif
                        </span>
                        <span>{{ \Carbon\Carbon::parse($history->last_watched_at)->diffForHumans() }}</span>
                    </div>

                    <!-- Progress bar and time info moved here -->
                    <div class="progress-container">
                        <div class="watch-time">
                            @php
                            $currentTime = gmdate("H\\h i\\m", $history->current_time);
                            $totalTime = gmdate("H\\h i\\m", $history->duration);

                            // Nếu thời gian dưới 1 giờ, hiển thị định dạng khác
                            if ($history->current_time < 3600) { $currentTime=gmdate("i\\m", $history->current_time);
                                }
                                if ($history->duration < 3600) { $totalTime=gmdate("i\\m", $history->duration);
                                    }
                                    @endphp
                                    {{ $currentTime }} / {{ $totalTime }}
                        </div>
                        <div class="progress">
                            <div class="progress-bar" role="progressbar"
                                style="width: {{ $history->progress }}%; background: #04c1e7;"
                                aria-valuenow="{{ $history->progress }}" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Phân trang -->
    <div class="pagination-wrapper">
        {{ $histories->links() }}
    </div>
    @endif
</div>

<!-- Modal xác nhận xóa một mục lịch sử -->
<div id="delete-confirm-modal" class="custom-modal">
    <div class="modal-overlay"></div>
    <div class="modal-container">
        <div class="modal-header">
            <h3><i class="fas fa-trash-alt"></i> Xác nhận xóa</h3>
            <button class="modal-close"><i class="fas fa-times"></i></button>
        </div>
        <div class="modal-content">
            <div class="modal-icon">
                <i class="fas fa-exclamation-circle"></i>
            </div>
            <div class="modal-text">
                <p>Bạn có chắc chắn muốn xóa phim <span id="delete-movie-title"></span> khỏi lịch sử xem?</p>
                <p class="modal-note">Thao tác này không thể hoàn tác.</p>
            </div>
        </div>
        <div class="modal-actions">
            <button class="btn-cancel">Hủy bỏ</button>
            <button class="btn-confirm">Xác nhận xóa</button>
        </div>
    </div>
</div>

<!-- Modal xác nhận xóa tất cả lịch sử -->
<div id="delete-all-confirm-modal" class="custom-modal">
    <div class="modal-overlay"></div>
    <div class="modal-container">
        <div class="modal-header">
            <h3><i class="fas fa-trash-alt"></i> Xóa tất cả lịch sử</h3>
            <button class="modal-close"><i class="fas fa-times"></i></button>
        </div>
        <div class="modal-content">
            <div class="modal-icon warning">
                <i class="fas fa-exclamation-triangle"></i>
            </div>
            <div class="modal-text">
                <p>Bạn có chắc chắn muốn xóa <strong>tất cả lịch sử xem phim</strong>?</p>
                <p class="modal-note">Thao tác này sẽ xóa toàn bộ lịch sử xem và không thể hoàn tác.</p>
            </div>
        </div>
        <div class="modal-actions">
            <button class="btn-cancel">Hủy bỏ</button>
            <button class="btn-confirm">Xác nhận xóa tất cả</button>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        // Biến lưu trữ thông tin cho modal xóa một mục
        let currentHistoryId = null;
        let currentHistoryItem = null;
        
        // Modal xác nhận xóa một mục
        $('.remove-history').on('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            // Lưu thông tin mục cần xóa
            currentHistoryId = $(this).data('id');
            currentHistoryItem = $(this).closest('.history-item');
            const title = $(this).data('title');
            const episode = $(this).data('episode');
            
            // Hiển thị tên phim trong modal
            if (episode) {
                $('#delete-movie-title').text(`"${title} - ${episode}"`);
            } else {
                $('#delete-movie-title').text(`"${title}"`);
            }
            
            // Hiện modal xác nhận
            showModal('#delete-confirm-modal');
        });
        
        // Xử lý khi nhấn nút xác nhận xóa một mục
        $('#delete-confirm-modal .btn-confirm').on('click', function() {
            if (currentHistoryId) {
                // Hiển thị trạng thái đang xử lý
                const $btn = $(this);
                const originalText = $btn.text();
                $btn.html('<i class="fas fa-spinner fa-spin"></i> Đang xử lý...');
                $btn.prop('disabled', true);
                
                // Gửi yêu cầu xóa
                $.ajax({
                    url: "{{ url('/history/remove') }}/" + currentHistoryId,
                    type: "DELETE",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        if (response.success) {
                            // Ẩn modal
                            hideModal('#delete-confirm-modal');
                            
                            // Hiển thị thông báo thành công
                            showToast('Đã xóa khỏi lịch sử xem', 'success');
                            
                            // Xóa phần tử khỏi DOM với hiệu ứng
                            currentHistoryItem.fadeOut(300, function() {
                                $(this).remove();
                                
                                // Kiểm tra nếu không còn mục nào
                                if ($('.history-item').length === 0) {
                                    // Hiển thị thông báo trống
                                    const emptyMessage = `
                                        <div class="empty-history">
                                            <div class="text-center" style="padding: 40px 0; background: rgba(0, 0, 0, 0.1); border-radius: 8px;">
                                                <i class="fas fa-history" style="font-size: 50px; color: #666; margin-bottom: 15px;"></i>
                                                <h4 style="color: #fff;">Bạn chưa xem phim nào</h4>
                                                <p style="color: #aaa;">Các phim bạn đã xem sẽ hiển thị ở đây</p>
                                                <a href="{{ route('homepage') }}" class="btn btn-primary" style="margin-top: 15px;">
                                                    <i class="fas fa-home"></i> Khám phá phim ngay
                                                </a>
                                            </div>
                                        </div>
                                    `;
                                    $('.history-list').replaceWith(emptyMessage);
                                    $('.history-actions').hide();
                                    $('.pagination-wrapper').hide();
                                }
                            });
                        } else {
                            // Hiển thị thông báo lỗi
                            showToast('Có lỗi xảy ra, vui lòng thử lại sau', 'error');
                            
                            // Khôi phục nút
                            $btn.html(originalText);
                            $btn.prop('disabled', false);
                        }
                    },
                    error: function() {
                        // Hiển thị thông báo lỗi
                        showToast('Có lỗi xảy ra, vui lòng thử lại sau', 'error');
                        
                        // Khôi phục nút
                        $btn.html(originalText);
                        $btn.prop('disabled', false);
                        
                        // Ẩn modal
                        hideModal('#delete-confirm-modal');
                    }
                });
            }
        });
        
        // Xóa tất cả lịch sử
        $('#clear-history').on('click', function() {
            showModal('#delete-all-confirm-modal');
        });
        
        // Xử lý khi nhấn nút xác nhận xóa tất cả
        $('#delete-all-confirm-modal .btn-confirm').on('click', function() {
            // Hiển thị trạng thái đang xử lý
            const $btn = $(this);
            const originalText = $btn.text();
            $btn.html('<i class="fas fa-spinner fa-spin"></i> Đang xử lý...');
            $btn.prop('disabled', true);
            
            // Gửi yêu cầu xóa tất cả
            $.ajax({
                url: "{{ route('history.clear') }}",
                type: "DELETE",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    if (response.success) {
                        // Hiển thị thông báo thành công
                        showToast('Đã xóa tất cả lịch sử xem', 'success');
                        
                        // Tải lại trang sau 1 giây
                        setTimeout(function() {
                            location.reload();
                        }, 1000);
                    } else {
                        // Hiển thị thông báo lỗi
                        showToast('Có lỗi xảy ra, vui lòng thử lại sau', 'error');
                        
                        // Khôi phục nút
                        $btn.html(originalText);
                        $btn.prop('disabled', false);
                    }
                    
                    // Ẩn modal
                    hideModal('#delete-all-confirm-modal');
                },
                error: function() {
                    // Hiển thị thông báo lỗi
                    showToast('Có lỗi xảy ra, vui lòng thử lại sau', 'error');
                    
                    // Khôi phục nút
                    $btn.html(originalText);
                    $btn.prop('disabled', false);
                    
                    // Ẩn modal
                    hideModal('#delete-all-confirm-modal');
                }
            });
        });
        
        // Xử lý đóng modal khi nhấn nút hủy/đóng
        $('.modal-close, .btn-cancel').on('click', function() {
            hideModal($(this).closest('.custom-modal'));
        });
        
        // Xử lý đóng modal khi nhấn bên ngoài
        $('.modal-overlay').on('click', function() {
            hideModal($(this).closest('.custom-modal'));
        });
        
        // Hàm hiển thị modal
        function showModal(modalSelector) {
            const $modal = $(modalSelector);
            $modal.addClass('show');
            $('body').addClass('modal-open');
            
            // Hiệu ứng hiển thị
            setTimeout(function() {
                $modal.find('.modal-container').addClass('show');
            }, 10);
        }
        
        // Hàm ẩn modal
        function hideModal(modalSelector) {
            const $modal = $(modalSelector);
            $modal.find('.modal-container').removeClass('show');
            
            // Chờ hiệu ứng kết thúc rồi ẩn hoàn toàn
            setTimeout(function() {
                $modal.removeClass('show');
                $('body').removeClass('modal-open');
            }, 300);
        }
        
        // Hàm hiển thị thông báo toast
        function showToast(message, type = 'info') {
            // Xóa toast cũ nếu có
            $('.toast-notification').remove();
            
            // Xác định icon theo loại thông báo
            let icon = 'info-circle';
            let bgColor = '#3498db';
            
            if (type === 'success') {
                icon = 'check-circle';
                bgColor = '#2ecc71';
            } else if (type === 'error') {
                icon = 'exclamation-circle';
                bgColor = '#e74c3c';
            } else if (type === 'warning') {
                icon = 'exclamation-triangle';
                bgColor = '#f39c12';
            }
            
            // Tạo HTML cho toast
            const toast = $(`
                <div class="toast-notification ${type}">
                    <div class="toast-icon">
                        <i class="fas fa-${icon}"></i>
                    </div>
                    <div class="toast-message">${message}</div>
                    <button class="toast-close"><i class="fas fa-times"></i></button>
                </div>
            `);
            
            // Thêm vào body
            $('body').append(toast);
            
            // Hiển thị toast với hiệu ứng
            setTimeout(function() {
                toast.addClass('show');
            }, 10);
            
            // Tự động ẩn sau 3 giây
            setTimeout(function() {
                toast.removeClass('show');
                setTimeout(function() {
                    toast.remove();
                }, 300);
            }, 3000);
            
            // Xử lý đóng thủ công
            toast.find('.toast-close').on('click', function() {
                toast.removeClass('show');
                setTimeout(function() {
                    toast.remove();
                }, 300);
            });
        }
    });
</script>


@endsection