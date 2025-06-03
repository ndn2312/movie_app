@extends('layouts.app')

@section('content')
<style>
    /* Định dạng cho nút tìm kiếm API mới */
    .search-action-container {
        display: flex;
        align-items: center;
        justify-content: flex-end;
    }

    .search-api-btn {
        background-color: #4a6bff;
        color: white;
        padding: 10px 20px;
        border-radius: 6px;
        text-decoration: none;
        font-weight: 500;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: all 0.3s ease;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    }

    .search-api-btn:hover {
        background-color: #3451d1;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        transform: translateY(-2px);
        color: white;
        text-decoration: none;
    }

    .search-api-btn i {
        font-size: 16px;
    }

    /* Layout phù hợp cho container */
    .search-filter-container {
        display: flex;
        justify-content: space-between;
        align-items: center;
        background-color: #f8f9fa;
        padding: 15px 20px;
        border-radius: 8px;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.05);
    }

    /* Loading Overlay */
    .loading-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.85);
        z-index: 9999;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: opacity 0.3s ease;
    }

    .loading-content {
        background: #171f30;
        border-radius: 10px;
        padding: 30px;
        text-align: center;
        width: 90%;
        max-width: 500px;
        color: white;
        box-shadow: 0 15px 30px rgba(0, 0, 0, 0.5);
    }

    .spinner-container {
        margin-bottom: 20px;
    }

    .spinner {
        width: 70px;
        height: 70px;
        border: 5px solid transparent;
        border-top-color: #4CAF50;
        border-radius: 50%;
        animation: spin 1.5s linear infinite;
        margin: 0 auto;
        position: relative;
    }

    .spinner:before {
        content: "";
        position: absolute;
        top: 5px;
        left: 5px;
        right: 5px;
        bottom: 5px;
        border: 5px solid transparent;
        border-top-color: #8BC34A;
        border-radius: 50%;
        animation: spin 2s linear infinite;
    }

    .spinner:after {
        content: "";
        position: absolute;
        top: 15px;
        left: 15px;
        right: 15px;
        bottom: 15px;
        border: 5px solid transparent;
        border-top-color: #CDDC39;
        border-radius: 50%;
        animation: spin 1s linear infinite;
    }

    @keyframes spin {
        0% {
            transform: rotate(0deg);
        }

        100% {
            transform: rotate(360deg);
        }
    }

    .loading-text {
        font-size: 24px;
        color: #fff;
        margin-bottom: 15px;
    }

    .loading-description {
        color: #aaa;
        line-height: 1.5;
    }

    /* Dialog xác nhận */
    .delete-confirmation-dialog {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.8);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 9999;
        opacity: 1;
        visibility: visible;
        transition: all 0.3s ease;
    }

    .confirmation-content {
        background: #171f30;
        border-radius: 8px;
        width: 90%;
        max-width: 500px;
        padding: 30px;
        text-align: center;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.5);
        transform: translateY(0);
        transition: transform 0.4s ease;
    }

    .confirmation-title {
        font-size: 24px;
        margin-bottom: 20px;
    }

    .confirmation-message {
        color: #fff;
        margin-bottom: 30px;
        font-size: 16px;
        line-height: 1.6;
    }

    .selected-movies-list {
        max-height: 150px;
        overflow-y: auto;
        margin-bottom: 20px;
        text-align: left;
        padding: 10px;
        background: rgba(0, 0, 0, 0.2);
        border-radius: 4px;
    }

    .selected-movie-item {
        padding: 5px 0;
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        color: #e0e0e0;
    }

    /* Khi phim được chọn */
    .movie-card.selected {
        box-shadow: 0 0 0 3px #4CAF50;
        transform: translateY(-5px);
    }
</style>
<link rel="stylesheet" href="{{ asset('css/movie-grid.css') }}">
<script src="{{ asset('js/movie-grid.js') }}"></script>

<div class="container-fluid leech-container">
    <div class="dashboard-header">
        <h2 class="page-title"><i class="fas fa-film"></i> API Phim</h2>
        <p class="text-muted">Tìm kiếm và thêm phim vào hệ thống</p>
    </div>

    <!-- Bộ lọc và tìm kiếm nhanh -->
    <div class="search-filter-section mb-4">
        <div class="search-filter-container">
            <div class="filter-tabs">
                <button class="filter-tab active" data-filter="all"><i class="fas fa-list"></i> Tất cả</button>
                <button class="filter-tab" data-filter="newest"><i class="fas fa-calendar-alt"></i> Mới Nhất</button>
                <span class="result-badge">{{ count($resp['items']) }} kết quả</span>
            </div>

            <div class="search-action-container">
                <a href="{{ route('leech-search') }}" class="search-api-btn">
                    <i class="fas fa-search"></i> Tìm kiếm phim theo API
                </a>
            </div>
        </div>
    </div>

    <!-- Navigating API Pages -->
    <div class="api-page-navigation mb-3">
        <form action="{{ route('leech-movie') }}" method="GET"
            class="d-flex align-items-center justify-content-between">
            <div class="d-flex align-items-center">
                <label for="page-input" class="mr-2 mb-0">Trang:</label>
                <input type="number" id="page-input" name="page" class="form-control" style="width: 70px;"
                    value="{{ $page }}" min="1" max="{{ $totalPages }}">
                <span class="mx-2">/ {{ $totalPages }}</span>
                <button type="submit" class="btn btn-primary btn-sm">Go</button>
            </div>

            <div class="page-buttons">
                @if($page > 1)
                <a href="{{ route('leech-movie', ['page' => $page - 1]) }}" class="btn btn-outline-secondary btn-sm">
                    <i class="fas fa-chevron-left"></i> Trang trước
                </a>
                @endif

                @if($page < $totalPages) <a href="{{ route('leech-movie', ['page' => $page + 1]) }}"
                    class="btn btn-outline-secondary btn-sm">
                    Trang sau <i class="fas fa-chevron-right"></i>
                    </a>
                    @endif
            </div>
        </form>
    </div>

    <!-- Chức năng chọn nhiều và thêm hàng loạt -->
    <div class="batch-actions" style="margin-bottom: 20px;">
        <form method="POST" action="{{route('leech-store-multiple')}}" id="batch-add-form">
            @csrf
            <input type="hidden" name="selected_movies" id="selected-movies-input" value="">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <button type="button" id="select-all-btn" class="btn btn-sm btn-outline-primary">
                        <i class="fas fa-check-square"></i> Chọn tất cả
                    </button>
                    <button type="button" id="deselect-all-btn" class="btn btn-sm btn-outline-secondary">
                        <i class="fas fa-square"></i> Bỏ chọn tất cả
                    </button>
                    <span id="selected-count" class="ml-3 badge badge-info">0 phim được chọn</span>
                </div>
                <button type="button" id="batch-add-confirm-btn" class="btn btn-success" disabled>
                    <i class="fas fa-plus-circle"></i> Thêm phim đã chọn
                </button>
            </div>
        </form>
    </div>

    <!-- Loading Overlay -->
    <div id="loading-overlay" class="loading-overlay" style="display: none;">
        <div class="loading-content">
            <div class="spinner-container">
                <div class="spinner"></div>
            </div>
            <h3 class="loading-text">Đang xử lý...</h3>
            <p class="loading-description" id="loading-description">Vui lòng đợi trong khi hệ thống đang thêm phim.
                Quá trình này có thể mất vài phút.</p>
        </div>
    </div>

    <!-- Dialog xác nhận thêm nhiều phim -->
    <div class="delete-confirmation-dialog" id="addMoviesConfirmation" style="display: none;">
        <div class="confirmation-content">
            <h3 class="confirmation-title" style="color: #4CAF50;"><i class="fas fa-plus-circle"></i> Xác nhận thêm phim
            </h3>
            <p class="confirmation-message">Bạn sắp thêm <span id="movies-count">0</span> phim vào hệ thống.</p>

            <div class="selected-movies-list" id="selectedMoviesList"
                style="max-height: 200px; overflow-y: auto; margin-bottom: 20px;">
                <!-- Danh sách phim sẽ được thêm vào bằng JavaScript -->
            </div>

            <div class="confirmation-buttons">
                <button type="button" class="cancel-add-btn" id="cancelAddBtn"
                    style="background: #ccc; color: #333; border: none; padding: 10px 20px; border-radius: 4px; cursor: pointer;">Hủy
                    bỏ</button>
                <button type="button" class="confirm-add-btn" id="confirmAddBtn"
                    style="background: linear-gradient(135deg, #4CAF50, #2E7D32); color: white; border: none; padding: 10px 20px; border-radius: 4px; cursor: pointer; margin-left: 10px;">Thêm
                    phim</button>
            </div>
        </div>
    </div>

    <!-- Grid Layout thay thế Table -->
    <div class="movie-grid" id="movie-grid-container">
        @foreach ($resp['items'] as $key => $res)
        <div class="movie-card" data-id="{{ $res['_id'] }}" data-new="{{ $key < 10 ? 'true' : 'false' }}"
            data-slug="{{ $res['slug'] }}">
            <div class="movie-card-header">
                <div class="movie-image">
                    <img src="{{$res['poster_url']}}" alt="{{ $res['name'] }}" loading="lazy">
                    @if($key < 10) <div class="new-badge">Mới
                </div>
                @endif
                @if(isset($res['tmdb']) && $res['tmdb']['vote_average'] > 0)
                <div class="rating-badge">
                    <i class="fas fa-star"></i> {{ $res['tmdb']['vote_average'] }}
                </div>
                @endif
                <!-- Checkbox chọn phim -->
                <div class="select-movie-checkbox">
                    <input type="checkbox" class="movie-checkbox" data-slug="{{ $res['slug'] }}"
                        data-title="{{ $res['name'] }}" id="movie-{{ $res['_id'] }}">
                    <label for="movie-{{ $res['_id'] }}"></label>
                </div>
            </div>

            <h3 class="movie-title">{{ $res['name'] }}</h3>
            <p class="movie-original-title">{{ $res['origin_name'] }}</p>

            <div class="movie-meta">
                <div class="movie-year">
                    <i class="fas fa-calendar-alt"></i> {{ $res['year'] }}
                </div>
            </div>
        </div>

        <div class="movie-footer">
            <div class="action-buttons">
                <a href="{{route('leech-detail', $res['slug'])}}" class="action-btn detail-btn">
                    <i class="fas fa-info-circle"></i> Chi tiết
                </a>
                <a href="{{route('leech-episode', $res['slug'])}}" class="action-btn episode-btn">
                    <i class="fas fa-list"></i> Tập phim
                </a>
                <button class="action-btn preview-btn show-details-btn" data-modal-id="movieDetailModal-{{ $key }}">
                    <i class="fas fa-eye"></i> Xem nhanh
                </button>
                @php
                $movie = App\Models\Movie::where('slug', $res['slug'])->first();
                @endphp
                @if(!$movie)
                <div class="add-movie-button">
                    <form method="POST" action="{{route('leech-store', $res['slug'])}}" type="submit"
                        class="add-movie-form" style="width: 100%;">
                        @csrf
                        <button type="submit" class="action-btn add-btn" style="width: 100%;">
                            <i class="fas fa-plus-circle"></i> Thêm phim
                        </button>
                    </form>
                </div>
                @else
                <div class="destroy-movie-button" style="width: 100%;">
                    <form method="POST" action="{{route('movie.destroy', $movie->id)}}" type="submit"
                        class="destroy-movie-form" style="width: 100%;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="action-btn delete-btn" style="width: 100%;">
                            <i class="fas fa-trash"></i> Xóa phim
                        </button>
                    </form>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Modal Chi tiết phim -->
    <div class="custom-modal" id="movieDetailModal-{{ $key }}">
        <div class="modal-overlay"></div>
        <div class="modal-container">
            <div class="modal-header">
                <h3 class="modal-title">
                    <i class="fas fa-film"></i> {{ $res['name'] }}
                </h3>
                <button type="button" class="close-modal-btn" data-modal-id="movieDetailModal-{{ $key }}">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <div class="modal-body">
                <div class="movie-detail-grid">
                    <div class="movie-detail-images">
                        <div class="main-image">
                            <img src="{{$res['poster_url']}}" alt="{{ $res['name'] }}" loading="lazy">
                            @if(isset($res['tmdb']) && $res['tmdb']['vote_average'] > 0)
                            <div class="rating-badge">
                                <i class="fas fa-star"></i>
                                <span>{{ $res['tmdb']['vote_average'] }}</span>
                            </div>
                            @endif
                        </div>
                        <div class="thumbnail-image">
                            <img src="{{$res['thumb_url']}}" alt="{{ $res['name'] }}" loading="lazy">
                        </div>
                    </div>

                    <div class="movie-detail-info">
                        <div class="info-section">
                            <h4 class="info-title"><i class="fas fa-info-circle"></i> Thông tin cơ bản</h4>
                            <div class="info-content">
                                <div class="info-group">
                                    <span class="info-label">ID:</span>
                                    <span class="info-value">{{ $res['_id'] }}</span>
                                </div>
                                <div class="info-group">
                                    <span class="info-label">Tên phim:</span>
                                    <span class="info-value">{{ $res['name'] }}</span>
                                </div>
                                <div class="info-group">
                                    <span class="info-label">Tên tiếng Anh:</span>
                                    <span class="info-value">{{ $res['origin_name'] }}</span>
                                </div>
                                <div class="info-group">
                                    <span class="info-label">Slug:</span>
                                    <span class="info-value">{{ $res['slug'] }}</span>
                                </div>
                                <div class="info-group">
                                    <span class="info-label">Năm phát hành:</span>
                                    <span class="info-value">{{ $res['year'] }}</span>
                                </div>
                            </div>
                        </div>

                        @if(isset($res['tmdb']))
                        <div class="info-section">
                            <h4 class="info-title"><i class="fas fa-database"></i> TMDB</h4>
                            <div class="info-content tmdb-info">
                                <div class="info-group">
                                    <span class="info-label">Type:</span>
                                    <span class="info-value tmdb-type">{{ $res['tmdb']['type'] ?? 'N/A' }}</span>
                                </div>
                                <div class="info-group">
                                    <span class="info-label">ID:</span>
                                    <span class="info-value">{{ $res['tmdb']['id'] ?? 'N/A' }}</span>
                                </div>
                                <div class="info-group">
                                    <span class="info-label">Đánh giá:</span>
                                    <div class="rating-stars">
                                        @for($i = 1; $i <= 10; $i++) @if($i <=round($res['tmdb']['vote_average'] ?? 0))
                                            <i class="fas fa-star filled"></i>
                                            @else
                                            <i class="far fa-star"></i>
                                            @endif
                                            @endfor
                                            <span>{{ $res['tmdb']['vote_average'] ?? '0' }}/10 ({{
                                                $res['tmdb']['vote_count'] ?? '0' }} lượt)</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif

                        @if(isset($res['imdb']) && $res['imdb']['id'])
                        <div class="info-section">
                            <h4 class="info-title"><i class="fab fa-imdb"></i> IMDB</h4>
                            <div class="info-content">
                                <div class="info-group">
                                    <span class="info-label">ID:</span>
                                    <span class="info-value">{{ $res['imdb']['id'] }}</span>
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <a href="{{route('leech-detail', $res['slug'])}}" class="btn primary-btn">
                    <i class="fas fa-info-circle"></i> Chi tiết phim
                </a>
                <button type="button" class="btn secondary-btn close-modal-btn"
                    data-modal-id="movieDetailModal-{{ $key }}">
                    <i class="fas fa-times"></i> Đóng
                </button>
            </div>
        </div>
    </div>
    @endforeach
</div>

<div class="empty-results" style="display: none;">
    <i class="fas fa-search"></i>
    <h3>Không tìm thấy kết quả</h3>
    <p>Vui lòng thử lại với từ khóa khác</p>
</div>

<!-- Pagination Bottom -->
<div class="api-page-navigation mt-4 d-flex justify-content-center" id="api-page-navigation-bottom">
    <nav aria-label="Page navigation">
        <ul class="pagination">
            @if($page > 1)
            <li class="page-item">
                <a class="page-link" href="{{ route('leech-movie', ['page' => 1]) }}" aria-label="First">
                    <span aria-hidden="true">&laquo;&laquo;</span>
                </a>
            </li>
            <li class="page-item">
                <a class="page-link" href="{{ route('leech-movie', ['page' => $page - 1]) }}" aria-label="Previous">
                    <span aria-hidden="true">&laquo;</span>
                </a>
            </li>
            @endif

            @php
            $startPage = max(1, $page - 2);
            $endPage = min($totalPages, $page + 2);
            @endphp

            @if($startPage > 1)
            <li class="page-item disabled"><a class="page-link" href="#">...</a></li>
            @endif

            @for($i = $startPage; $i <= $endPage; $i++) <li class="page-item {{ $i == $page ? 'active' : '' }}">
                <a class="page-link" href="{{ route('leech-movie', ['page' => $i]) }}">{{ $i }}</a>
                </li>
                @endfor

                @if($endPage < $totalPages) <li class="page-item disabled"><a class="page-link" href="#">...</a></li>
                    @endif

                    @if($page < $totalPages) <li class="page-item">
                        <a class="page-link" href="{{ route('leech-movie', ['page' => $page + 1]) }}" aria-label="Next">
                            <span aria-hidden="true">&raquo;</span>
                        </a>
                        </li>
                        <li class="page-item">
                            <a class="page-link" href="{{ route('leech-movie', ['page' => $totalPages]) }}"
                                aria-label="Last">
                                <span aria-hidden="true">&raquo;&raquo;</span>
                            </a>
                        </li>
                        @endif
        </ul>
    </nav>
</div>
</div>



<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Thêm hiệu ứng khi trang tải xong
        document.querySelector('.search-filter-container').classList.add('animate-element');
        document.querySelector('.batch-actions').classList.add('animate-element');
        document.querySelector('.api-page-navigation').classList.add('animate-element');
        
        // Hiệu ứng khi hover vào các phần tử
        const interactiveElements = document.querySelectorAll('.search-api-btn, .filter-tab, .action-btn, #batch-add-confirm-btn, #select-all-btn, #deselect-all-btn');
        interactiveElements.forEach(element => {
            element.addEventListener('mouseenter', function() {
                this.style.transform = 'translateY(-3px)';
                this.style.boxShadow = '0 4px 15px rgba(92, 107, 192, 0.2)';
            });
            
            element.addEventListener('mouseleave', function() {
                this.style.transform = '';
                this.style.boxShadow = '';
            });
        });
    
        // Xử lý hiển thị modal
        const detailButtons = document.querySelectorAll('.show-details-btn');
        detailButtons.forEach(button => {
            button.addEventListener('click', function() {
                const modalId = this.getAttribute('data-modal-id');
                const modal = document.getElementById(modalId);
                if (modal) {
                    document.body.style.overflow = 'hidden'; // Ngăn cuộn trang khi modal hiển thị
                    modal.style.display = 'block';
                    
                    // Đóng modal khi click bên ngoài
                    modal.querySelector('.modal-overlay').addEventListener('click', function() {
                        closeModal(modal);
                    });
                }
            });
        });
        
        // Xử lý đóng modal khi nhấn nút đóng
        const closeButtons = document.querySelectorAll('.close-modal-btn');
        closeButtons.forEach(button => {
            button.addEventListener('click', function() {
                const modalId = this.getAttribute('data-modal-id');
                const modal = document.getElementById(modalId);
                if (modal) {
                    closeModal(modal);
                }
            });
        });
        
        // Đóng modal bằng phím ESC
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                const openModals = document.querySelectorAll('.custom-modal[style="display: block;"]');
                openModals.forEach(modal => {
                    closeModal(modal);
                });
            }
        });
        
        // Hàm đóng modal
        function closeModal(modal) {
            modal.style.display = 'none';
            document.body.style.overflow = ''; // Khôi phục cuộn trang
        }
        
        // Xử lý chọn nhiều phim
        const movieCheckboxes = document.querySelectorAll('.movie-checkbox');
        const selectAllBtn = document.getElementById('select-all-btn');
        const deselectAllBtn = document.getElementById('deselect-all-btn');
        const selectedCountDisplay = document.getElementById('selected-count');
        const batchAddBtn = document.getElementById('batch-add-confirm-btn');
        const selectedMoviesInput = document.getElementById('selected-movies-input');
        const batchAddForm = document.getElementById('batch-add-form');
        const loadingOverlay = document.getElementById('loading-overlay');
        const addMoviesConfirmation = document.getElementById('addMoviesConfirmation');
        const moviesCountEl = document.getElementById('movies-count');
        const selectedMoviesList = document.getElementById('selectedMoviesList');
        const confirmAddBtn = document.getElementById('confirmAddBtn');
        const cancelAddBtn = document.getElementById('cancelAddBtn');
        
        // Danh sách phim đã chọn
        let selectedMovies = [];
        
        // Hàm cập nhật số lượng phim đã chọn
        function updateSelectedCount() {
            const checkedBoxes = document.querySelectorAll('.movie-checkbox:checked');
            const selectedCount = checkedBoxes.length;
            
            // Debug log
            console.log('Số phim đã chọn:', selectedCount);
            
            // Cập nhật hiển thị
            selectedCountDisplay.textContent = selectedCount + ' phim được chọn';
            batchAddBtn.disabled = selectedCount === 0;
            
            // Thêm hiệu ứng khi có phim được chọn
            if (selectedCount > 0) {
                selectedCountDisplay.style.transform = 'scale(1.05)';
                selectedCountDisplay.style.boxShadow = '0 2px 10px rgba(76, 175, 80, 0.3)';
                setTimeout(() => {
                    selectedCountDisplay.style.transform = 'scale(1)';
                    selectedCountDisplay.style.boxShadow = 'none';
                }, 300);
            }
            
            // Cập nhật danh sách phim đã chọn
            selectedMovies = [];
            checkedBoxes.forEach(checkbox => {
                const slug = checkbox.getAttribute('data-slug');
                const title = checkbox.getAttribute('data-title') || 'Phim không có tiêu đề';
                if (slug) {
                    selectedMovies.push({slug: slug, title: title});
                    console.log('Đã thêm slug:', slug); // Debug log
                }
            });
            
            // Cập nhật giá trị input
            if (selectedMovies.length > 0) {
                const slugs = selectedMovies.map(movie => movie.slug);
                selectedMoviesInput.value = JSON.stringify(slugs);
            } else {
                selectedMoviesInput.value = "[]";
            }
            
            // Cập nhật card style
            document.querySelectorAll('.movie-card').forEach(card => {
                const checkbox = card.querySelector('.movie-checkbox');
                if (checkbox && checkbox.checked) {
                    card.classList.add('selected');
                } else {
                    card.classList.remove('selected');
                }
            });
            
            console.log('Giá trị input:', selectedMoviesInput.value); // Debug log
        }
        
        // Xử lý sự kiện thay đổi trên mỗi checkbox
        movieCheckboxes.forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                // Debug log
                console.log('Checkbox thay đổi:', this.id, 'Trạng thái:', this.checked);
                
                // Thêm hiệu ứng khi chọn phim
                const movieCard = this.closest('.movie-card');
                if (this.checked) {
                    movieCard.style.transform = 'translateY(-5px)';
                    movieCard.style.boxShadow = '0 8px 15px rgba(76, 175, 80, 0.3)';
                    setTimeout(() => {
                        movieCard.style.transform = '';
                        movieCard.style.boxShadow = '';
                    }, 300);
                }
                
                updateSelectedCount();
            });
        });
        
        // Chọn tất cả
        selectAllBtn.addEventListener('click', function() {
            let countSelected = 0;
            movieCheckboxes.forEach(checkbox => {
                // Chỉ chọn những phim chưa được thêm vào hệ thống
                const movieCard = checkbox.closest('.movie-card');
                const hasDeleteBtn = movieCard.querySelector('.destroy-movie-button');
                if (!hasDeleteBtn) {
                    checkbox.checked = true;
                    countSelected++;
                }
            });
            
            // Debug log
            console.log('Đã chọn tất cả phim mới:', countSelected);
            
            // Thêm hiệu ứng khi chọn tất cả
            if (countSelected > 0) {
                document.querySelectorAll('.movie-card').forEach((card, index) => {
                    if (!card.querySelector('.destroy-movie-button')) {
                        setTimeout(() => {
                            card.style.transform = 'translateY(-5px)';
                            card.style.boxShadow = '0 8px 15px rgba(76, 175, 80, 0.3)';
                            setTimeout(() => {
                                card.style.transform = '';
                                card.style.boxShadow = '';
                            }, 200);
                        }, index * 20); // Tạo hiệu ứng lần lượt
                    }
                });
            }
            
            updateSelectedCount();
        });
        
        // Bỏ chọn tất cả
        deselectAllBtn.addEventListener('click', function() {
            movieCheckboxes.forEach(checkbox => {
                checkbox.checked = false;
            });
            
            // Debug log
            console.log('Đã bỏ chọn tất cả');
            
            updateSelectedCount();
        });
        
        // Mở dialog xác nhận khi nhấn nút thêm phim
        batchAddBtn.addEventListener('click', function() {
            if (selectedMovies.length === 0) return;
            
            // Cập nhật nội dung dialog
            moviesCountEl.textContent = selectedMovies.length;
            
            // Hiển thị danh sách phim sẽ thêm
            selectedMoviesList.innerHTML = '';
            selectedMovies.forEach(movie => {
                const movieEl = document.createElement('div');
                movieEl.className = 'selected-movie-item';
                movieEl.innerHTML = `<i class="fas fa-film"></i> ${movie.title}`;
                selectedMoviesList.appendChild(movieEl);
            });
            
            // Hiển thị dialog
            addMoviesConfirmation.style.display = 'flex';
        });
        
        // Đóng dialog khi nhấn nút hủy
        cancelAddBtn.addEventListener('click', function() {
            addMoviesConfirmation.style.display = 'none';
        });
        
        // Thực hiện thêm phim khi xác nhận
        confirmAddBtn.addEventListener('click', function() {
            // Hiển thị loading overlay
            addMoviesConfirmation.style.display = 'none';
            document.getElementById('loading-description').textContent = 
                `Đang thêm ${selectedMovies.length} phim vào hệ thống. Quá trình này có thể mất vài phút, vui lòng không đóng trình duyệt.`;
            
            loadingOverlay.style.display = 'flex';
            
            // Hiệu ứng loading
            let dots = 0;
            const loadingText = document.querySelector('.loading-text');
            const originalText = loadingText.textContent;
            
            const loadingInterval = setInterval(() => {
                dots = (dots + 1) % 4;
                loadingText.textContent = originalText + '.'.repeat(dots);
            }, 500);
            
            // Lưu interval vào window để có thể clear khi cần
            window.loadingInterval = loadingInterval;
            
            // Submit form sau khi hiển thị loading
            setTimeout(() => {
                batchAddForm.submit();
            }, 500);
        });
        
        // Đóng dialog khi click ra ngoài
        addMoviesConfirmation.addEventListener('click', function(e) {
            if (e.target === addMoviesConfirmation) {
                addMoviesConfirmation.style.display = 'none';
            }
        });

        // Xử lý chuyển tab
        const filterTabs = document.querySelectorAll('.filter-tab');
        filterTabs.forEach(tab => {
            tab.addEventListener('click', function() {
                filterTabs.forEach(t => t.classList.remove('active'));
                this.classList.add('active');
                
                // Thêm hiệu ứng khi chuyển tab
                this.style.transform = 'translateY(-3px)';
                setTimeout(() => {
                    this.style.transform = '';
                }, 200);
                
                // Thêm logic lọc dữ liệu ở đây theo data-filter
                const filterType = this.getAttribute('data-filter');
                filterMoviesByType(filterType);
            });
        });
        
        // Khởi tạo ban đầu
        updateSelectedCount();
    });
</script>

@endsection