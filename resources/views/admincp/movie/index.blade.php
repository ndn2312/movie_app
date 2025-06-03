@extends('layouts.app')
@section('content')
<link rel="stylesheet" href="{{ asset('css/indexmovie.css') }}">
<link rel="stylesheet" href="{{ asset('css/movie-grid.css') }}">
<script src="{{asset('js/movie-grid.js')}}"></script>
<style>
    /* Checkbox styling */
    .select-checkbox {
        position: absolute;
        top: 10px;
        right: 10px;
        z-index: 10;
    }

    .select-checkbox input[type="checkbox"] {
        width: 22px;
        height: 22px;
        cursor: pointer;
        border: 2px solid #06f2e6;
        border-radius: 4px;
        background-color: rgba(0, 0, 0, 0.5);
        accent-color: #ff4a4a;
    }

    /* Hiệu ứng khi chọn */
    .movie-card.selected {
        box-shadow: 0 0 0 3px #ff4a4a;
        transform: translateY(-5px);
    }

    .dashboard-actions {
        display: flex;
        align-items: center;
    }

    /* Nút API cho tập phim */
    .add-episode-btn.api-btn {
        background: linear-gradient(135deg, #4a6bff, #2563eb);
        margin-left: 5px;
    }

    .add-episode-btn.api-btn:hover {
        background: linear-gradient(135deg, #3b5bef, #1d4ed8);
    }

    /* Confirmation dialog */
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
        opacity: 0;
        visibility: hidden;
        transition: all 0.3s ease;
    }

    .delete-confirmation-dialog.active {
        opacity: 1;
        visibility: visible;
    }

    .confirmation-content {
        background: #171f30;
        border-radius: 8px;
        width: 90%;
        max-width: 500px;
        padding: 30px;
        text-align: center;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.5);
        transform: translateY(30px);
        transition: transform 0.4s ease;
    }

    .delete-confirmation-dialog.active .confirmation-content {
        transform: translateY(0);
    }

    .confirmation-title {
        color: #ff4a4a;
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

    .confirmation-buttons {
        display: flex;
        justify-content: center;
        gap: 15px;
    }

    .confirm-delete-btn {
        background: linear-gradient(135deg, #ff4a4a, #d32f2f);
        color: white;
        border: none;
        padding: 10px 20px;
        border-radius: 4px;
        cursor: pointer;
        font-weight: bold;
        transition: all 0.3s ease;
    }

    .confirm-delete-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(255, 74, 74, 0.4);
    }

    .cancel-delete-btn {
        background: rgba(255, 255, 255, 0.2);
        color: white;
        border: none;
        padding: 10px 20px;
        border-radius: 4px;
        cursor: pointer;
        font-weight: bold;
        transition: all 0.3s ease;
    }

    .cancel-delete-btn:hover {
        background: rgba(255, 255, 255, 0.3);
    }

    /* Nút Chọn/Bỏ chọn tất cả */
    .select-btn {
        margin-left: 15px;
        border-radius: 5px;
        font-weight: 500;
        transition: all 0.3s ease;
    }

    #select-all-btn {
        background: linear-gradient(135deg, #06f2e6, #0b84d5);
        color: white;
    }

    #unselect-all-btn {
        background: linear-gradient(135deg, #9ca3af, #64748b);
        color: white;
    }

    #select-all-btn:hover {
        background: linear-gradient(135deg, #04c1e7, #0a6cad);
        transform: translateY(-2px);
    }

    #unselect-all-btn:hover {
        background: linear-gradient(135deg, #6b7280, #4b5563);
        transform: translateY(-2px);
    }

    #select-no-episodes-btn {
        background: linear-gradient(135deg, #f97316, #c2410c);
        color: white;
        font-weight: 500;
    }

    #select-no-episodes-btn:hover {
        background: linear-gradient(135deg, #ea580c, #9a3412);
        transform: translateY(-2px);
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
        border-top-color: #4a6bff;
        border-radius: 50%;
        animation: spin 1.5s linear infinite;
        margin: 0 auto;
    }

    .spinner:before {
        content: "";
        position: absolute;
        top: 5px;
        left: 5px;
        right: 5px;
        bottom: 5px;
        border: 5px solid transparent;
        border-top-color: #06f2e6;
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
        border-top-color: #ff4a4a;
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
</style>
<!-- Thêm vào phần head của layout -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        });
    });
</script>
<div class="container-fluid">
    <div class="dashboard-header">
        <h2 class="page-title"><i class="fas fa-film"></i> Quản Lý Phim</h2>
        <div class="dashboard-actions">
            <a href="{{route('movie.create')}}" class="button-custom button-add">
                <i class="fas fa-plus-circle"></i> Thêm Phim Mới
            </a>
            <button type="button" class="button-custom button-delete" id="delete-selected-btn"
                style="display: none; background: linear-gradient(135deg, #ff4a4a, #d32f2f); margin-left: 10px;">
                <i class="fas fa-trash"></i> Xóa phim đã chọn (<span id="selected-count">0</span>)
            </button>
            <button type="button" class="button-custom" id="add-episodes-btn"
                style="display: none; background: linear-gradient(135deg, #4a6bff, #2563eb); margin-left: 10px;">
                <i class="fas fa-cloud-download-alt"></i> Thêm tập API cho phim đã chọn (<span
                    id="episodes-selected-count">0</span>)
            </button>
        </div>
    </div>

    <!-- Thêm form xử lý thêm tập API cho nhiều phim -->
    <form id="add-episodes-multiple-form" action="{{ route('leech-episode-multiple') }}" method="POST"
        style="display: none;">
        @csrf
        <input type="hidden" name="movie_slugs" id="movie_slugs_to_add_episodes">
    </form>

    <!-- Form xóa nhiều phim -->
    <form id="delete-multiple-form" action="{{ route('movie.delete_multiple') }}" method="POST" style="display: none;">
        @csrf
        <input type="hidden" name="movie_ids" id="movie_ids_to_delete">
    </form>

    <!-- Loading Overlay -->
    <div id="loading-overlay" class="loading-overlay" style="display: none;">
        <div class="loading-content">
            <div class="spinner-container">
                <div class="spinner"></div>
            </div>
            <h3 class="loading-text">Đang xử lý...</h3>
            <p class="loading-description" id="loading-description">Vui lòng đợi trong khi hệ thống đang thêm tập phim.
                Quá trình này có thể mất vài phút.</p>
        </div>
    </div>

    <!-- Bộ lọc và tìm kiếm -->
    <div class="filter-section">
        <div class="search-box">
            <input type="text" id="movie-search" placeholder="Tìm kiếm phim...">
            <i class="fas fa-search search-icon"></i>
        </div>
        <div class="filter-buttons">
            <button class="filter-btn active" data-filter="all"><i class="fas fa-list"></i> Tất cả</button>
            <button class="filter-btn" data-filter="hot"><i class="fas fa-fire"></i> Phim Hot</button>
            <button class="filter-btn" data-filter="series"><i class="fas fa-tv"></i> Phim Bộ</button>
            <button class="filter-btn" data-filter="single"><i class="fas fa-film"></i> Phim Lẻ</button>
            <button class="filter-btn" data-filter="newest"><i class="fas fa-calendar-alt"></i> Mới Nhất</button>

            <button type="button" id="select-all-btn" class="filter-btn select-btn">
                <i class="fas fa-check-square"></i> Chọn tất cả
            </button>
            <button type="button" id="unselect-all-btn" class="filter-btn select-btn">
                <i class="fas fa-square"></i> Bỏ chọn tất cả
            </button>
            <button type="button" id="select-no-episodes-btn" class="filter-btn select-btn"
                style="background: linear-gradient(135deg, #f97316, #c2410c); color: white;">
                <i class="fas fa-tasks"></i> Chọn phim chưa có tập
            </button>
        </div>
    </div>

    <!-- Grid Layout thay thế DataTable -->
    <div class="movie-grid" id="movie-grid-container">
        @foreach($list as $key => $movie)
        <div class="movie-card" data-id="{{ $movie->id }}" id="movie-{{ $movie->id }}">
            <div class="movie-card-header">
                <div class="select-checkbox">
                    <input type="checkbox" class="movie-checkbox" data-id="{{ $movie->id }}"
                        data-title="{{ $movie->title }}" data-slug="{{ $movie->slug }}"
                        data-episode-count="{{ $movie->episode_count }}">
                </div>
                <div class="movie-image">
                    @php
                    $image_check = substr($movie->image,0,5);
                    @endphp
                    @if($image_check == 'https')
                    <img src="{{ $movie->image }}" alt="{{ $movie->title }}">
                    @else
                    <img src="{{ asset('uploads/movie/'.$movie->image) }}" alt="{{ $movie->title }}">
                    @endif
                    <div class="image-overlay">
                        <label for="file-{{$movie->id}}" class="image-upload-btn">
                            <i class="fas fa-camera"></i>
                        </label>
                        <input type="file" data-movie_id="{{$movie->id}}" data-movie_title="{{$movie->title}}"
                            name="image_choose" id="file-{{$movie->id}}" class="form-control-file file_image"
                            accept="image/*">
                    </div>
                </div>

                <h3 class="movie-title">{{ $movie->title }}</h3>

                <div class="movie-status">
                    <div class="status-badge {{ $movie->status == 1 ? 'active' : 'inactive' }}">
                        <i class="fas {{ $movie->status == 1 ? 'fa-eye' : 'fa-eye-slash' }}"></i>
                        <span>{{ $movie->status == 1 ? 'Hiển thị' : 'Ẩn' }}</span>
                    </div>

                    <div class="status-badge {{ $movie->phim_hot == 1 ? 'hot' : 'regular' }}">
                        <i class="fas {{ $movie->phim_hot == 1 ? 'fa-fire' : 'fa-snowflake' }}"></i>
                        <span>{{ $movie->phim_hot == 1 ? 'Hot' : 'Thường' }}</span>
                    </div>
                </div>
            </div>


            <div class="movie-info" id="movie-info-{{ $movie->id }}">
                <div class="info-group">
                    <div class="info-label"><i class="fas fa-list"></i> Danh mục:</div>
                    <div class="info-value category-select">
                        @if($movie->category)
                        {!! Form::select('category_id', $category, isset($movie) ? $movie->category->id : '',
                        ['class'=>'form-select category_choose','id'=>$movie->id,'title'=>$movie->title]) !!}
                        @else
                        @php
                        $deleted_category = \App\Models\Category::withTrashed()->find($movie->category_id);
                        @endphp
                        <span class="deleted-item">
                            <i class="fas fa-exclamation-triangle"></i>
                            Danh mục đã bị xóa: {{ $deleted_category ? $deleted_category->title : '' }}
                        </span>
                        @endif
                    </div>
                </div>

                <div class="info-group">
                    <div class="info-label"><i class="fas fa-globe"></i> Quốc gia:</div>
                    <div class="info-value country-select">
                        @if($movie->country)
                        {!! Form::select('country_id', $country, isset($movie) ? $movie->country->id : '',
                        ['class'=>'form-select country_choose','id'=>$movie->id,'title'=>$movie->title]) !!}
                        @else
                        @php
                        $deleted_country = \App\Models\Country::withTrashed()->find($movie->country_id);
                        @endphp
                        <span class="deleted-item">
                            <i class="fas fa-exclamation-triangle"></i>
                            Quốc gia đã bị xóa: {{ $deleted_country ? $deleted_country->title : '' }}
                        </span>
                        @endif
                    </div>
                </div>

                <div class="info-group">
                    <div class="info-label"><i class="fas fa-film"></i> Loại phim:</div>
                    <div class="info-value">
                        <select id="{{$movie->id}}" title="{{$movie->title}}" class="form-select thuocphim_choose">
                            <option value="phimle" {{ $movie->thuocphim == 'phimle' ? 'selected' : '' }}>Phim lẻ
                            </option>
                            <option value="phimbo" {{ $movie->thuocphim == 'phimbo' ? 'selected' : '' }}>Phim bộ
                            </option>
                        </select>
                    </div>
                </div>

                <div class="info-group">
                    <div class="info-label"><i class="fas fa-closed-captioning"></i> Phiên bản:</div>
                    <div class="info-value">
                        <select id="{{$movie->id}}" title="{{$movie->title}}" class="form-select phude_choose">
                            <option value="1" {{ $movie->phude == 1 ? 'selected' : '' }}>Thuyết minh</option>
                            <option value="0" {{ $movie->phude == 0 ? 'selected' : '' }}>Phụ đề</option>
                        </select>
                    </div>
                </div>

                <div class="info-group">
                    <div class="info-label"><i class="fas fa-video"></i> Độ phân giải:</div>
                    <div class="info-value">
                        <select id="{{$movie->id}}" title="{{$movie->title}}" class="form-select resolution_choose">
                            @php
                            $options = [
                            0 => 'HD',
                            1 => 'SD',
                            2 => 'HDCam',
                            3 => 'Cam',
                            4 => 'FullHD',
                            5 => 'Trailer'
                            ];
                            @endphp
                            @foreach($options as $key => $resolution)
                            <option {{ $movie->resolution == $key ? 'selected' : '' }} value="{{$key}}">
                                {{$resolution}}
                            </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="info-group">
                    <div class="info-label"><i class="fas fa-tags"></i> Thể loại:</div>
                    <div class="info-value genre-badges">
                        @foreach($movie->movie_genre as $gen)
                        <span class="genre-badge">{{ $gen->title }}</span>
                        @endforeach
                    </div>
                </div>

                @if(isset($movie->director) && !empty($movie->director))
                <div class="info-group">
                    <div class="info-label"><i class="fas fa-video"></i> Đạo diễn:</div>
                    <div class="info-value">{{ $movie->director }}</div>
                </div>
                @endif

                @if(isset($movie->actors) && !empty($movie->actors))
                <div class="info-group">
                    <div class="info-label"><i class="fas fa-users"></i> Diễn viên:</div>
                    <div class="info-value">{{ $movie->actors }}</div>
                </div>
                @endif

                <div class="info-group episodes-info">
                    <div class="info-label"><i class="fas fa-list-ol"></i> Tập phim:</div>
                    <div class="info-value">
                        <span class="episode-count">{{$movie->episode_count}}/{{ $movie->sotap }}
                            Tập</span>
                        <div class="mt-2">
                            <a href="{{route('add_episode',[$movie->id])}}" class="btn btn-sm btn-success">
                                <i class="fas fa-plus-circle"></i> Thêm tập
                            </a>
                            <a href="{{route('leech-episode', $movie->slug)}}" class="btn btn-sm btn-primary">
                                <i class="fas fa-cloud-download-alt"></i> API
                            </a>
                        </div>
                    </div>
                </div>

                <div class="info-group">
                    <div class="info-label"><i class="fas fa-eye"></i> Top view:</div>
                    <div class="info-value">
                        {!! Form::select('topview',[''=>'--Chọn Top View--', '0'=>'Ngày','1'=>'Tuần', '2'=>'Tháng'],
                        isset($movie->topview) && $movie->topview !== '' ? $movie->topview : '',
                        ['class'=>'form-select select-topview','id'=>$movie->id,'title'=>$movie->title]) !!}

                    </div>
                </div>

                <div class="info-row">
                    <div class="info-group half">
                        <div class="info-label"><i class="fas fa-calendar-alt"></i> Năm:</div>
                        <div class="info-value">
                            @php
                            $years = ['' => '--Chọn năm sản xuất--'];
                            for ($i = 2025; $i >= 2000; $i--) {
                            $years[$i] = $i;
                            }
                            @endphp

                            {!! Form::select('year', $years,
                            isset($movie->year) && $movie->year !== '' ? $movie->year : '',
                            ['class'=>'form-select select-year','id'=>$movie->id,'title'=>$movie->title]) !!}

                        </div>
                    </div>

                    <div class="info-group half">
                        <div class="info-label"><i class="fas fa-layer-group"></i> Season:</div>
                        <div class="info-value">
                            {!! Form::selectRange('season', 0, 20,
                            isset($movie->season) ? $movie->season : '',
                            ['class'=>'form-select select-season','id'=>$movie->id,'title'=>$movie->title]) !!}
                        </div>
                    </div>
                </div>
            </div>

            <div class="movie-footer">
                <div class="update-info">
                    <div class="update-time">
                        <i class="fas fa-clock"></i> Cập nhật:
                        @php
                        $updatedAt = \Carbon\Carbon::parse($movie->ngaycapnhat)->locale('vi');
                        echo $updatedAt->diffForHumans();
                        @endphp
                    </div>
                </div>

                <div class="action-buttons">
                    <a href="{{route('movie.edit', $movie->id)}}" class="edit-btn action-btn">
                        <i class="fas fa-edit"></i> Sửa
                    </a>

                    {!! Form::open(['method' => 'DELETE', 'route' => ['movie.destroy', $movie->id],
                    'class' => 'delete-form', 'data-movie-title' => $movie->title]) !!}
                    <button type="submit" class="delete-btn action-btn">
                        <i class="fas fa-trash"></i> Xóa
                    </button>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <div class="pagination-container">
        <!-- Pagination sẽ được thêm bằng JavaScript -->
    </div>
</div>

<!-- Dialog xác nhận xóa nhiều phim -->
<div class="delete-confirmation-dialog" id="deleteMultipleConfirmation">
    <div class="confirmation-content">
        <h3 class="confirmation-title"><i class="fas fa-exclamation-triangle"></i> Xác nhận xóa phim</h3>
        <p class="confirmation-message">Bạn sắp xóa <span id="movie-count">0</span> phim. Hành động này không thể hoàn
            tác.</p>

        <div class="selected-movies-list" id="selectedMoviesList">
            <!-- Danh sách phim sẽ được thêm vào bằng JavaScript -->
        </div>

        <div class="confirmation-buttons">
            <button type="button" class="cancel-delete-btn" id="cancelDeleteBtn">Hủy bỏ</button>
            <button type="button" class="confirm-delete-btn" id="confirmDeleteBtn">Xóa phim</button>
        </div>
    </div>
</div>

<!-- Dialog xác nhận thêm tập nhiều phim -->
<div class="delete-confirmation-dialog" id="addEpisodesConfirmation">
    <div class="confirmation-content">
        <h3 class="confirmation-title" style="color: #4a6bff;"><i class="fas fa-cloud-download-alt"></i> Xác nhận thêm
            tập API</h3>
        <p class="confirmation-message">Bạn sắp thêm tập API cho <span id="episodes-movie-count">0</span> phim. API sẽ
            tự động chọn server đầu tiên cho mỗi phim.</p>

        <div class="selected-movies-list" id="episodesSelectedMoviesList">
            <!-- Danh sách phim sẽ được thêm vào bằng JavaScript -->
        </div>

        <div class="confirmation-buttons">
            <button type="button" class="cancel-delete-btn" id="cancelAddEpisodesBtn">Hủy bỏ</button>
            <button type="button" class="confirm-delete-btn" id="confirmAddEpisodesBtn"
                style="background: linear-gradient(135deg, #4a6bff, #2563eb);">Thêm tập API</button>
        </div>
    </div>
</div>

@endsection

<script>
    document.addEventListener('DOMContentLoaded', function() {
    // Biến lưu trữ ID các phim đã chọn
    let selectedMovies = [];
    let selectedMovieSlugs = [];
    
    // Các phần tử DOM
    const checkboxes = document.querySelectorAll('.movie-checkbox');
    const deleteSelectedBtn = document.getElementById('delete-selected-btn');
    const addEpisodesBtn = document.getElementById('add-episodes-btn');
    const selectedCountEl = document.getElementById('selected-count');
    const episodesSelectedCountEl = document.getElementById('episodes-selected-count');
    const deleteForm = document.getElementById('delete-multiple-form');
    const addEpisodesForm = document.getElementById('add-episodes-multiple-form');
    const movieIdsInput = document.getElementById('movie_ids_to_delete');
    const movieSlugsInput = document.getElementById('movie_slugs_to_add_episodes');
    const selectAllBtn = document.getElementById('select-all-btn');
    const unselectAllBtn = document.getElementById('unselect-all-btn');
    const selectNoEpisodesBtn = document.getElementById('select-no-episodes-btn');
    const loadingOverlay = document.getElementById('loading-overlay');
    
    // Dialog xác nhận
    const deleteConfirmation = document.getElementById('deleteMultipleConfirmation');
    const addEpisodesConfirmation = document.getElementById('addEpisodesConfirmation');
    const movieCountEl = document.getElementById('movie-count');
    const episodesMovieCountEl = document.getElementById('episodes-movie-count');
    const selectedMoviesList = document.getElementById('selectedMoviesList');
    const episodesSelectedMoviesList = document.getElementById('episodesSelectedMoviesList');
    const confirmDeleteBtn = document.getElementById('confirmDeleteBtn');
    const confirmAddEpisodesBtn = document.getElementById('confirmAddEpisodesBtn');
    const cancelDeleteBtn = document.getElementById('cancelDeleteBtn');
    const cancelAddEpisodesBtn = document.getElementById('cancelAddEpisodesBtn');
    
    // Thêm sự kiện cho các checkbox
    checkboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            updateSelectedMovies(this);
        });
    });
    
    // Hàm cập nhật danh sách phim đã chọn
    function updateSelectedMovies(checkbox) {
        const movieId = checkbox.getAttribute('data-id');
        const movieTitle = checkbox.getAttribute('data-title');
        const movieSlug = checkbox.getAttribute('data-slug') || '';
        const movieCard = document.getElementById('movie-' + movieId);
        
        if (checkbox.checked) {
            // Thêm vào danh sách đã chọn
            selectedMovies.push({id: movieId, title: movieTitle, slug: movieSlug});
            movieCard.classList.add('selected');
        } else {
            // Xóa khỏi danh sách đã chọn
            selectedMovies = selectedMovies.filter(movie => movie.id !== movieId);
            movieCard.classList.remove('selected');
        }
        
        // Cập nhật số lượng đã chọn
        selectedCountEl.textContent = selectedMovies.length;
        episodesSelectedCountEl.textContent = selectedMovies.length;
        
        // Hiển thị/ẩn nút thao tác
        if (selectedMovies.length > 0) {
            deleteSelectedBtn.style.display = 'block';
            addEpisodesBtn.style.display = 'block';
        } else {
            deleteSelectedBtn.style.display = 'none';
            addEpisodesBtn.style.display = 'none';
        }
    }
    
    // Xử lý nút Chọn tất cả
    selectAllBtn.addEventListener('click', function() {
        checkboxes.forEach(checkbox => {
            if (!checkbox.checked) {
                checkbox.checked = true;
                updateSelectedMovies(checkbox);
            }
        });
    });
    
    // Xử lý nút Bỏ chọn tất cả
    unselectAllBtn.addEventListener('click', function() {
        checkboxes.forEach(checkbox => {
            if (checkbox.checked) {
                checkbox.checked = false;
                updateSelectedMovies(checkbox);
            }
        });
    });
    
    // Xử lý nút Chọn phim chưa có tập
    selectNoEpisodesBtn.addEventListener('click', function() {
        // Đầu tiên bỏ chọn tất cả
        checkboxes.forEach(checkbox => {
            if (checkbox.checked) {
                checkbox.checked = false;
                updateSelectedMovies(checkbox);
            }
        });
        
        // Sau đó chọn những phim chưa có tập
        checkboxes.forEach(checkbox => {
            const episodeCount = parseInt(checkbox.getAttribute('data-episode-count')) || 0;
            if (episodeCount === 0) {
                checkbox.checked = true;
                updateSelectedMovies(checkbox);
            }
        });
    });
    
    // Mở dialog xác nhận khi nhấn nút xóa
    deleteSelectedBtn.addEventListener('click', function() {
        if (selectedMovies.length === 0) return;
        
        // Cập nhật nội dung dialog
        movieCountEl.textContent = selectedMovies.length;
        
        // Hiển thị danh sách phim sẽ xóa
        selectedMoviesList.innerHTML = '';
        selectedMovies.forEach(movie => {
            const movieEl = document.createElement('div');
            movieEl.className = 'selected-movie-item';
            movieEl.innerHTML = `<i class="fas fa-film"></i> ${movie.title}`;
            selectedMoviesList.appendChild(movieEl);
        });
        
        // Hiển thị dialog
        deleteConfirmation.classList.add('active');
    });

    // Mở dialog xác nhận khi nhấn nút thêm tập API
    addEpisodesBtn.addEventListener('click', function() {
        if (selectedMovies.length === 0) return;
        
        // Cập nhật nội dung dialog
        episodesMovieCountEl.textContent = selectedMovies.length;
        
        // Hiển thị danh sách phim sẽ thêm tập
        episodesSelectedMoviesList.innerHTML = '';
        selectedMovies.forEach(movie => {
            const movieEl = document.createElement('div');
            movieEl.className = 'selected-movie-item';
            movieEl.innerHTML = `<i class="fas fa-film"></i> ${movie.title}`;
            episodesSelectedMoviesList.appendChild(movieEl);
        });
        
        // Hiển thị dialog
        addEpisodesConfirmation.classList.add('active');
    });
    
    // Đóng dialog khi nhấn nút hủy
    cancelDeleteBtn.addEventListener('click', function() {
        deleteConfirmation.classList.remove('active');
    });

    // Đóng dialog thêm tập khi nhấn nút hủy
    cancelAddEpisodesBtn.addEventListener('click', function() {
        addEpisodesConfirmation.classList.remove('active');
    });
    
    // Đóng dialog khi click ra ngoài
    deleteConfirmation.addEventListener('click', function(e) {
        if (e.target === deleteConfirmation) {
            deleteConfirmation.classList.remove('active');
        }
    });

    // Đóng dialog thêm tập khi click ra ngoài
    addEpisodesConfirmation.addEventListener('click', function(e) {
        if (e.target === addEpisodesConfirmation) {
            addEpisodesConfirmation.classList.remove('active');
        }
    });
    
    // Thực hiện xóa khi xác nhận
    confirmDeleteBtn.addEventListener('click', function() {
        // Chuẩn bị danh sách ID
        const movieIds = selectedMovies.map(movie => movie.id);
        movieIdsInput.value = JSON.stringify(movieIds);
        
        // Submit form
        deleteForm.submit();
    });

    // Thực hiện thêm tập khi xác nhận
    confirmAddEpisodesBtn.addEventListener('click', function() {
        // Chuẩn bị danh sách slug
        const movieSlugs = selectedMovies.map(movie => movie.slug).filter(slug => slug);
        movieSlugsInput.value = JSON.stringify(movieSlugs);
        
        // Hiển thị loading overlay
        if (loadingOverlay) {
            addEpisodesConfirmation.classList.remove('active'); // Đóng dialog xác nhận
            document.getElementById('loading-description').textContent = 
                `Đang thêm tập phim cho ${selectedMovies.length} phim. Quá trình này có thể mất vài phút, vui lòng không đóng trình duyệt.`;
            
            loadingOverlay.style.display = 'flex';
            loadingOverlay.style.opacity = '0';
            
            // Hiệu ứng fade in
            setTimeout(() => {
                loadingOverlay.style.opacity = '1';
            }, 10);
            
            // Thêm chỉ báo tiến trình
            let dots = 0;
            const loadingText = document.querySelector('.loading-text');
            const originalText = loadingText.textContent;
            
            const loadingInterval = setInterval(() => {
                dots = (dots + 1) % 4;
                loadingText.textContent = originalText + '.'.repeat(dots);
            }, 500);
            
            // Lưu interval vào window để có thể clear khi cần
            window.loadingInterval = loadingInterval;
        }
        
        // Submit form sau khi hiển thị loading
        setTimeout(() => {
            addEpisodesForm.submit();
        }, 500);
    });
});
</script>

<!-- Chú ý: JavaScript xử lý topview, year, season đã được định nghĩa trong file layouts/app.blade.php -->

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