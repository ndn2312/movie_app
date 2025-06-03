@extends('layout')
@section('content')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.32/dist/sweetalert2.min.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.32/dist/sweetalert2.all.min.js"></script>

<style>
    /* Banner section */
    .favorites-banner {
        position: relative;
        width: 100%;
        height: 200px;
        background-image: url('https://images.unsplash.com/photo-1536440136628-849c177e76a1?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1025&q=80');
        background-size: cover;
        background-position: center;
        border-radius: 8px;
        margin-bottom: 30px;
        overflow: hidden;
    }

    .banner-overlay {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: linear-gradient(to right, rgba(15, 23, 42, 0.9), rgba(15, 23, 42, 0.7));
    }

    .banner-content {
        position: relative;
        z-index: 2;
        padding: 40px;
        color: #fff;
        text-align: center;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        height: 100%;
    }

    .heart-icon {
        font-size: 3.5rem;
        color: #ff5e94;
        margin-bottom: 15px;
    }

    .banner-title {
        font-size: 2.8rem;
        font-weight: 700;
        margin-bottom: 15px;
        text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
        letter-spacing: 0.5px;
    }

    .banner-description {
        font-size: 1.3rem;
        opacity: 0.9;
        max-width: 800px;
        margin: 0 auto;
    }

    /* Stats bar */
    .favorites-stats {
        display: flex;
        justify-content: center;
        gap: 40px;
        margin-bottom: 25px;
        padding: 18px 20px;
        background: rgba(15, 23, 42, 0.5);
        border-radius: 8px;
    }

    .stat-item {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .stat-item i {
        font-size: 2.2rem;
        color: #06f2e6;
    }

    .stat-details {
        display: flex;
        flex-direction: column;
    }

    .stat-value {
        font-size: 1.4rem;
        font-weight: 700;
        color: #fff;
    }

    .stat-label {
        font-size: 1rem;
        color: #ddd;
    }

    /* Section title */
    .section-bar .section-title {
        font-size: 1.8rem;
        font-weight: 700;
        letter-spacing: 0.5px;
    }

    /* Sorting options */
    .movie-sorting {
        display: flex;
        align-items: center;
        margin-bottom: 25px;
        padding: 16px;
        background: rgba(15, 23, 42, 0.3);
        border-radius: 8px;
    }

    /* Grid layout và khoảng cách giữa các phim */
    .halim_box {
        display: flex;
        flex-wrap: wrap;
        margin: 0 -15px;
        /* Âm để bù trừ padding của thumb */
    }

    .thumb {
        padding: 0 15px;
        margin-bottom: 30px;
        /* Tăng khoảng cách giữa các hàng */
    }

    .halim-item {
        background: rgba(15, 23, 42, 0.3);
        border-radius: 8px;
        overflow: hidden;
        transition: all 0.3s ease;
        height: 100%;
        display: flex;
        flex-direction: column;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
    }

    .halim-item:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
    }

    .halim-thumb {
        position: relative;
        margin-bottom: 10px;
        overflow: hidden;
        display: block;
    }

    .halim-thumb figure {
        margin: 0;
        position: relative;
        padding-top: 140%;
        /* Tỷ lệ khung hình cho ảnh */
        overflow: hidden;
    }

    .halim-thumb figure img {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.5s ease;
    }

    .halim-thumb:hover figure img {
        transform: scale(1.05);
    }

    .sorting-label {
        color: #ddd;
        margin-right: 20px;
        font-size: 1.1rem;
        font-weight: 600;
    }

    .sort-option {
        padding: 8px 16px;
        margin-right: 12px;
        border-radius: 20px;
        color: #fff;
        background: rgba(15, 23, 42, 0.5);
        transition: all 0.3s ease;
        font-size: 1.05rem;
        font-weight: 500;
    }

    .sort-option:hover,
    .sort-option.active {
        background: #06f2e6;
        color: #111;
        text-decoration: none;
    }

    /* Movie titles */
    .halim-post-title-box {
        z-index: 3;
        position: absolute;
        bottom: 0;
        left: 0;
        width: 100%;
        padding: 15px;
    }

    .halim-post-title .entry-title {
        font-size: 1.15rem !important;
        font-weight: 600 !important;
        line-height: 1.4 !important;
        margin-bottom: 5px !important;
        color: #fff !important;
        text-shadow: 0 1px 3px rgba(0, 0, 0, 0.7) !important;
    }

    .halim-post-title .entry-title:hover {
        color: #06f2e6 !important;
    }

    .halim-post-title .original_title {
        font-size: 0.95rem !important;
        color: rgba(255, 255, 255, 0.7) !important;
        text-shadow: 0 1px 2px rgba(0, 0, 0, 0.5) !important;
    }

    /* Action buttons for movie items */
    .movie-actions {
        padding: 12px;
        text-align: center;
        margin-top: auto;
        /* Để nút luôn ở dưới cùng */
    }

    .action-buttons {
        display: flex;
        gap: 10px;
        margin-bottom: 5px;
    }

    .btn-watch {
        flex: 1;
        display: block;
        padding: 18px 12px;
        background: linear-gradient(135deg, #ff6b6b 0%, #ee0979 100%);
        color: #fff;
        border-radius: 5px;
        text-align: center;
        transition: all 0.3s ease;
        text-decoration: none;
        font-weight: 600;
        font-size: 1rem;
        letter-spacing: 0.3px;
    }

    .btn-watch:hover {
        background: linear-gradient(135deg, #ee0979 0%, #ff6b6b 100%);
        text-decoration: none;
        color: #fff;
        transform: translateY(-2px);
    }

    .btn-remove {
        width: 42px;
        height: 42px;
        background: rgba(220, 53, 69, 0.2);
        color: #dc3545;
        border: none;
        border-radius: 5px;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s ease;
        font-size: 1.1rem;
    }

    .btn-remove:hover {
        background: #dc3545;
        color: #fff;
        transform: translateY(-2px);
    }

    /* Pagination */
    .pagination-container {
        margin-top: 40px;
        margin-bottom: 20px;
    }

    .pagination {
        justify-content: center;
    }

    .pagination .page-item .page-link {
        background-color: #1f2d3d;
        border-color: #1f2d3d;
        color: #fff;
        border-radius: 5px;
        margin: 0 3px;
        font-size: 1.05rem;
        padding: 8px 16px;
    }

    .pagination .page-item.active .page-link {
        background-color: #06f2e6;
        border-color: #06f2e6;
        color: #111;
        font-weight: 600;
    }

    .pagination .page-item .page-link:hover {
        background-color: #06f2e6;
        border-color: #06f2e6;
        color: #111;
    }

    /* Empty state */
    .empty-favorites {
        padding: 70px 20px;
        text-align: center;
        background: rgba(15, 23, 42, 0.3);
        border-radius: 8px;
    }

    .empty-content {
        max-width: 600px;
        margin: 0 auto;
    }

    .empty-favorites i {
        font-size: 5rem;
        color: #ff5e94;
        margin-bottom: 25px;
        opacity: 0.8;
    }

    .empty-favorites h3 {
        font-size: 2.2rem;
        font-weight: 700;
        margin-bottom: 18px;
        color: #fff;
        letter-spacing: 0.5px;
    }

    .empty-favorites p {
        color: #ddd;
        margin-bottom: 35px;
        font-size: 1.3rem;
        line-height: 1.6;
    }

    .discover-btn {
        display: inline-block;
        padding: 14px 30px;
        background: linear-gradient(135deg, #00c6ff 0%, #0072ff 100%);
        color: #fff;
        border-radius: 30px;
        font-weight: 600;
        font-size: 1.15rem;
        transition: all 0.3s ease;
        text-decoration: none;
        box-shadow: 0 4px 20px rgba(0, 114, 255, 0.3);
        letter-spacing: 0.5px;
    }

    .discover-btn:hover {
        transform: translateY(-3px);
        box-shadow: 0 6px 25px rgba(0, 114, 255, 0.5);
        text-decoration: none;
        color: #fff;
    }

    .discover-btn i {
        margin-right: 10px;
    }

    /* Status and episode tags */
    .status1 .tag-base,
    .episode1 .tag-base {
        font-size: 11px;
        font-weight: 600;
        padding: 5px 8px;
        border-radius: 4px;
        display: inline-block;
        margin-bottom: 5px;
        text-shadow: 0 1px 2px rgba(0, 0, 0, 0.5);
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.3);
        transition: all 0.3s ease;
        transform: translateY(0);
        max-width: 100%;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        letter-spacing: 0.2px;
    }

    .halim-thumb:hover .tag-base {
        transform: translateY(-2px);
        box-shadow: 0 3px 6px rgba(0, 0, 0, 0.3);
    }

    /* Màu sắc cho các tag */
    .hd-tag {
        background-color: #2196F3 !important;
        color: white !important;
    }

    .sd-tag {
        background-color: #4CAF50 !important;
        color: white !important;
    }

    .hdcam-tag {
        background-color: #FF9800 !important;
        color: white !important;
    }

    .cam-tag {
        background-color: #F44336 !important;
        color: white !important;
    }

    .fullhd-tag {
        background-color: #9C27B0 !important;
        color: white !important;
    }

    .trailer-tag {
        background-color: #607D8B !important;
        color: white !important;
    }

    .phude-tag {
        background-color: #E91E63 !important;
        color: white !important;
    }

    .thuyetminh-tag {
        background-color: #00BCD4 !important;
        color: white !important;
    }

    .ss-tag {
        background-color: #FF5722 !important;
        color: white !important;
    }

    /* Vị trí của tag status và episode */
    .status1 {
        position: absolute;
        top: 8px;
        left: 8px;
        z-index: 5;
        max-width: 45%;
        /* Giới hạn chiều rộng */
    }

    .episode1 {
        position: absolute;
        top: 8px;
        right: 20px;
        z-index: 5;
        display: flex;
        flex-direction: column;
        align-items: flex-end;
        gap: 5px;
        max-width: 45%;
        /* Giới hạn chiều rộng */
    }

    .episode1 .tag-base {
        padding: 5px 8px;
        font-size: 11px;
        margin-right: 0;
    }

    /* Play button */
    .play-button {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%) scale(0.8);
        width: 60px;
        height: 60px;
        background: rgba(255, 255, 255, 0.2);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        opacity: 0;
        transition: all 0.3s ease;
        z-index: 10;
        box-shadow: 0 0 20px rgba(255, 255, 255, 0.3);
    }

    .play-button i {
        font-size: 24px;
        color: #fff;
    }

    .halim-thumb:hover .play-button {
        opacity: 1;
        transform: translate(-50%, -50%) scale(1);
    }

    /* Overlay */
    .icon_overlay {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: linear-gradient(to bottom, rgba(0, 0, 0, 0) 0%, rgba(0, 0, 0, 0.7) 80%, rgba(0, 0, 0, 0.9) 100%);
        opacity: 0.7;
        transition: all 0.3s ease;
        z-index: 2;
    }

    .halim-thumb:hover .icon_overlay {
        opacity: 0.9;
    }

    /* Animation */
    .pulse {
        animation: pulse-heart 2s infinite;
    }

    @keyframes pulse-heart {
        0% {
            transform: scale(1);
        }

        50% {
            transform: scale(1.1);
        }

        100% {
            transform: scale(1);
        }
    }

    /* Responsive fixes */
    @media (max-width: 991px) {
        .action-buttons {
            flex-direction: row;
        }

        .halim_box {
            margin: 0 -10px;
        }

        .thumb {
            padding: 0 10px;
            margin-bottom: 20px;
        }
    }

    @media (max-width: 768px) {
        .banner-title {
            font-size: 2.2rem;
        }

        .banner-description {
            font-size: 1.1rem;
        }

        .favorites-stats {
            flex-direction: column;
            gap: 15px;
            align-items: center;
        }

        .movie-sorting {
            flex-wrap: wrap;
        }

        .sort-option {
            margin-bottom: 10px;
        }

        .empty-favorites h3 {
            font-size: 1.8rem;
        }

        .empty-favorites p {
            font-size: 1.1rem;
        }

        .thumb {
            width: 50%;
            /* 2 phim mỗi hàng trên tablet */
        }
    }

    @media (max-width: 576px) {
        .thumb {
            width: 100%;
            /* 1 phim mỗi hàng trên mobile */
            padding: 0 15px;
            margin-bottom: 25px;
        }

        .halim-post-title .entry-title {
            font-size: 1.3rem !important;
        }

        .halim-post-title .original_title {
            font-size: 1rem !important;
        }

        .banner-title {
            font-size: 1.8rem;
        }

        .banner-description {
            font-size: 1rem;
        }

        .section-bar .section-title {
            font-size: 1.5rem;
        }

        .movie-sorting {
            flex-direction: column;
            align-items: flex-start;
        }

        .sorting-label {
            margin-bottom: 10px;
        }

        .sort-option {
            margin-right: 5px;
            padding: 6px 12px;
            font-size: 0.95rem;
        }
    }

    /* Tùy chỉnh style cho SweetAlert2 */
    .swal-title {
        font-size: 2rem !important;
        font-weight: 700 !important;
        margin-bottom: 20px !important;
    }

    .swal-text {
        font-size: 1.4rem !important;
        margin: 15px 0 !important;
        line-height: 1.5 !important;
    }

    .swal-text b {
        color: #d33;
        font-weight: 700;
    }

    .swal-confirm {
        font-weight: 600 !important;
        font-size: 1.2rem !important;
        padding: 12px 30px !important;
    }

    .swal-cancel {
        font-weight: 600 !important;
        font-size: 1.2rem !important;
        padding: 12px 30px !important;
    }

    /* Tăng kích thước bảng thông báo */
    .swal2-popup {
        width: auto !important;
        min-width: 32em !important;
        max-width: 95% !important;
        padding: 2em !important;
        font-size: 16px !important;
        border-radius: 12px !important;
    }

    .swal2-icon {
        font-size: 16px !important;
        margin: 1.5em auto 0.8em !important;
        height: 5em !important;
        width: 5em !important;
    }

    /* Responsive cho màn hình nhỏ */
    @media (max-width: 768px) {
        .swal2-popup {
            min-width: 300px !important;
            padding: 1.5em !important;
        }

        .swal-title {
            font-size: 1.6rem !important;
        }

        .swal-text {
            font-size: 1.2rem !important;
        }

        .swal-confirm,
        .swal-cancel {
            font-size: 1.1rem !important;
            padding: 10px 20px !important;
        }

        .swal2-icon {
            height: 4em !important;
            width: 4em !important;
        }
    }
</style>
<div class="row container" id="wrapper">
    <!-- Banner section -->
    <div class="favorites-banner">
        <div class="banner-overlay"></div>
        <div class="banner-content">
            <i class="fas fa-heart heart-icon pulse"></i>
            <h1 class="banner-title">Phim Yêu Thích</h1>
            <p class="banner-description">Danh sách những bộ phim mà bạn đã đánh dấu yêu thích</p>
        </div>
    </div>

    <main id="main-contents" class="col-xs-12 col-sm-12 col-md-12">
        <section class="favorites-section">
            <div class="section-bar clearfix">
                <h3 class="section-title"><span>Phim yêu thích của tôi</span></h3>
            </div>

            @if(count($favorites) > 0)
            <!-- Stats bar -->
            <div class="favorites-stats">
                <div class="stat-item">
                    <i class="fas fa-film"></i>
                    <div class="stat-details">
                        <span class="stat-value">{{ count($favorites) }}</span>
                        <span class="stat-label">Phim</span>
                    </div>
                </div>
                <div class="stat-item">
                    <i class="fas fa-heart"></i>
                    <div class="stat-details">
                        <span class="stat-value">{{ now()->format('d/m/Y') }}</span>
                        <span class="stat-label">Cập nhật</span>
                    </div>
                </div>
            </div>

            <div class="movie-sorting">
                <span class="sorting-label"><i class="fas fa-sort"></i> Sắp xếp:</span>
                <a href="{{ route('favorites', ['sort' => 'latest']) }}"
                    class="sort-option {{ request()->get('sort') == 'latest' ? 'active' : '' }}">Mới nhất</a>
                <a href="{{ route('favorites', ['sort' => 'oldest']) }}"
                    class="sort-option {{ request()->get('sort') == 'oldest' ? 'active' : '' }}">Cũ nhất</a>
                <a href="{{ route('favorites', ['sort' => 'name']) }}"
                    class="sort-option {{ request()->get('sort') == 'name' ? 'active' : '' }}">Theo tên</a>
            </div>

            <div class="halim_box">
                @foreach($favorites as $key => $mov)
                <article class="col-md-3 col-sm-3 col-xs-6 thumb grid-item post-{{ $mov->id }}">
                    <div class="halim-item">
                        <a class="halim-thumb" href="{{ route('movie', $mov->slug) }}" title="{{ $mov->title }}">
                            <figure>
                                <img class="lazy img-responsive"
                                    src="@if(Str::startsWith($mov->image, ['http://', 'https://'])){{ $mov->image }}@else{{ asset('uploads/movie/'.$mov->image) }}@endif"
                                    alt="{{ $mov->title }}" title="{{ $mov->title }}">
                            </figure>
                            <span class="status1">
                                @if($mov->resolution==0)
                                <span class="tag-base hd-tag">HD</span>
                                @elseif($mov->resolution==1)
                                <span class="tag-base sd-tag">SD</span>
                                @elseif($mov->resolution==2)
                                <span class="tag-base hdcam-tag">Cam HD</span>
                                @elseif($mov->resolution==3)
                                <span class="tag-base cam-tag">CAM</span>
                                @elseif($mov->resolution==4)
                                <span class="tag-base fullhd-tag">FullHD</span>
                                @else
                                <span class="tag-base trailer-tag">Trailer</span>
                                @endif
                            </span>

                            <span class="episode1">
                                @if($mov->phude==0)
                                <span class="tag-base phude-tag">P.Đề</span>
                                @else
                                <span class="tag-base thuyetminh-tag">T.Minh</span>
                                @endif

                                @if($mov->season!=0)
                                <span class="tag-base ss-tag">SS {{ $mov->season }}</span>
                                @endif
                            </span>
                            <div class="play-button">
                                <i class="fas fa-play"></i>
                            </div>
                            <div class="icon_overlay"></div>
                            <div class="halim-post-title-box">
                                <div class="halim-post-title">
                                    <p class="entry-title">{{ $mov->title }}</p>
                                    <p class="original_title">{{ $mov->name_eng }}</p>
                                </div>
                            </div>
                        </a>
                        <div class="movie-actions">
                            <div class="action-buttons">
                                <a href="{{ route('movie', $mov->slug) }}" class="btn-watch">
                                    <i class="fas fa-play-circle"></i> Xem phim
                                </a>
                                <form action="{{ route('favorites.remove', $mov->id) }}" method="POST"
                                    class="remove-form">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" class="btn-remove" title="Xóa khỏi yêu thích"
                                        onclick="confirmDelete(this, {{ $mov->id }}, '{{ $mov->title }}')">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </article>
                @endforeach
            </div>

            <div class="clearfix"></div>
            <div class="pagination-container">
                {{ $favorites->links('pagination::bootstrap-4') }}
            </div>
            @else
            <div class="empty-favorites">
                <div class="empty-content">
                    <i class="fas fa-heart-broken"></i>
                    <h3>Bạn chưa có phim yêu thích nào</h3>
                    <p>Khám phá thêm các bộ phim và thêm vào danh sách yêu thích của bạn</p>
                    <a href="{{ route('homepage') }}" class="discover-btn">
                        <i class="fas fa-compass"></i> Khám phá phim ngay
                    </a>
                </div>
            </div>
            @endif
        </section>
    </main>
</div>

<script>
    function confirmDelete(button, movieId, movieTitle) {
        Swal.fire({
            title: 'Xóa phim yêu thích?',
            html: `Bạn có chắc muốn xóa phim <b>${movieTitle}</b> khỏi danh sách yêu thích không?`,
            icon: 'warning',
            iconColor: '#ff9800',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Xóa phim',
            cancelButtonText: 'Hủy bỏ',
            backdrop: `rgba(0,0,0,0.7)`,
            width: 'auto',
            padding: '2em',
            customClass: {
                title: 'swal-title',
                htmlContainer: 'swal-text',
                confirmButton: 'swal-confirm',
                cancelButton: 'swal-cancel',
                icon: 'swal-icon',
                popup: 'swal-popup'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                // Nếu người dùng xác nhận xóa, submit form
                const form = button.closest('form');
                if (form) {
                    form.submit();
                }
            }
        });
    }
</script>

@endsection