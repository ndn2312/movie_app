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
        </div>
    </div>

    <!-- Form xóa nhiều phim -->
    <form id="delete-multiple-form" action="{{ route('movie.delete_multiple') }}" method="POST" style="display: none;">
        @csrf
        <input type="hidden" name="movie_ids" id="movie_ids_to_delete">
    </form>

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
        </div>
    </div>

    <!-- Grid Layout thay thế DataTable -->
    <div class="movie-grid" id="movie-grid-container">
        @foreach($list as $key => $movie)
        <div class="movie-card" data-id="{{ $movie->id }}" id="movie-{{ $movie->id }}">
            <div class="movie-card-header">
                <div class="select-checkbox">
                    <input type="checkbox" class="movie-checkbox" data-id="{{ $movie->id }}"
                        data-title="{{ $movie->title }}">
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
        <!-- Modal cho phim {{ $movie->title }} -->
        <div class="modal fade" id="movieModal-{{ $movie->id }}" tabindex="-1"
            aria-labelledby="movieModalLabel-{{ $movie->id }}" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="movieModalLabel-{{ $movie->id }}">
                            <i class="fas fa-film"></i> {{ $movie->title }}
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-4">
                                <img src="{{ asset('uploads/movie/'.$movie->image) }}" alt="{{ $movie->title }}"
                                    class="img-fluid rounded">
                            </div>
                            <div class="col-md-8">
                                <!-- Nội dung movie-info sẽ chuyển vào đây -->
                                <div class="movie-info-modal">
                                    <div class="info-group">
                                        <div class="info-label"><i class="fas fa-list"></i> Danh mục:</div>
                                        <div class="info-value">
                                            @if($movie->category)
                                            {{ $movie->category->title }}
                                            @else
                                            @php
                                            $deleted_category =
                                            \App\Models\Category::withTrashed()->find($movie->category_id);
                                            @endphp
                                            <span class="deleted-item">
                                                <i class="fas fa-exclamation-triangle"></i>
                                                Danh mục đã bị xóa: {{ $deleted_category ? $deleted_category->title : ''
                                                }}
                                            </span>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="info-group">
                                        <div class="info-label"><i class="fas fa-globe"></i> Quốc gia:</div>
                                        <div class="info-value">
                                            @if($movie->country)
                                            {{ $movie->country->title }}
                                            @else
                                            @php
                                            $deleted_country =
                                            \App\Models\Country::withTrashed()->find($movie->country_id);
                                            @endphp
                                            <span class="deleted-item">
                                                <i class="fas fa-exclamation-triangle"></i>
                                                Quốc gia đã bị xóa: {{ $deleted_country ? $deleted_country->title : ''
                                                }}
                                            </span>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="info-group">
                                        <div class="info-label"><i class="fas fa-film"></i> Loại phim:</div>
                                        <div class="info-value">
                                            {{ $movie->thuocphim == 'phimle' ? 'Phim lẻ' : 'Phim bộ' }}
                                        </div>
                                    </div>

                                    <div class="info-group">
                                        <div class="info-label"><i class="fas fa-closed-captioning"></i> Phiên bản:
                                        </div>
                                        <div class="info-value">
                                            {{ $movie->phude == 1 ? 'Thuyết minh' : 'Phụ đề' }}
                                        </div>
                                    </div>

                                    <div class="info-group">
                                        <div class="info-label"><i class="fas fa-video"></i> Độ phân giải:</div>
                                        <div class="info-value">
                                            @php
                                            $options = [
                                            0 => 'HD',
                                            1 => 'SD',
                                            2 => 'HDCam',
                                            3 => 'Cam',
                                            4 => 'FullHD',
                                            5 => 'Trailer'
                                            ];
                                            echo $options[$movie->resolution] ?? '';
                                            @endphp
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
                                                <a href="{{route('add_episode',[$movie->id])}}"
                                                    class="btn btn-sm btn-success">
                                                    <i class="fas fa-plus-circle"></i> Thêm tập
                                                </a>
                                                <a href="{{route('leech-episode', $movie->slug)}}"
                                                    class="btn btn-sm btn-primary">
                                                    <i class="fas fa-cloud-download-alt"></i> API
                                                </a>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="info-group">
                                        <div class="info-label"><i class="fas fa-eye"></i> Top view:</div>
                                        <div class="info-value">
                                            @if($movie->topview == 0)
                                            Ngày
                                            @elseif($movie->topview == 1)
                                            Tuần
                                            @elseif($movie->topview == 2)
                                            Tháng
                                            @endif
                                        </div>
                                    </div>

                                    <div class="info-row">
                                        <div class="info-group half">
                                            <div class="info-label"><i class="fas fa-calendar-alt"></i> Năm:</div>
                                            <div class="info-value">
                                                {{ $movie->year }}
                                            </div>
                                        </div>
                                        <div class="info-group half">
                                            <div class="info-label"><i class="fas fa-layer-group"></i> Season:</div>
                                            <div class="info-value">
                                                {{ $movie->season }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <a href="{{route('movie.edit', $movie->id)}}" class="btn btn-primary">
                            <i class="fas fa-edit"></i> Sửa phim
                        </a>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                    </div>
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

<script>
    document.addEventListener('DOMContentLoaded', function() {
    // Biến lưu trữ ID các phim đã chọn
    let selectedMovies = [];
    
    // Các phần tử DOM
    const checkboxes = document.querySelectorAll('.movie-checkbox');
    const deleteSelectedBtn = document.getElementById('delete-selected-btn');
    const selectedCountEl = document.getElementById('selected-count');
    const deleteForm = document.getElementById('delete-multiple-form');
    const movieIdsInput = document.getElementById('movie_ids_to_delete');
    
    // Dialog xác nhận
    const deleteConfirmation = document.getElementById('deleteMultipleConfirmation');
    const movieCountEl = document.getElementById('movie-count');
    const selectedMoviesList = document.getElementById('selectedMoviesList');
    const confirmDeleteBtn = document.getElementById('confirmDeleteBtn');
    const cancelDeleteBtn = document.getElementById('cancelDeleteBtn');
    
    // Thêm sự kiện cho các checkbox
    checkboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            const movieId = this.getAttribute('data-id');
            const movieTitle = this.getAttribute('data-title');
            const movieCard = document.getElementById('movie-' + movieId);
            
            if (this.checked) {
                // Thêm vào danh sách đã chọn
                selectedMovies.push({id: movieId, title: movieTitle});
                movieCard.classList.add('selected');
            } else {
                // Xóa khỏi danh sách đã chọn
                selectedMovies = selectedMovies.filter(movie => movie.id !== movieId);
                movieCard.classList.remove('selected');
            }
            
            // Cập nhật số lượng đã chọn
            selectedCountEl.textContent = selectedMovies.length;
            
            // Hiển thị/ẩn nút xóa
            if (selectedMovies.length > 0) {
                deleteSelectedBtn.style.display = 'block';
            } else {
                deleteSelectedBtn.style.display = 'none';
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
    
    // Đóng dialog khi nhấn nút hủy
    cancelDeleteBtn.addEventListener('click', function() {
        deleteConfirmation.classList.remove('active');
    });
    
    // Đóng dialog khi click ra ngoài
    deleteConfirmation.addEventListener('click', function(e) {
        if (e.target === deleteConfirmation) {
            deleteConfirmation.classList.remove('active');
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
});
</script>

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