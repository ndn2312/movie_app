@extends('layouts.app')

@section('content')
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

    <!-- Grid Layout thay thế Table -->
    <div class="movie-grid" id="movie-grid-container">
        @foreach($list_episode as $key => $episode)
        <div class="movie-card" data-id="{{ $episode->id }}">
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
</div>

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
</style>

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
@endsection