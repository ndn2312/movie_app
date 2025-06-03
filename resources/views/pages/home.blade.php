@extends('layout')

@section('content')
<style>
    .tag-base {
        display: inline-block;
        padding: 4px 10px;
        font-size: 11px;
        font-weight: 600;
        border-radius: 20px;
        color: #FFFFFF;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
        text-transform: uppercase;
        letter-spacing: 0.5px;
        transition: all 0.3s ease;
    }

    /* ==================== POSITION STYLES ==================== */
    .status {
        position: absolute;
        top: 10px;
        left: 10px;
        z-index: 10;
    }

    .episode {
        position: absolute;
        top: 10px;
        right: 10px;
        z-index: 10;
        background: none;
        padding: 0;
        margin: 0;
    }

    /* ==================== TAG STYLES ==================== */
    .trailer-tag {
        background: linear-gradient(135deg, #e304af 0%, #dc07d5 100%);
    }

    .fullhd-tag {
        background: linear-gradient(135deg, #ff0000 0%, #aa0000 100%);
    }

    .hd-tag {
        background: linear-gradient(135deg, #03f5fd 0%, #0f6060 100%);
    }

    .sd-tag {
        background: linear-gradient(135deg, #0f3460 0%, #533483 100%);
    }

    .hdcam-tag {
        background: linear-gradient(135deg, #533483 0%, #9896f1 100%);
    }

    .cam-tag {
        background: linear-gradient(135deg, #9896f1 0%, #7579e7 100%);
    }

    .thuyetminh-tag {
        background: linear-gradient(135deg, #008000 0%, #005000 100%);
    }

    .phude-tag {
        background: linear-gradient(135deg, #666666 0%, #333333 100%);
    }

    .ss-tag {
        background: linear-gradient(135deg, #034ba8 30%, #04c1e7 70%);
    }

    .sotap-tag {
        background: linear-gradient(135deg, #ea8d40 30%, #e70404 70%);
    }

    /* ==================== TITLE HOVER EFFECTS ==================== */
    .halim-post-title .entry-title {
        position: relative;
        transition: all 0.3s ease;
        font-weight: 700;
        font-size: 15px;
        margin-bottom: 3px;
        line-height: 1.3;
    }

    .halim-thumb:hover .entry-title {
        color: #06f2e6 !important;
        transform: translateY(-2px);
        font-weight: 700 !important;
    }

    .halim-post-title .original_title {
        position: relative;
        transition: all 0.3s ease;
        opacity: 0.7;
        font-weight: 600;
        font-size: 13px;
        line-height: 1.2;
    }

    .halim-thumb:hover .original_title {
        color: #ffcf4a !important;
        opacity: 1;
    }

    /* ==================== PLAY BUTTON - UPDATED ==================== */
    .play-button {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%) scale(0.8);
        width: 60px;
        height: 60px;
        background: rgba(4, 193, 231, 0.3);
        backdrop-filter: blur(10px);
        border-radius: 50%;
        display: flex;
        justify-content: center;
        align-items: center;
        color: white;
        opacity: 0;
        transition: all 0.4s ease;
        z-index: 3;
        border: 2px solid rgba(255, 255, 255, 0.8);
    }

    .halim-thumb:hover .play-button {
        opacity: 1;
        transform: translate(-50%, -50%) scale(1);
        background: rgba(4, 193, 231, 0.7);
    }

    .play-button i {
        color: white;
        font-size: 22px;
        margin-left: 3px;
    }

    .play-button::after {
        content: '';
        position: absolute;
        width: 100%;
        height: 100%;
        border-radius: 50%;
        border: 2px solid rgba(255, 255, 255, 0.8);
        animation: pulse 2s infinite;
    }

    @keyframes pulse {
        0% {
            transform: scale(1);
            opacity: 0.8;
        }

        70% {
            transform: scale(1.4);
            opacity: 0;
        }

        100% {
            transform: scale(1.4);
            opacity: 0;
        }
    }

    /* ==================== MOVIE THUMBNAIL EFFECTS ==================== */
    .halim-thumb figure {
        border-radius: 8px;
        overflow: hidden;
        transition: all 0.3s ease;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    }

    .halim-thumb figure img {
        transition: transform 0.4s ease;
    }

    .halim-thumb:hover figure {
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.15);
    }

    .halim-thumb:hover figure img {
        transform: scale(1.05);
    }

    /* ==================== MOVIE CONTAINER ==================== */


    .halim-item:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 15px rgba(0, 0, 0, 0.15);
    }

    /* ==================== SECTION HEADINGS ==================== */
    .section-heading,
    .section-bar {
        position: relative;
        margin-bottom: 20px;
        border-bottom: 2px solid #f0f0f0;
        padding: 10px 0;
    }

    .section-heading span.h-text,
    .section-title span {
        display: inline-block;
        padding: 8px 16px;
        color: white;
        font-weight: 700;
        font-size: 16px;
        position: relative;
        background: linear-gradient(135deg, #03f5fd 0%, #0f6060 100%);
        border-radius: 4px 4px 0 0;
    }

    /* PHIM HOT specific styles */
    #halim_related_movies-2xx .section-bar {
        border-bottom: 2px solid #e30f04;
    }

    #halim_related_movies-2xx .section-title {
        margin: 0;
    }

    #halim_related_movies-2xx .section-title span {
        background: linear-gradient(135deg, #e33c04 0%, #dc1207 100%);
        font-size: 16px;
        padding: 8px 16px;
    }

    /* Container for PHIM HOT carousel */
    #halim_related_movies-2 {
        padding: 15px 0;
    }

    /* === FIX CHẮC CHẮN CHO NÚT ĐIỀU HƯỚNG === */
    .owl-carousel .owl-nav {
        z-index: 99 !important;
    }

    /* Ẩn style mặc định */
    .owl-theme .owl-nav [class*=owl-] {
        background: transparent !important;
        margin: 0 !important;
        padding: 0 !important;
        font-size: 0 !important;
    }

    /* Style mới cho nút - QUYẾT LIỆT */
    .hero-slider .owl-nav .owl-prev,
    .hero-slider .owl-nav .owl-next {
        width: 50px !important;
        height: 50px !important;
        position: absolute !important;
        top: 50% !important;
        transform: translateY(-50%) !important;
        background: rgba(0, 0, 0, 0.5) !important;
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
        border-radius: 50% !important;
        color: white !important;
        font-size: 24px !important;
        opacity: 0.8 !important;
        transition: all 0.3s ease !important;
        border: 2px solid rgba(255, 255, 255, 0.3) !important;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.3) !important;
    }

    .hero-slider .owl-nav .owl-prev {
        left: 15px !important;
    }

    .hero-slider .owl-nav .owl-next {
        right: 15px !important;
    }

    .hero-slider:hover .owl-nav .owl-prev,
    .hero-slider:hover .owl-nav .owl-next {
        opacity: 1 !important;
    }

    .hero-slider .owl-nav .owl-prev:hover,
    .hero-slider .owl-nav .owl-next:hover {
        background: #03f5fd !important;
        box-shadow: 0 0 15px rgba(3, 245, 253, 0.6) !important;
        transform: translateY(-50%) scale(1.1) !important;
        border-color: white !important;
    }

    /* Phim hot specific */
    #halim_related_movies-2 .owl-nav .owl-prev,
    #halim_related_movies-2 .owl-nav .owl-next {
        width: 40px !important;
        height: 40px !important;
        position: absolute !important;
        top: 50% !important;
        transform: translateY(-50%) !important;
        background: #e33c04 !important;
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
        border-radius: 50% !important;
        color: white !important;
        font-size: 18px !important;
        opacity: 0.8 !important;
        transition: all 0.3s ease !important;
        border: 2px solid rgba(255, 255, 255, 0.3) !important;
        box-shadow: 0 0 10px rgba(227, 60, 4, 0.3) !important;
    }

    #halim_related_movies-2 .owl-nav .owl-prev {
        left: 10px !important;
    }

    #halim_related_movies-2 .owl-nav .owl-next {
        right: 10px !important;
    }

    #halim_related_movies-2:hover .owl-nav .owl-prev,
    #halim_related_movies-2:hover .owl-nav .owl-next {
        opacity: 1 !important;
    }

    #halim_related_movies-2 .owl-nav .owl-prev:hover,
    #halim_related_movies-2 .owl-nav .owl-next:hover {
        background: #ff4a12 !important;
        box-shadow: 0 0 15px rgba(255, 74, 18, 0.6) !important;
        transform: translateY(-50%) scale(1.1) !important;
        border-color: white !important;
    }

    /* Đảm bảo icon hiển thị đúng */
    .owl-carousel .owl-nav button.owl-prev i,
    .owl-carousel .owl-nav button.owl-next i {
        font-size: inherit !important;
        color: white !important;
        line-height: 1 !important;
        margin: 0 !important;
        padding: 0 !important;
        display: block !important;
    }

    /* ==================== CATEGORY SECTION STYLES ==================== */
    /* Container for movie grid */
    /* #halim-advanced-widget-2-ajax-box {
        display: flex;
        flex-wrap: wrap;
        padding: 15px 0;
    } */

    /* Customize category section headings */
    #halim-advanced-widget-2 .section-heading span.h-text {
        background: linear-gradient(135deg, #0f3460 0%, #533483 100%);
    }

    /* Alternate colors for different category sections */
    #halim-advanced-widget-2:nth-of-type(odd) .section-heading span.h-text {
        background: linear-gradient(135deg, #0f3460 0%, #533483 100%);
    }

    #halim-advanced-widget-2:nth-of-type(even) .section-heading span.h-text {
        background: linear-gradient(135deg, #034ba8 0%, #04c1e7 100%);
    }

    /* ==================== HERO SECTION STYLES ==================== */
    .hero-section {
        position: relative;
        height: 500px;
        margin-bottom: 30px;
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.2);
    }

    .hero-slide {
        height: 500px;
        background-size: cover;
        background-position: center;
        position: relative;
    }

    .hero-overlay {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: linear-gradient(to bottom, rgba(0, 0, 0, 0.2) 0%, rgba(0, 0, 0, 0.8) 100%);
    }

    /* Hero content styling */
    .hero-content {
        position: absolute;
        bottom: 60px;
        left: 0;
        width: 100%;
        padding: 0 50px;
        z-index: 2;
        color: #fff;
        text-align: center;
    }

    .hero-title {
        font-size: 42px;
        font-weight: 700;
        margin-bottom: 15px;
        text-shadow: 0 2px 4px rgba(0, 0, 0, 0.5);
        transform: translateY(20px);
        opacity: 0;
        animation: fadeInUp 0.8s ease forwards 0.3s;
    }

    .hero-description {
        font-size: 18px;
        max-width: 700px;
        margin: 0 auto 25px;
        text-shadow: 0 1px 3px rgba(0, 0, 0, 0.3);
        transform: translateY(20px);
        opacity: 0;
        animation: fadeInUp 0.8s ease forwards 0.5s;
    }

    .hero-buttons {
        display: flex;
        justify-content: center;
        gap: 15px;
        transform: translateY(20px);
        opacity: 0;
        animation: fadeInUp 0.8s ease forwards 0.7s;
    }

    /* Animation reset for inactive slides */
    .owl-item:not(.active) .hero-title,
    .owl-item:not(.active) .hero-description,
    .owl-item:not(.active) .hero-buttons {
        animation: none;
        opacity: 0;
        transform: translateY(20px);
    }

    /* Animation for when slide becomes active */
    .owl-item.active .hero-title {
        animation: fadeInUp 0.8s ease forwards 0.3s;
    }

    .owl-item.active .hero-description {
        animation: fadeInUp 0.8s ease forwards 0.5s;
    }

    .owl-item.active .hero-buttons {
        animation: fadeInUp 0.8s ease forwards 0.7s;
    }

    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .hero-btn {
        display: inline-flex;
        align-items: center;
        padding: 12px 30px;
        border-radius: 50px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 1px;
        transition: all 0.3s ease;
        border: none;
        cursor: pointer;
    }

    .hero-btn-primary {
        background: linear-gradient(135deg, #03f5fd 0%, #0f6060 100%);
        color: #fff;
    }

    .hero-btn-secondary {
        background: rgba(255, 255, 255, 0.2);
        backdrop-filter: blur(10px);
        color: #fff;
    }

    .hero-btn i {
        margin-right: 8px;
        font-size: 16px;
    }

    .hero-btn:hover {
        transform: translateY(-3px);
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
    }

    /* Hero slider dots styling - FIXED */
    .hero-slider .owl-dots {
        position: absolute;
        bottom: 20px;
        left: 0;
        width: 100%;
        text-align: center;
        z-index: 10;
        display: flex !important;
        justify-content: center;
        align-items: center;
        height: auto !important;
        background: none !important;
    }

    .hero-slider .owl-dots .owl-dot {
        width: 12px !important;
        height: 12px !important;
        margin: 0 5px !important;
        border-radius: 50% !important;
        background: rgba(255, 255, 255, 0.5) !important;
        border: none !important;
        transition: all 0.3s ease !important;
        display: inline-block !important;
        float: none !important;
    }

    .hero-slider .owl-dots .owl-dot.active {
        background: #03f5fd !important;
        transform: scale(1.2) !important;
    }

    /* Remove any progress bar styling */
    .owl-dot span {
        display: none !important;
    }

    /* Remove any progress bar effect */
    .owl-dots::before,
    .owl-dots::after {
        display: none !important;
    }

    /* Hide any unwanted dot containers */
    body>.owl-dots,
    .main-content>.owl-dots,
    #wrapper>.owl-dots {
        display: none !important;
    }

    /* ==================== SECTION IMPROVEMENTS ==================== */
    /* Category section improvements */
    .category-section {
        margin-bottom: 40px;
    }

    .category-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 15px;
    }

    .view-all-link {
        color: #03f5fd;
        font-weight: 600;
        font-size: 14px;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
    }

    .view-all-link:hover {
        color: #0f6060;
        transform: translateX(3px);
    }

    .view-all-link i {
        margin-left: 5px;
        font-size: 12px;
    }

    /* Grid improvements */
    .movie-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
        gap: 20px;
    }

    @media (max-width: 576px) {
        .movie-grid {
            grid-template-columns: repeat(auto-fill, minmax(140px, 1fr));
            gap: 15px;
        }
    }
</style>

<!-- Hero Section -->
<div class="hero-section">
    <div class="hero-slider owl-carousel owl-theme">
        @foreach($phimhot->take(5) as $hot)
        <div class="hero-slide"
            style="background-image: url('@if(isset($hot->thumb_url) && Str::startsWith($hot->thumb_url, ['http://', 'https://'])){{$hot->thumb_url}}@elseif(isset($hot->thumb_url)){{asset('uploads/movie/'.$hot->thumb_url)}}@else{{asset('uploads/movie/'.$hot->image)}}@endif')">
            <div class="hero-overlay"></div>
            <div class="hero-content">
                <h2 class="hero-title">{{$hot->title}}</h2>
                <p class="hero-description">
                    @if(isset($hot->description) && !empty($hot->description))
                    {{ \Illuminate\Support\Str::limit($hot->description, 150) }}
                    @else
                    Một bộ phim đáng xem với những diễn biến hấp dẫn
                    @endif
                </p>
                <div class="hero-buttons">
                    @php
                    $first_episode = App\Models\Episode::where('movie_id', $hot->id)->orderBy('episode',
                    'ASC')->first();
                    @endphp

                    @if($first_episode)
                    <a href="{{url('xem-phim/'.$hot->slug. '/tap-'.$first_episode->episode)}}"
                        class="hero-btn hero-btn-primary">
                        <i class="fas fa-play"></i> Xem Phim
                    </a>
                    @else
                    <a href="{{route('movie',$hot->slug)}}" class="hero-btn hero-btn-primary">
                        <i class="fas fa-play"></i> Xem Phim
                    </a>
                    @endif

                    <a href="{{route('movie',$hot->slug)}}" class="hero-btn hero-btn-secondary">
                        <i class="fas fa-info-circle"></i> Chi Tiết
                    </a>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>

<div class="row container" id="wrapper">
    <div class="halim-panel-filter">
        <div id="ajax-filter" class="panel-collapse collapse" aria-expanded="true" role="menu">
            <div class="ajax"></div>
        </div>
    </div>

    <!-- Hot Movies Carousel Section -->
    <div id="halim_related_movies-2xx" class="wrap-slider">
        <div class="section-bar clearfix">
            <h3 class="section-title"><span>PHIM HOT</span></h3>
        </div>
        <div id="halim_related_movies-2" class="owl-carousel owl-theme related-film">
            @foreach($phimhot as $key => $hot)
            <article class="thumb grid-item post-38498">
                <div class="halim-item">
                    <a class="halim-thumb" href="{{route('movie',$hot->slug)}}" title="{{$hot->title}}">
                        <figure><img class="lazy img-responsive"
                                src="@if(Str::startsWith($hot->image, ['http://', 'https://'])){{$hot->image}}@else{{asset('uploads/movie/'.$hot->image)}}@endif"
                                alt="{{$hot->title}}" title="{{$hot->title}}"></figure>
                        <span class="status">
                            @if($hot->resolution==0)
                            <span class="tag-base hd-tag">HD</span>
                            @elseif($hot->resolution==1)
                            <span class="tag-base sd-tag">SD</span>
                            @elseif($hot->resolution==2)
                            <span class="tag-base hdcam-tag">HDCam</span>
                            @elseif($hot->resolution==3)
                            <span class="tag-base cam-tag">Cam</span>
                            @elseif($hot->resolution==4)
                            <span class="tag-base fullhd-tag">FullHD</span>
                            @else
                            <span class="tag-base trailer-tag">Trailer</span>
                            @endif
                        </span>

                        <span class="episode">
                            <span class="tag-base sotap-tag">{{$hot->episode_count}}/{{$hot->sotap}}</span>

                            @if($hot->phude==0)
                            <span class="tag-base phude-tag">Phụ Đề</span>
                            @else
                            <span class="tag-base thuyetminh-tag">T.M</span>
                            @endif

                            @if($hot->season!=0)
                            <span class="tag-base ss-tag">{{$hot->season}}.S</span>
                            @endif
                        </span>

                        <!-- Play button -->
                        <div class="play-button">
                            <i class="fas fa-play"></i>
                        </div>

                        <div class="icon_overlay"></div>
                        <div class="halim-post-title-box">
                            <div class="halim-post-title ">
                                <p class="entry-title">{{$hot->title}}</p>
                                <p class="original_title">{{$hot->name_eng}}</p>
                            </div>
                        </div>
                    </a>
                </div>
            </article>
            @endforeach
        </div>
    </div>

    <!-- Main Content -->
    <main id="main-contents" class="col-xs-12 col-sm-12 col-md-8">
        @foreach($category_home as $key => $cate_home)
        <section id="halim-advanced-widget-2" class="category-section">
            <div class="category-header">
                <div class="section-heading">
                    <span class="h-text">{{$cate_home->title}}</span>
                </div>
                @if(count($cate_home->movie) > 12)
                {{-- <a href="{{route('category', $cate_home->slug)}}" class="view-all-link">
                    Xem tất cả <i class="fas fa-chevron-right"></i>
                </a> --}}
                @endif
            </div>
            <div id="halim-advanced-widget-2-ajax-box" class="halim_box movie-grid">
                @foreach($cate_home->movie->take(9) as $key => $mov)
                <article class="thumb grid-item post-37606">
                    <div class="halim-item">
                        <a class="halim-thumb" href="{{route('movie',$mov->slug)}}" title="{{$mov->title}}">
                            <figure><img class="lazy img-responsive"
                                    src="@if(Str::startsWith($mov->image, ['http://', 'https://'])){{$mov->image}}@else{{asset('uploads/movie/'.$mov->image)}}@endif"
                                    title="{{$mov->title}}"></figure>
                            <span class="status">
                                @if($mov->resolution==0)
                                <span class="tag-base hd-tag">HD</span>
                                @elseif($mov->resolution==1)
                                <span class="tag-base sd-tag">SD</span>
                                @elseif($mov->resolution==2)
                                <span class="tag-base hdcam-tag">HDCam</span>
                                @elseif($mov->resolution==3)
                                <span class="tag-base cam-tag">Cam</span>
                                @elseif($mov->resolution==4)
                                <span class="tag-base fullhd-tag">FullHD</span>
                                @else
                                <span class="tag-base trailer-tag">Trailer</span>
                                @endif
                            </span>

                            <span class="episode">
                                <span class="tag-base sotap-tag">{{$mov->episode_count}}/{{$mov->sotap}}</span>
                                @if($mov->phude==0)
                                <span class="tag-base phude-tag">P.Đề</span>
                                @else
                                <span class="tag-base thuyetminh-tag">T.M</span>
                                @endif

                                @if($mov->season!=0)
                                <span class="tag-base ss-tag">{{$mov->season}}.S</span>
                                @endif
                            </span>

                            <!-- Play button -->
                            <div class="play-button">
                                <i class="fas fa-play"></i>
                            </div>

                            <div class="icon_overlay"></div>
                            <div class="halim-post-title-box">
                                <div class="halim-post-title ">
                                    <p class="entry-title">{{$mov->title}}</p>
                                    <p class="original_title">{{$mov->name_eng}}</p>
                                </div>
                            </div>
                        </a>
                    </div>
                </article>
                @endforeach
            </div>
        </section>
        <div class="clearfix"></div>
        @endforeach
    </main>

    {{-- Sidebar --}}
    @include('pages.include.sidebar')
</div>

<script>
    $(document).ready(function() {
        $('.hero-slider').owlCarousel({
            items: 1,
            loop: true,
            margin: 0,
            nav: true,
            navText: [
                '<i class="fa fa-angle-left"></i>',
                '<i class="fa fa-angle-right"></i>'
            ],
            dots: true,
            dotsEach: true,
            dotsData: false,
            autoplay: true,
            autoplayTimeout: 6000,
            autoplayHoverPause: true,
            smartSpeed: 1000,
            animateOut: 'fadeOut',
            animateIn: 'fadeIn'
        });
        
        $('#halim_related_movies-2').owlCarousel({
            loop: true,
            margin: 5,
            autoplay: true,
            autoplayTimeout: 4000,
            autoplayHoverPause: true,
            nav: true,
            navText: [
                '<i class="fa fa-angle-left"></i>',
                '<i class="fa fa-angle-right"></i>'
            ],
            responsiveClass: true,
            responsive: {
                0: {
                    items: 2
                },
                480: {
                    items: 3
                },
                600: {
                    items: 5
                },
                1000: {
                    items: 5
                }
            }
        });
    });
</script>
@endsection