@extends('layouts.app')

@section('content')
<style>
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
        <h2 class="page-title"><i class="fas fa-list"></i> Danh Sách Tổng Hợp API</h2>
        <p class="text-muted">Tìm kiếm và thêm phim từ nhiều nguồn khác nhau</p>
    </div>

    <!-- Bộ lọc và tìm kiếm nâng cao -->
    <div class="search-filter-section mb-4">
        <div class="search-filter-container">
            <div class="filter-tabs">
                @foreach($listTypes as $slug => $name)
                <a href="{{ route('api-list', ['type_list' => $slug]) }}"
                    class="filter-tab {{ $type_list == $slug ? 'active' : '' }}">
                    <i class="fas fa-film"></i> {{ $name }}
                </a>
                @endforeach
                <span class="result-badge">{{ $totalItems ?? 0 }} kết quả</span>
            </div>

            <form action="{{ route('api-list') }}" method="GET" class="mt-3">
                <input type="hidden" name="type_list" value="{{ $type_list }}">

                <div class="row">
                    <div class="col-md-2">
                        <div class="form-group">
                            <label>Sắp xếp theo</label>
                            <select name="sort_field" class="form-control">
                                <option value="modified.time" {{ $sort_field=='modified.time' ? 'selected' : '' }}>Thời
                                    gian cập nhật</option>
                                <option value="_id" {{ $sort_field=='_id' ? 'selected' : '' }}>ID phim</option>
                                <option value="year" {{ $sort_field=='year' ? 'selected' : '' }}>Năm phát hành</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-md-2">
                        <div class="form-group">
                            <label>Thứ tự</label>
                            <select name="sort_type" class="form-control">
                                <option value="desc" {{ $sort_type=='desc' ? 'selected' : '' }}>Giảm dần</option>
                                <option value="asc" {{ $sort_type=='asc' ? 'selected' : '' }}>Tăng dần</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-md-2">
                        <div class="form-group">
                            <label>Ngôn ngữ</label>
                            <select name="sort_lang" class="form-control">
                                <option value="">Tất cả</option>
                                <option value="vietsub" {{ $sort_lang=='vietsub' ? 'selected' : '' }}>Vietsub</option>
                                <option value="thuyet-minh" {{ $sort_lang=='thuyet-minh' ? 'selected' : '' }}>Thuyết
                                    minh</option>
                                <option value="long-tieng" {{ $sort_lang=='long-tieng' ? 'selected' : '' }}>Lồng tiếng
                                </option>
                            </select>
                        </div>
                    </div>

                    <div class="col-md-2">
                        <div class="form-group">
                            <label>Thể loại</label>
                            <select name="category" class="form-control">
                                <option value="">Tất cả</option>
                                @foreach($genres as $genre)
                                <option value="{{ $genre->slug }}" {{ $category==$genre->slug ? 'selected' : '' }}>
                                    {{ $genre->title }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-md-2">
                        <div class="form-group">
                            <label>Quốc gia</label>
                            <select name="country" class="form-control">
                                <option value="">Tất cả</option>
                                @foreach($countries as $country_item)
                                <option value="{{ $country_item->slug }}" {{ $country==$country_item->slug ? 'selected'
                                    : '' }}>
                                    {{ $country_item->title }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-md-2">
                        <div class="form-group">
                            <label>Năm</label>
                            <select name="year" class="form-control">
                                <option value="">Tất cả</option>
                                @for($i = 2025; $i >= 2015; $i--)
                                <option value="{{ $i }}" {{ $year==$i ? 'selected' : '' }}>{{ $i }}</option>
                                @endfor
                            </select>
                        </div>
                    </div>
                </div>

                <div class="row mt-2">
                    <div class="col-md-2">
                        <div class="form-group">
                            <label>Số kết quả</label>
                            <select name="limit" class="form-control">
                                <option value="24" {{ $limit==24 ? 'selected' : '' }}>24</option>
                                <option value="36" {{ $limit==36 ? 'selected' : '' }}>36</option>
                                <option value="48" {{ $limit==48 ? 'selected' : '' }}>48</option>
                                <option value="60" {{ $limit==60 ? 'selected' : '' }}>60</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-md-10">
                        <div class="form-group d-flex align-items-end h-100">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-filter"></i> Lọc kết quả
                            </button>
                            <a href="{{ route('api-list', ['type_list' => $type_list]) }}"
                                class="btn btn-secondary ml-2">
                                <i class="fas fa-sync"></i> Đặt lại
                            </a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
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

    <!-- Hiển thị thông báo nếu không có kết quả -->
    @if(empty($resp['data']['items']) || count($resp['data']['items']) == 0)
    <div class="alert alert-info">
        <i class="fas fa-info-circle"></i> Không tìm thấy kết quả phù hợp. Vui lòng thử lại với bộ lọc khác.
    </div>
    @else

    <!-- Grid Layout hiển thị kết quả -->
    <div class="movie-grid" id="movie-grid-container">
        @foreach ($resp['data']['items'] as $key => $res)
        <div class="movie-card" data-id="{{ $res['_id'] }}" data-slug="{{ $res['slug'] }}">
            <div class="movie-card-header">
                <div class="movie-image">
                    <img src="https://phimimg.com/{{$res['poster_url']}}" alt="{{ $res['name'] }}" loading="lazy">
                    @if(isset($res['quality']) && !empty($res['quality']))
                    <div class="quality-badge">{{ $res['quality'] }}</div>
                    @endif
                    @if(isset($res['lang']) && !empty($res['lang']))
                    <div class="lang-badge">{{ $res['lang'] }}</div>
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
                    @if(isset($res['episode_current']))
                    <div class="movie-episode">
                        <i class="fas fa-play-circle"></i> {{ $res['episode_current'] }}
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
        @endforeach
    </div>
    @endif

    <!-- Phân trang -->
    @if($totalPages > 1)
    <div class="api-page-navigation mt-4 d-flex justify-content-center" id="api-page-navigation-bottom">
        <nav aria-label="Page navigation">
            <ul class="pagination">
                @if($page > 1)
                <li class="page-item">
                    <a class="page-link"
                        href="{{ route('api-list', array_merge(request()->except(['page']), ['page' => 1])) }}"
                        aria-label="First">
                        <span aria-hidden="true">&laquo;&laquo;</span>
                    </a>
                </li>
                <li class="page-item">
                    <a class="page-link"
                        href="{{ route('api-list', array_merge(request()->except(['page']), ['page' => $page - 1])) }}"
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
                        href="{{ route('api-list', array_merge(request()->except(['page']), ['page' => $i])) }}">{{ $i
                        }}</a>
                    </li>
                    @endfor

                    @if($endPage < $totalPages) <li class="page-item disabled"><a class="page-link" href="#">...</a>
                        </li>
                        @endif

                        @if($page < $totalPages) <li class="page-item">
                            <a class="page-link"
                                href="{{ route('api-list', array_merge(request()->except(['page']), ['page' => $page + 1])) }}"
                                aria-label="Next">
                                <span aria-hidden="true">&raquo;</span>
                            </a>
                            </li>
                            <li class="page-item">
                                <a class="page-link"
                                    href="{{ route('api-list', array_merge(request()->except(['page']), ['page' => $totalPages])) }}"
                                    aria-label="Last">
                                    <span aria-hidden="true">&raquo;&raquo;</span>
                                </a>
                            </li>
                            @endif
            </ul>
        </nav>
    </div>
    @endif
</div>


<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Các phần tử DOM
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
            
            // Cập nhật danh sách slug của phim đã chọn
            selectedMovies = [];
            checkedBoxes.forEach(checkbox => {
                const slug = checkbox.getAttribute('data-slug');
                const title = checkbox.getAttribute('data-title') || 'Phim không có tiêu đề';
                if (slug) {
                    selectedMovies.push({slug: slug, title: title});
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
                const cardId = card.getAttribute('data-id');
                const checkbox = card.querySelector('.movie-checkbox');
                if (checkbox && checkbox.checked) {
                    card.classList.add('selected');
                } else {
                    card.classList.remove('selected');
                }
            });
        }
        
        // Xử lý sự kiện thay đổi trên mỗi checkbox
        movieCheckboxes.forEach(checkbox => {
            checkbox.addEventListener('change', function() {
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
        
        // Khởi tạo ban đầu
        updateSelectedCount();
    });
</script>

@endsection