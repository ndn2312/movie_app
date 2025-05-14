@extends('layouts.app')

@section('content')
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

            <div class="search-container">
                <form action="{{ route('leech-search') }}" method="GET" class="search-form">
                    <div class="search-input-group">
                        <input type="text" name="keyword" class="search-input" placeholder="Tìm kiếm phim..." required>
                        <button type="submit" class="search-button">
                            <i class="fas fa-search"></i> TÌM KIẾM
                        </button>
                    </div>
                    <div class="advanced-filter-toggle">
                        <i class="fas fa-sliders-h"></i> Bộ lọc nâng cao
                    </div>
                </form>

                <div class="advanced-filters">
                    <div class="filter-row">
                        <div class="filter-group">
                            <label>Thể loại</label>
                            <select name="category" class="filter-select">
                                <option value="">Tất cả</option>
                                <option value="hanh-dong">Hành động</option>
                                <option value="tinh-cam">Tình cảm</option>
                                <option value="hai-huoc">Hài hước</option>
                                <option value="co-trang">Cổ trang</option>
                                <option value="kinh-di">Kinh dị</option>
                                <option value="tam-ly">Tâm lý</option>
                            </select>
                        </div>
                        <div class="filter-group">
                            <label>Quốc gia</label>
                            <select name="country" class="filter-select">
                                <option value="">Tất cả</option>
                                <option value="trung-quoc">Trung Quốc</option>
                                <option value="han-quoc">Hàn Quốc</option>
                                <option value="au-my">Âu Mỹ</option>
                                <option value="nhat-ban">Nhật Bản</option>
                                <option value="thai-lan">Thái Lan</option>
                            </select>
                        </div>
                        <div class="filter-group">
                            <label>Năm</label>
                            <select name="year" class="filter-select">
                                <option value="">Tất cả</option>
                                @for($i = 2024; $i >= 2015; $i--)
                                <option value="{{ $i }}">{{ $i }}</option>
                                @endfor
                            </select>
                        </div>
                    </div>
                    <div class="filter-actions">
                        <button type="button" class="filter-apply-btn">
                            <i class="fas fa-check"></i> Áp dụng
                        </button>
                        <button type="button" class="filter-reset-btn">
                            <i class="fas fa-redo"></i> Đặt lại
                        </button>
                        <button type="button" class="filter-close-btn">
                            <i class="fas fa-times"></i> Đóng
                        </button>
                    </div>
                </div>
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
                <button type="submit" id="batch-add-btn" class="btn btn-success" disabled>
                    <i class="fas fa-plus-circle"></i> Thêm phim đã chọn
                </button>
            </div>
        </form>
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
        const interactiveElements = document.querySelectorAll('.search-button, .filter-tab, .action-btn, #batch-add-btn, #select-all-btn, #deselect-all-btn');
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
        const batchAddBtn = document.getElementById('batch-add-btn');
        const selectedMoviesInput = document.getElementById('selected-movies-input');
        
        // Hàm cập nhật số lượng phim đã chọn
        function updateSelectedCount() {
            const selectedCount = document.querySelectorAll('.movie-checkbox:checked').length;
            selectedCountDisplay.textContent = selectedCount + ' phim được chọn';
            batchAddBtn.disabled = selectedCount === 0;
            
            // Thêm hiệu ứng khi có phim được chọn
            if (selectedCount > 0) {
                selectedCountDisplay.style.transform = 'scale(1.05)';
                selectedCountDisplay.style.boxShadow = '0 2px 10px rgba(79, 70, 229, 0.3)';
                setTimeout(() => {
                    selectedCountDisplay.style.transform = 'scale(1)';
                    selectedCountDisplay.style.boxShadow = 'none';
                }, 300);
            }
            
            // Cập nhật danh sách slug của phim đã chọn
            const selectedSlugs = [];
            document.querySelectorAll('.movie-checkbox:checked').forEach(checkbox => {
                selectedSlugs.push(checkbox.getAttribute('data-slug'));
            });
            selectedMoviesInput.value = JSON.stringify(selectedSlugs);
        }
        
        // Xử lý sự kiện thay đổi trên mỗi checkbox
        movieCheckboxes.forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                // Thêm hiệu ứng khi chọn phim
                const movieCard = this.closest('.movie-card');
                if (this.checked) {
                    movieCard.style.transform = 'translateY(-5px)';
                    movieCard.style.boxShadow = '0 8px 15px rgba(0, 0, 0, 0.1)';
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
            
            // Thêm hiệu ứng khi chọn tất cả
            if (countSelected > 0) {
                document.querySelectorAll('.movie-card').forEach((card, index) => {
                    setTimeout(() => {
                        card.style.transform = 'translateY(-5px)';
                        card.style.boxShadow = '0 8px 15px rgba(0, 0, 0, 0.1)';
                        setTimeout(() => {
                            card.style.transform = '';
                            card.style.boxShadow = '';
                        }, 200);
                    }, index * 20); // Tạo hiệu ứng lần lượt
                });
            }
            
            updateSelectedCount();
        });
        
        // Bỏ chọn tất cả
        deselectAllBtn.addEventListener('click', function() {
            movieCheckboxes.forEach(checkbox => {
                checkbox.checked = false;
            });
            updateSelectedCount();
        });
        
        // Khởi tạo ban đầu
        updateSelectedCount();

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
        
        // Hiển thị/ẩn bộ lọc nâng cao
        const advancedFilterToggle = document.querySelector('.advanced-filter-toggle');
        const advancedFilters = document.querySelector('.advanced-filters');
        if (advancedFilterToggle) {
            advancedFilterToggle.addEventListener('click', function() {
                if (advancedFilters.style.display === 'block') {
                    advancedFilters.style.display = 'none';
                } else {
                    advancedFilters.style.display = 'block';
                    // Thêm hiệu ứng khi hiển thị bộ lọc
                    advancedFilters.style.opacity = '0';
                    advancedFilters.style.transform = 'translateY(-10px)';
                    setTimeout(() => {
                        advancedFilters.style.opacity = '1';
                        advancedFilters.style.transform = 'translateY(0)';
                    }, 10);
                }
            });
        }
        
        // Đóng bộ lọc nâng cao
        const filterCloseBtn = document.querySelector('.filter-close-btn');
        if (filterCloseBtn) {
            filterCloseBtn.addEventListener('click', function() {
                advancedFilters.style.opacity = '0';
                advancedFilters.style.transform = 'translateY(-10px)';
                setTimeout(() => {
                    advancedFilters.style.display = 'none';
                }, 200);
            });
        }
        
        // Áp dụng bộ lọc nâng cao (cần thêm logic gửi form tìm kiếm với các bộ lọc)
        const filterApplyBtn = document.querySelector('.filter-apply-btn');
        if (filterApplyBtn) {
            filterApplyBtn.addEventListener('click', function() {
                // Hiệu ứng khi nhấn nút áp dụng
                this.style.transform = 'scale(0.95)';
                setTimeout(() => {
                    this.style.transform = '';
                }, 100);
                
                // Lấy giá trị từ các select và thêm vào form tìm kiếm
                const searchForm = document.querySelector('.search-form');
                const category = document.querySelector('select[name="category"]').value;
                const country = document.querySelector('select[name="country"]').value;
                const year = document.querySelector('select[name="year"]').value;
                
                // Tạo input ẩn để thêm vào form
                if (category) {
                    const categoryInput = document.createElement('input');
                    categoryInput.type = 'hidden';
                    categoryInput.name = 'category';
                    categoryInput.value = category;
                    searchForm.appendChild(categoryInput);
                }
                
                if (country) {
                    const countryInput = document.createElement('input');
                    countryInput.type = 'hidden';
                    countryInput.name = 'country';
                    countryInput.value = country;
                    searchForm.appendChild(countryInput);
                }
                
                if (year) {
                    const yearInput = document.createElement('input');
                    yearInput.type = 'hidden';
                    yearInput.name = 'year';
                    yearInput.value = year;
                    searchForm.appendChild(yearInput);
                }
                
                // Gửi form
                searchForm.submit();
            });
        }
        
        // Đặt lại bộ lọc nâng cao
        const filterResetBtn = document.querySelector('.filter-reset-btn');
        if (filterResetBtn) {
            filterResetBtn.addEventListener('click', function() {
                // Hiệu ứng khi nhấn nút đặt lại
                this.style.transform = 'rotate(360deg)';
                this.style.transition = 'transform 0.5s ease';
                setTimeout(() => {
                    this.style.transform = '';
                }, 500);
                
                document.querySelectorAll('.filter-select').forEach(select => {
                    select.selectedIndex = 0;
                });
            });
        }
        
        // Thêm hiệu ứng khi hover lên các phần tử
        const searchInput = document.querySelector('.search-input');
        if (searchInput) {
            searchInput.addEventListener('focus', function() {
                this.parentElement.style.boxShadow = '0 4px 12px rgba(92, 107, 192, 0.15)';
            });
            
            searchInput.addEventListener('blur', function() {
                this.parentElement.style.boxShadow = 'none';
            });
        }
    });
</script>

@endsection