@extends('layouts.app')

@section('content')
<style>
    .episode-badge {
        position: absolute;
        top: 10px;
        right: 10px;
        background: linear-gradient(45deg, var(--primary-color), var(--secondary-color));
        color: white;
        font-weight: bold;
        padding: 5px 10px;
        border-radius: var(--border-radius);
        font-size: 14px;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
    }

    .delete-form-container {
        flex: 1;
        margin: 0;
    }

    /* Pagination styling */
    .pagination-container {
        display: flex;
        justify-content: center;
        margin-top: 30px;
        margin-bottom: 20px;
    }

    .pagination {
        display: flex;
        list-style: none;
        border-radius: var(--border-radius);
        overflow: hidden;
    }

    .page-item {
        margin: 0 3px;
    }

    .page-item.active .page-link {
        background: linear-gradient(45deg, var(--primary-color), var(--secondary-color));
        border-color: var(--primary-color);
        color: white;
    }

    .page-link {
        display: flex;
        align-items: center;
        justify-content: center;
        height: 38px;
        min-width: 38px;
        padding: 5px 12px;
        border-radius: var(--border-radius);
        border: 1px solid #ddd;
        background-color: white;
        color: #444;
        text-decoration: none;
        transition: all 0.2s ease;
    }

    .page-link:hover {
        background-color: #f5f5f5;
        color: var(--primary-color);
    }

    .page-item.disabled .page-link {
        color: #999;
        pointer-events: none;
        background-color: #f5f5f5;
    }

    /* Spinner và trạng thái tải */
    .loading-spinner {
        display: flex;
        align-items: center;
        justify-content: center;
        height: 200px;
        width: 100%;
        color: var(--primary-color);
        font-size: 20px;
    }

    .loading-spinner i {
        margin-right: 10px;
        font-size: 24px;
    }

    .error-message {
        text-align: center;
        padding: 20px;
        background-color: #fff0f0;
        border-radius: var(--border-radius);
        color: #ff3333;
        margin: 20px auto;
        max-width: 600px;
    }

    /* Checkbox style cho chọn nhiều tập phim */
    .movie-card-checkbox {
        position: absolute;
        top: 10px;
        left: 10px;
        transform: scale(1.3);
        z-index: 10;
        cursor: pointer;
    }

    .movie-card {
        position: relative;
    }

    .bulk-actions {
        margin-bottom: 15px;
        display: flex;
        align-items: center;
        flex-wrap: wrap;
        gap: 10px;
    }

    .delete-selected {
        background: linear-gradient(45deg, #ff5252, #f48fb1);
        color: white;
        border: none;
        padding: 8px 15px;
        border-radius: var(--border-radius);
        cursor: pointer;
        display: flex;
        align-items: center;
        font-weight: 500;
        transition: all 0.2s ease;
    }

    .delete-selected:hover {
        background: linear-gradient(45deg, #ff1744, #f06292);
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
    }

    .delete-selected:disabled {
        background: linear-gradient(45deg, #bdbdbd, #e0e0e0);
        cursor: not-allowed;
        box-shadow: none;
    }

    .delete-selected i {
        margin-right: 5px;
    }

    .select-all-container {
        display: flex;
        align-items: center;
    }

    .select-all-checkbox {
        transform: scale(1.3);
        margin-right: 5px;
    }

    .selection-count {
        margin-left: auto;
        color: #666;
        font-weight: 500;
    }

    /* Nút chọn tất cả tập phim mới */
    .select-all-btn {
        background: linear-gradient(45deg, #3949ab, #1e88e5);
        color: white;
        border: none;
        padding: 8px 15px;
        border-radius: var(--border-radius);
        font-weight: 500;
        display: flex;
        align-items: center;
        cursor: pointer;
        transition: all 0.2s ease;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    }

    .select-all-btn:hover {
        background: linear-gradient(45deg, #303f9f, #1976d2);
        box-shadow: 0 3px 8px rgba(0, 0, 0, 0.2);
    }

    .select-all-btn:active {
        transform: translateY(1px);
    }

    .select-all-btn i {
        margin-right: 5px;
    }

    /* Modal xác nhận */
    .modal-overlay {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: rgba(0, 0, 0, 0.7);
        z-index: 1000;
        display: flex;
        align-items: center;
        justify-content: center;
        opacity: 0;
        pointer-events: none;
        transition: opacity 0.3s ease;
    }

    .modal-overlay.show {
        opacity: 1;
        pointer-events: auto;
    }

    .confirm-modal {
        background-color: #fff;
        border-radius: 8px;
        max-width: 500px;
        width: 90%;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
        overflow: hidden;
        transform: translateY(-20px);
        transition: transform 0.3s ease;
    }

    .modal-overlay.show .confirm-modal {
        transform: translateY(0);
    }

    .modal-header {
        background: linear-gradient(45deg, var(--primary-color), var(--secondary-color));
        color: white;
        padding: 15px 20px;
    }

    .modal-header h3 {
        margin: 0;
        font-size: 18px;
        display: flex;
        align-items: center;
    }

    .modal-header h3 i {
        margin-right: 10px;
    }

    .modal-body {
        padding: 20px;
        color: #333;
    }

    .modal-body p {
        margin-top: 0;
        font-size: 16px;
    }

    .episode-count {
        background: #f1f8e9;
        padding: 10px 15px;
        border-radius: 5px;
        border-left: 3px solid #7cb342;
        margin: 15px 0;
        font-weight: 500;
    }

    .modal-actions {
        display: flex;
        justify-content: flex-end;
        padding: 15px 20px;
        background-color: #f5f5f5;
        gap: 10px;
    }

    .modal-btn {
        padding: 8px 20px;
        border-radius: 4px;
        border: none;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .cancel-btn {
        background-color: #e0e0e0;
        color: #333;
    }

    .cancel-btn:hover {
        background-color: #d5d5d5;
    }

    .confirm-btn {
        background: linear-gradient(45deg, #f44336, #e53935);
        color: white;
    }

    .confirm-btn:hover {
        background: linear-gradient(45deg, #d32f2f, #c62828);
    }

    .processing-btn {
        background: linear-gradient(45deg, #42a5f5, #64b5f6);
        color: white;
        pointer-events: none;
    }
</style>
<link rel="stylesheet" href="{{ asset('css/movie-grid.css') }}">
<script>
    // Vô hiệu hóa phân trang JavaScript
    document.addEventListener('DOMContentLoaded', function() {
        // Ngăn không cho hàm setupPagination chạy từ movie-grid.js
        window.setupPagination = function() { 
            console.log('Đã vô hiệu hóa phân trang JavaScript, sử dụng phân trang AJAX');
        };
    });
</script>
<script src="{{ asset('js/movie-grid.js') }}"></script>
<script src="{{ asset('js/episode-pagination.js') }}"></script>

<div class="container-fluid">
    <div class="dashboard-header">
        <h2 class="page-title"><i class="fas fa-film"></i> Quản Lý Tập Phim</h2>
        <a href="{{route('episode.create')}}" class="button-custom button-add">
            <i class="fas fa-plus-circle"></i> Thêm Tập Phim Mới
        </a>
    </div>

    <!-- Bộ lọc và tìm kiếm -->
    <div class="filter-section">
        <div class="search-box">
            <input type="text" id="movie-search" placeholder="Tìm kiếm tập phim...">
            <i class="fas fa-search search-icon"></i>
        </div>
    </div>

    <!-- Bulk action controls -->
    <div class="bulk-actions">
        <div class="select-all-container">
            <input type="checkbox" id="select-all-episodes" class="select-all-checkbox">
            <label for="select-all-episodes">Chọn trang này</label>
        </div>

        <button id="select-all-pages" class="select-all-btn">
            <i class="fas fa-check-double"></i> Chọn TẤT CẢ tập phim
        </button>

        <button id="unselect-all" class="select-all-btn" style="background: linear-gradient(45deg, #546e7a, #78909c);">
            <i class="fas fa-times"></i> Bỏ chọn tất cả
        </button>

        <button id="delete-selected-episodes" class="delete-selected" disabled>
            <i class="fas fa-trash-alt"></i> Xóa đã chọn
        </button>

        <span class="selection-count">0 tập phim được chọn</span>
    </div>

    <!-- Grid Layout thay thế Table -->
    <div class="movie-grid" id="movie-grid-container">
        @foreach($list_episode as $key => $episode)
        <div class="movie-card" data-id="{{ $episode->id }}">
            <input type="checkbox" class="movie-card-checkbox episode-checkbox" data-id="{{ $episode->id }}"
                data-title="{{ $episode->movie->title }} - {{ $episode->episode }}">
            <div class="movie-card-header">
                <div class="movie-image">
                    @php
                    $image_check = substr($episode->movie->image,0,5);
                    @endphp
                    @if($image_check == 'https')
                    <img src="{{ $episode->movie->image}}" alt="{{ $episode->movie->title }}">
                    @else
                    <img src="{{ asset('uploads/movie/'.$episode->movie->image) }}" alt="{{ $episode->movie->title }}">
                    @endif
                    <div class="episode-badge">{{ $episode->episode }}</div>
                </div>

                <h3 class="movie-title">{{ $episode->movie->title }}</h3>
                <p class="movie-original-title">Link: {{ Str::limit($episode->linkphim, 30) }}</p>
            </div>

            <div class="movie-footer">
                <div class="action-buttons">
                    <a href="{{route('episode.edit', $episode->id)}}" class="action-btn edit-btn">
                        <i class="fas fa-edit"></i> Sửa
                    </a>
                    {!! Form::open(['method'=>'DELETE','route'=>['episode.destroy',$episode->id], 'class' =>
                    'delete-form-container'])!!}
                    {!! Form::button('<i class="fas fa-trash-alt"></i> Xóa', ['type' => 'submit', 'class' => 'action-btn
                    delete-btn']) !!}
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Pagination -->
    <div class="pagination-container">
        {{ $list_episode->links('pagination::bootstrap-4') }}
    </div>

    <!-- Form xóa nhiều tập phim -->
    <form id="delete-episodes-form" action="{{ route('episode.delete_multiple') }}" method="POST"
        style="display: none;">
        @csrf
        <input type="hidden" name="episode_ids" id="episode-ids-input">
    </form>
</div>

@if(session('action_type') == 'xóa' || session('action_type') == 'thêm' || session('action_type') == 'cập nhật')
<div class="success-notification-overlay"
    id="{{ session('action_type') == 'xóa' ? 'deleteSuccessPopup' : 'successPopup' }}">
    <div class="{{ session('action_type') == 'xóa' ? 'delete-notification-card' : 'success-notification-card' }}">
        <div class="{{ session('action_type') == 'xóa' ? 'delete-icon-container' : 'success-icon-container' }}">
            <svg class="{{ 
                    session('action_type') == 'xóa' ? 'delete-checkmark' : 
                    (session('action_type') == 'thêm' ? 'success-checkmark add-icon' : 'success-checkmark update-icon') 
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
                Phim "<span
                    class="{{ session('action_type') == 'xóa' ? 'highlighted-title-delete' : 'highlighted-title' }}">{{
                    session('movie_title') }}</span>"
                {{ session('action_type') == 'xóa' ? session('delete_message') : session('success_message') }}
                <span class="action-highlight {{ 
                        session('action_type') == 'xóa' ? 'delete-action' : 
                        (session('action_type') == 'thêm' ? 'add-action' : 'update-action') 
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
                    (session('action_type') == 'thêm' ? 'success-button add-button' : 'success-button update-button') 
                }}" id="{{ session('action_type') == 'xóa' ? 'closeDeleteBtn' : 'closeSuccessBtn' }}">OK</button>
        </div>
    </div>
</div>


<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Determine which popup is active based on action type
        const isDeleteAction = "{{ session('action_type') }}" === "xóa";
        
        // Get the appropriate elements based on action type
        const popup = document.getElementById(isDeleteAction ? 'deleteSuccessPopup' : 'successPopup');
        const closeBtn = document.getElementById(isDeleteAction ? 'closeDeleteBtn' : 'closeSuccessBtn');
        const countdownElement = document.getElementById(isDeleteAction ? 'deleteCountdown' : 'countdown');
        
        let secondsLeft = 3;
        
        // Set up event listeners
        if (closeBtn) {
            closeBtn.addEventListener('click', closeNotification);
        }
        
        if (popup) {
            popup.addEventListener('click', e => {
                if (e.target === popup) closeNotification();
            });
        }
        
        document.addEventListener('keydown', e => {
            if (e.key === 'Enter' || e.key === 'Escape') closeNotification();
        });
        
        // Countdown timer
        const countdownInterval = setInterval(function() {
            secondsLeft--;
            if (countdownElement) countdownElement.textContent = secondsLeft;
            if (secondsLeft <= 0) {
                clearInterval(countdownInterval);
                closeNotification();
            }
        }, 1000);
        
        // Store interval ID in a global variable
        window.notificationCountdownId = countdownInterval;
        
        // Unified close function
        function closeNotification() {
            if (window.notificationCountdownId) {
                clearInterval(window.notificationCountdownId);
            }
            
            if (popup) {
                popup.style.animation = 'fadeOut 0.3s forwards';
                setTimeout(() => popup.style.display = 'none', 300);
            }
        }
    });
</script>
@endif

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const checkboxes = document.querySelectorAll('.episode-checkbox');
        const selectAllCheckbox = document.getElementById('select-all-episodes');
        const selectAllPagesBtn = document.getElementById('select-all-pages');
        const unselectAllBtn = document.getElementById('unselect-all');
        const deleteSelectedBtn = document.getElementById('delete-selected-episodes');
        const selectionCountSpan = document.querySelector('.selection-count');
        const deleteForm = document.getElementById('delete-episodes-form');
        const episodeIdsInput = document.getElementById('episode-ids-input');
        
        // Mảng lưu trữ ID các tập phim đã chọn
        let selectedEpisodeIds = [];
        
        // Hàm cập nhật số lượng được chọn và trạng thái nút xóa
        function updateSelection() {
            const selectedCheckboxes = document.querySelectorAll('.episode-checkbox:checked');
            const countOnPage = selectedCheckboxes.length;
            
            // Cập nhật mảng ID đã chọn từ các checkbox trên trang hiện tại
            selectedEpisodeIds = [];
            selectedCheckboxes.forEach(checkbox => {
                selectedEpisodeIds.push(checkbox.dataset.id);
            });
            
            // Cập nhật hiển thị số lượng đã chọn
            selectionCountSpan.textContent = `${selectedEpisodeIds.length} tập phim được chọn`;
            deleteSelectedBtn.disabled = selectedEpisodeIds.length === 0;
            
            // Cập nhật trạng thái của checkbox "Chọn tất cả"
            if (countOnPage === 0) {
                selectAllCheckbox.checked = false;
                selectAllCheckbox.indeterminate = false;
            } else if (countOnPage === checkboxes.length) {
                selectAllCheckbox.checked = true;
                selectAllCheckbox.indeterminate = false;
            } else {
                selectAllCheckbox.indeterminate = true;
            }
        }
        
        // Sự kiện khi click vào checkbox của từng tập phim
        checkboxes.forEach(checkbox => {
            checkbox.addEventListener('change', updateSelection);
        });
        
        // Sự kiện khi click vào "Chọn tất cả" (trên trang hiện tại)
        selectAllCheckbox.addEventListener('change', function() {
            const isChecked = this.checked;
            checkboxes.forEach(checkbox => {
                checkbox.checked = isChecked;
            });
            updateSelection();
        });
        
        // Sự kiện bỏ chọn tất cả tập phim
        unselectAllBtn.addEventListener('click', function() {
            // Bỏ chọn tất cả các checkbox
            checkboxes.forEach(checkbox => {
                checkbox.checked = false;
            });
            
            // Xóa mảng lưu ID
            selectedEpisodeIds = [];
            
            // Cập nhật hiển thị
            selectionCountSpan.textContent = '0 tập phim được chọn';
            deleteSelectedBtn.disabled = true;
            selectAllCheckbox.checked = false;
            selectAllCheckbox.indeterminate = false;
            
            // Hiệu ứng nút
            const originalText = this.innerHTML;
            this.innerHTML = '<i class="fas fa-check"></i> Đã bỏ chọn';
            setTimeout(() => {
                this.innerHTML = originalText;
            }, 1000);
        });
        
        // Sự kiện chọn tất cả tập phim không cần xác nhận
        selectAllPagesBtn.addEventListener('click', async function() {
            try {
                // Thay đổi text nút khi đang tải
                this.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Đang tải...';
                this.disabled = true;
                
                // Gọi AJAX để lấy tất cả ID tập phim
                const response = await fetch('{{ route("episode.get_all_ids") }}');
                const data = await response.json();
                
                if (data.success) {
                    // Lưu tất cả ID vào mảng
                    selectedEpisodeIds = data.episode_ids;
                    
                    // Chọn tất cả các checkbox trên trang hiện tại
                    checkboxes.forEach(checkbox => {
                        checkbox.checked = true;
                    });
                    
                    // Cập nhật hiển thị
                    selectionCountSpan.textContent = `${selectedEpisodeIds.length} tập phim được chọn (tất cả)`;
                    deleteSelectedBtn.disabled = false;
                }
            } catch (error) {
                console.error('Error:', error);
            } finally {
                // Khôi phục nút
                this.innerHTML = '<i class="fas fa-check-double"></i> Chọn TẤT CẢ tập phim';
                this.disabled = false;
            }
        });
        
        // Sự kiện xóa tập phim được chọn trực tiếp không cần xác nhận
        deleteSelectedBtn.addEventListener('click', function() {
            if (selectedEpisodeIds.length === 0) return;
            
            // Thay đổi text nút khi đang xử lý
            this.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Đang xử lý...';
            this.disabled = true;
            
            // Gửi form xóa
            episodeIdsInput.value = JSON.stringify(selectedEpisodeIds);
            deleteForm.submit();
        });
        
        // Khởi tạo
        updateSelection();
    });
</script>
@endsection