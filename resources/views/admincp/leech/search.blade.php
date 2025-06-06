@extends('layouts.app')

@section('content')
<style>
    /* Filter options styles */
    .filter-options {
        background: #fff;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        margin-bottom: 20px;
    }

    .filter-row {
        display: flex;
        flex-wrap: wrap;
        gap: 15px;
    }

    .filter-group {
        flex: 1;
        min-width: 200px;
    }

    .filter-group label {
        display: block;
        margin-bottom: 5px;
        font-size: 13px;
        font-weight: 600;
        color: #495057;
    }

    .filter-actions {
        display: flex;
        align-items: flex-end;
        gap: 10px;
    }

    .filter-apply-btn,
    .filter-reset-btn {
        padding: 8px 16px;
        border-radius: 4px;
        font-size: 14px;
        font-weight: 500;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 5px;
        text-decoration: none;
    }

    .filter-apply-btn {
        background: #4a6bff;
        color: white;
        border: none;
    }

    .filter-reset-btn {
        background: #f8f9fa;
        color: #495057;
        border: 1px solid #ddd;
    }

    .form-select {
        width: 100%;
        padding: 8px 12px;
        border: 1px solid #ddd;
        border-radius: 4px;
        font-size: 14px;
        color: #495057;
    }

    /* Movie episode CSS */
    .movie-year .fas {
        color: #6c757d;
    }

    /* CSS cho trang chào mừng tìm kiếm */
    .welcome-search {
        text-align: center;
        padding: 50px 20px;
        background-color: #f8f9fa;
        border-radius: 10px;
        margin-top: 20px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
    }

    .welcome-icon {
        width: 80px;
        height: 80px;
        background-color: #e9ecef;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 20px;
    }

    .welcome-icon i {
        font-size: 36px;
        color: #4a6bff;
    }

    .welcome-search h3 {
        font-size: 28px;
        color: #333;
        margin-bottom: 15px;
    }

    .welcome-search p {
        font-size: 16px;
        color: #6c757d;
        margin-bottom: 30px;
    }

    .search-tips {
        max-width: 600px;
        margin: 0 auto;
        padding: 20px;
        background-color: white;
        border-radius: 8px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
    }

    .search-tips h4 {
        font-size: 18px;
        color: #343a40;
        margin-bottom: 15px;
    }

    .search-tips h4 i {
        color: #ffc107;
        margin-right: 5px;
    }

    .tips-list {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        justify-content: center;
    }

    .tip-btn {
        padding: 8px 16px;
        background-color: #e9ecef;
        border: none;
        border-radius: 20px;
        color: #495057;
        font-size: 14px;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .tip-btn:hover {
        background-color: #4a6bff;
        color: white;
        transform: translateY(-2px);
    }

    .empty-results {
        text-align: center;
        padding: 50px 20px;
        background-color: #f8f9fa;
        border-radius: 10px;
        margin-top: 20px;
    }

    .empty-results i {
        font-size: 48px;
        color: #6c757d;
        margin-bottom: 20px;
    }

    .empty-results h3 {
        font-size: 24px;
        color: #343a40;
        margin-bottom: 10px;
    }

    .empty-results p {
        font-size: 16px;
        color: #6c757d;
        margin-bottom: 20px;
    }
</style>
<link rel="stylesheet" href="{{ asset('css/movie-grid.css') }}">
<script src="{{ asset('js/movie-grid.js') }}"></script>

<div class="container-fluid leech-container">
    <div class="dashboard-header">
        <h2 class="page-title"><i class="fas fa-search"></i> Tìm kiếm phim</h2>
        <p class="text-muted">Tìm thấy {{ $totalItems }} kết quả cho "{{ $keyword }}"</p>
    </div>

    <!-- Bộ lọc và tìm kiếm -->
    <div class="filter-section">
        <div class="search-box">
            <form action="{{ route('leech-search') }}" method="GET">
                <input type="text" name="keyword" value="{{ $keyword }}" placeholder="Tìm kiếm phim..." required>
                <i class="fas fa-search search-icon"></i>
            </form>
        </div>
        <div class="filter-buttons">
            <a href="{{ route('leech-movie') }}" class="filter-btn">
                <i class="fas fa-arrow-left"></i> Quay lại
            </a>
            <button type="button" class="filter-btn" data-toggle="collapse" data-target="#filterOptions"
                aria-expanded="false">
                <i class="fas fa-filter"></i> Bộ lọc <i class="fas fa-angle-down ml-1"></i>
            </button>
            <span class="result-count">{{ $totalItems }} kết quả</span>
        </div>
    </div>

    <!-- Bộ lọc nâng cao (có thể mở rộng/thu gọn) -->
    <div class="collapse mb-4" id="filterOptions">
        <div class="filter-options">
            <form action="{{ route('leech-search') }}" method="GET" class="filter-form">
                <input type="hidden" name="keyword" value="{{ $keyword }}">
                <div class="filter-row">
                    <div class="filter-group">
                        <label>Ngôn ngữ</label>
                        <select name="sort_lang" class="form-select">
                            <option value="">Tất cả</option>
                            <option value="vietsub" {{ $sort_lang=='vietsub' ? 'selected' : '' }}>Vietsub</option>
                            <option value="thuyet-minh" {{ $sort_lang=='thuyet-minh' ? 'selected' : '' }}>Thuyết minh
                            </option>
                            <option value="long-tieng" {{ $sort_lang=='long-tieng' ? 'selected' : '' }}>Lồng tiếng
                            </option>
                        </select>
                    </div>
                    <div class="filter-group">
                        <label>Thể loại phim</label>
                        <select name="category" class="form-select">
                            <option value="">Tất cả</option>
                            @foreach($genres as $genre)
                            <option value="{{ $genre->slug }}" {{ $category==$genre->slug ? 'selected' : '' }}>
                                {{ $genre->title }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="filter-group">
                        <label>Quốc gia</label>
                        <select name="country" class="form-select">
                            <option value="">Tất cả</option>
                            @foreach($countries as $country_item)
                            <option value="{{ $country_item->slug }}" {{ $country==$country_item->slug ? 'selected' : ''
                                }}>
                                {{ $country_item->title }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="filter-group">
                        <label>Danh mục phim</label>
                        <select name="type" class="form-select">
                            <option value="">Tất cả</option>
                            <option value="single" {{ $type=='single' ? 'selected' : '' }}>Phim lẻ
                            </option>
                            <option value="series" {{ $type=='series' ? 'selected' : '' }}>Phim bộ
                            </option>
                            <option value="hoathinh" {{ $type=='hoathinh' ? 'selected' : '' }}>Hoạt hình
                            </option>
                            <option value="tvshows" {{ $type=='tvshows' ? 'selected' : '' }}>TV Shows
                            </option>
                        </select>
                    </div>
                    <div class="filter-group">
                        <label>Năm</label>
                        <select name="year" class="form-select">
                            <option value="">Tất cả</option>
                            @for($i = 2025; $i >= 2015; $i--)
                            <option value="{{ $i }}" {{ $year==$i ? 'selected' : '' }}>{{ $i }}</option>
                            @endfor
                        </select>
                    </div>
                </div>
                <div class="filter-row mt-3">
                    <div class="filter-group">
                        <label>Sắp xếp theo</label>
                        <select name="sort_field" class="form-select">
                            <option value="modified.time" {{ $sort_field=='modified.time' ? 'selected' : '' }}>Thời gian
                                cập nhật</option>
                            <option value="_id" {{ $sort_field=='_id' ? 'selected' : '' }}>ID</option>
                            <option value="year" {{ $sort_field=='year' ? 'selected' : '' }}>Năm phát hành</option>
                        </select>
                    </div>
                    <div class="filter-group">
                        <label>Kiểu sắp xếp</label>
                        <select name="sort_type" class="form-select">
                            <option value="desc" {{ $sort_type=='desc' ? 'selected' : '' }}>Giảm dần</option>
                            <option value="asc" {{ $sort_type=='asc' ? 'selected' : '' }}>Tăng dần</option>
                        </select>
                    </div>
                    <div class="filter-group filter-actions">
                        <button type="submit" class="filter-apply-btn">
                            <i class="fas fa-filter"></i> Áp dụng
                        </button>
                        <a href="{{ route('leech-search', ['keyword' => $keyword]) }}" class="filter-reset-btn">
                            <i class="fas fa-sync-alt"></i> Làm mới
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    @if(isset($resp['data']['items']) && count($resp['data']['items']) > 0)
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
                <button type="submit" id="batch-add-btn" class="btn btn-success" disabled>
                    <i class="fas fa-plus-circle"></i> Thêm phim đã chọn
                </button>
            </div>
        </form>
    </div>

    <!-- Grid Layout thay thế Table -->
    <div class="movie-grid" id="movie-grid-container">
        @foreach ($resp['data']['items'] as $key => $res)
        <div class="movie-card" data-id="{{ $res['_id'] }}" data-slug="{{ $res['slug'] }}">
            <div class="movie-card-header">
                <div class="movie-image">

                    @if(isset($res['tmdb']) && isset($res['tmdb']['vote_average']) && $res['tmdb']['vote_average'] > 0)
                    <div class="rating-badge">
                        <i class="fas fa-star"></i> {{ $res['tmdb']['vote_average'] }}
                    </div>
                    @endif
                    @if(isset($res['quality']) && !empty($res['quality']))
                    <div class="new-badge">{{ $res['quality'] }}</div>
                    @endif
                    <!-- Checkbox chọn phim -->
                    <div class="select-movie-checkbox">
                        <input type="checkbox" class="movie-checkbox" data-slug="{{ $res['slug'] }}"
                            id="movie-{{ $res['_id'] }}">
                        <label for="movie-{{ $res['_id'] }}"></label>
                    </div>
                </div>

                <h3 class="movie-title">{{ $res['name'] }}</h3>
                <p class="movie-original-title">{{ $res['origin_name'] }}</p>

                <div class="movie-meta">
                    <div class="movie-year">
                        <i class="fas fa-calendar-alt"></i> {{ $res['year'] }}
                    </div>
                    @if(isset($res['episode_current']) && !empty($res['episode_current']))
                    <div class="movie-year ml-2">
                        <i class="fas fa-film"></i> {{ $res['episode_current'] }}
                    </div>
                    @endif
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
                                <img src="{{ $res['poster_url'] ?? $res['thumb_url'] ?? ($res['backdrop_url'] ?? asset('images/no-image.jpg')) }}"
                                    alt="{{ $res['name'] }}" loading="lazy"
                                    onerror="this.onerror=null; this.src='{{ asset('images/no-image.jpg') }}';">
                                @if(isset($res['tmdb']) && isset($res['tmdb']['vote_average']) &&
                                $res['tmdb']['vote_average'] > 0)
                                <div class="rating-badge">
                                    <i class="fas fa-star"></i>
                                    <span>{{ $res['tmdb']['vote_average'] }}</span>
                                </div>
                                @endif
                            </div>
                            <div class="thumbnail-image">
                                <img src="{{ $res['thumb_url'] ?? $res['poster_url'] ?? ($res['backdrop_url'] ?? asset('images/no-image.jpg')) }}"
                                    alt="{{ $res['name'] }}" loading="lazy"
                                    onerror="this.onerror=null; this.src='{{ asset('images/no-image.jpg') }}';">
                            </div>
                        </div>

                        <div class="movie-detail-info">
                            <div class="info-section">
                                <h4 class="info-title"><i class="fas fa-info-circle"></i> Thông tin cơ bản</h4>
                                <div class="info-content">
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
                                    @if(isset($res['episode_current']) && !empty($res['episode_current']))
                                    <div class="info-group">
                                        <span class="info-label">Số tập:</span>
                                        <span class="info-value">{{ $res['episode_current'] }}</span>
                                    </div>
                                    @endif
                                </div>
                            </div>

                            @if(isset($res['tmdb']) && !empty($res['tmdb']))
                            <div class="info-section">
                                <h4 class="info-title"><i class="fas fa-database"></i> TMDB</h4>
                                <div class="info-content tmdb-info">
                                    @if(isset($res['tmdb']['type']))
                                    <div class="info-group">
                                        <span class="info-label">Type:</span>
                                        <span class="info-value tmdb-type">{{ $res['tmdb']['type'] }}</span>
                                    </div>
                                    @endif
                                    @if(isset($res['tmdb']['id']))
                                    <div class="info-group">
                                        <span class="info-label">ID:</span>
                                        <span class="info-value">{{ $res['tmdb']['id'] }}</span>
                                    </div>
                                    @endif
                                    @if(isset($res['tmdb']['vote_average']))
                                    <div class="info-group">
                                        <span class="info-label">Đánh giá:</span>
                                        <div class="rating-stars">
                                            @for($i = 1; $i <= 10; $i++) @if($i <=round($res['tmdb']['vote_average'] ??
                                                0)) <i class="fas fa-star filled"></i>
                                                @else
                                                <i class="far fa-star"></i>
                                                @endif
                                                @endfor
                                                <span>{{ $res['tmdb']['vote_average'] ?? '0' }}/10 ({{
                                                    $res['tmdb']['vote_count'] ?? '0' }} lượt)</span>
                                        </div>
                                    </div>
                                    @endif
                                </div>
                            </div>
                            @endif

                            @if(isset($res['imdb']) && isset($res['imdb']['id']) && !empty($res['imdb']['id']))
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

    <!-- Pagination Bottom -->
    <div class="pagination-container mt-4 d-flex justify-content-center">
        <nav aria-label="Page navigation">
            <ul class="pagination">
                @if($page > 1)
                <li class="page-item">
                    <a class="page-link"
                        href="{{ route('leech-search', array_merge(request()->except('page'), ['page' => 1])) }}"
                        aria-label="First">
                        <span aria-hidden="true">&laquo;&laquo;</span>
                    </a>
                </li>
                <li class="page-item">
                    <a class="page-link"
                        href="{{ route('leech-search', array_merge(request()->except('page'), ['page' => $page - 1])) }}"
                        aria-label="Previous">
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
                    <a class="page-link"
                        href="{{ route('leech-search', array_merge(request()->except('page'), ['page' => $i])) }}">{{ $i
                        }}</a>
                    </li>
                    @endfor

                    @if($endPage < $totalPages) <li class="page-item disabled"><a class="page-link" href="#">...</a>
                        </li>
                        @endif

                        @if($page < $totalPages) <li class="page-item">
                            <a class="page-link"
                                href="{{ route('leech-search', array_merge(request()->except('page'), ['page' => $page + 1])) }}"
                                aria-label="Next">
                                <span aria-hidden="true">&raquo;</span>
                            </a>
                            </li>
                            <li class="page-item">
                                <a class="page-link"
                                    href="{{ route('leech-search', array_merge(request()->except('page'), ['page' => $totalPages])) }}"
                                    aria-label="Last">
                                    <span aria-hidden="true">&raquo;&raquo;</span>
                                </a>
                            </li>
                            @endif
            </ul>
        </nav>
    </div>
    @elseif(empty($keyword))
    <!-- Hiển thị trang chào mừng khi chưa tìm kiếm -->
    <div class="welcome-search">
        <div class="welcome-icon">
            <i class="fas fa-search"></i>
        </div>
        <h3>Tìm kiếm phim theo API</h3>
        <p>Nhập từ khóa vào ô tìm kiếm phía trên để tìm phim từ nguồn API</p>
        <div class="search-tips">
            <h4><i class="fas fa-lightbulb"></i> Gợi ý tìm kiếm:</h4>
            <div class="tips-list">
                <button onclick="quickSearch('marvel')" class="tip-btn">Marvel</button>
                <button onclick="quickSearch('ác quỷ')" class="tip-btn">Ác quỷ</button>
                <button onclick="quickSearch('batman')" class="tip-btn">Batman</button>
                <button onclick="quickSearch('avatar')" class="tip-btn">Avatar</button>
                <button onclick="quickSearch('ngôi nhà')" class="tip-btn">Ngôi nhà</button>
                <button onclick="quickSearch('hàn quốc')" class="tip-btn">Hàn Quốc</button>
                <button onclick="quickSearch('ma')" class="tip-btn">Ma</button>
                <button onclick="quickSearch('chiến tranh')" class="tip-btn">Chiến tranh</button>
            </div>
        </div>
    </div>
    @else
    <div class="empty-results">
        <i class="fas fa-search"></i>
        <h3>Không tìm thấy kết quả</h3>
        <p>Không tìm thấy phim phù hợp với từ khóa "{{ $keyword }}"</p>
        <a href="{{ route('leech-movie') }}" class="btn btn-primary mt-3">
            <i class="fas fa-arrow-left"></i> Quay lại trang chính
        </a>
    </div>
    @endif
</div>



<script>
    document.addEventListener('DOMContentLoaded', function() {
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
        const batchAddBtn = document.getElementById('batch-add-btn');
        const selectedMoviesInput = document.getElementById('selected-movies-input');
        
        // Hàm cập nhật số lượng phim đã chọn
        function updateSelectedCount() {
            const selectedCount = document.querySelectorAll('.movie-checkbox:checked').length;
            selectedCountDisplay.textContent = selectedCount + ' phim được chọn';
            batchAddBtn.disabled = selectedCount === 0;
            
            // Cập nhật danh sách slug của phim đã chọn
            const selectedSlugs = [];
            document.querySelectorAll('.movie-checkbox:checked').forEach(checkbox => {
                const slug = checkbox.getAttribute('data-slug');
                if (slug) {
                    selectedSlugs.push(slug);
                }
            });
            
            // Đảm bảo giá trị JSON chắc chắn hợp lệ và không bị trống
            if (selectedSlugs.length > 0) {
            selectedMoviesInput.value = JSON.stringify(selectedSlugs);
            } else {
                selectedMoviesInput.value = "[]"; // JSON array trống là hợp lệ
            }
        }
        
        // Xử lý sự kiện thay đổi trên mỗi checkbox
        movieCheckboxes.forEach(checkbox => {
            checkbox.addEventListener('change', updateSelectedCount);
        });
        
        // Chọn tất cả
        selectAllBtn.addEventListener('click', function() {
            movieCheckboxes.forEach(checkbox => {
                // Chỉ chọn những phim chưa được thêm vào hệ thống
                const movieCard = checkbox.closest('.movie-card');
                const hasDeleteBtn = movieCard.querySelector('.destroy-movie-button');
                if (!hasDeleteBtn) {
                    checkbox.checked = true;
                }
            });
            updateSelectedCount();
        });
        
        // Bỏ chọn tất cả
        deselectAllBtn.addEventListener('click', function() {
            movieCheckboxes.forEach(checkbox => {
                checkbox.checked = false;
            });
            updateSelectedCount();
        });
        
        // Xử lý submit form thêm nhiều phim
        document.getElementById('batch-add-form')?.addEventListener('submit', function(event) {
            // Cập nhật lại giá trị input trước khi submit để đảm bảo
            updateSelectedCount();
            
            // Kiểm tra xem có phim nào được chọn không
            const selectedCount = document.querySelectorAll('.movie-checkbox:checked').length;
            
            if (selectedCount === 0) {
                event.preventDefault(); // Ngăn form submit nếu không có phim nào được chọn
                alert('Vui lòng chọn ít nhất một phim để thêm!');
                return false;
            }
            
            // Kiểm tra giá trị JSON có hợp lệ không
            try {
                const parsedValue = JSON.parse(selectedMoviesInput.value);
                if (!Array.isArray(parsedValue) || parsedValue.length === 0) {
                    event.preventDefault();
                    alert('Dữ liệu không hợp lệ, vui lòng thử lại!');
                    return false;
                }
            } catch (e) {
                event.preventDefault();
                alert('Dữ liệu không hợp lệ, vui lòng thử lại!');
                return false;
            }
            
            // Hiển thị thông báo đang xử lý
            batchAddBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Đang xử lý...';
            batchAddBtn.disabled = true;
            
            return true; // Cho phép form submit
        });
        
        // Khởi tạo ban đầu
        if (movieCheckboxes.length > 0) {
            updateSelectedCount();
        }
    });
    
    // Hàm xử lý tìm kiếm nhanh
    function quickSearch(keyword) {
        const form = document.querySelector('.search-box form');
        const inputElement = form.querySelector('input[name="keyword"]');
        inputElement.value = keyword;
        form.submit();
    }
</script>

@endsection