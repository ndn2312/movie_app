<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <meta content="width=device-width,initial-scale=1.0, maximum-scale=1.0, user-scalable=no" name="viewport">
    <meta name="theme-color" content="#0f1824">
    <meta http-equiv="Content-Language" content="vi">
    <meta content="VN" name="geo.region">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="shortcut icon" href="/favicon.png" type="image/png">

    <title>Ndn Phim 2025 - Xem phim hay nhất</title>
    <meta name="description" content="Ndn Phim 2025 - Kho phim chất lượng cao, cập nhật nhanh nhất, xem phim online miễn phí HD">
    <link rel="canonical" href="">

    <!-- SEO Meta Tags -->
    <meta property="og:locale" content="vi_VN">
    <meta property="og:title" content="Ndn Phim 2025 - Xem phim hay nhất">
    <meta property="og:description" content="Kho phim chất lượng cao 2025 - Phim lẻ, phim bộ, phim chiếu rạp. Tuyển chọn phim hay nhất Việt Nam, Hàn Quốc, Trung Quốc, Mỹ...">
    <meta property="og:url" content="">
    <meta property="og:site_name" content="Phim hay 2025">
    <meta property="og:image" content="">

    <!-- CSS Files -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <link rel='stylesheet' href='{{asset('css/bootstrap.min.css')}}'>
    {{-- <link rel='stylesheet' href='{{asset('css/layout.css')}}'> --}}
 
    <link rel='stylesheet' href='{{asset('css/style.css')}}'>
    


    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&family=Be+Vietnam+Pro:wght@400;500;600;700&display=swap" rel="stylesheet">
    <div id="fb-root"></div>
    <script async defer crossorigin="anonymous" src="https://connect.facebook.net/vi_VN/sdk.js#xfbml=1&version=v22.0"></script>
    <script src='{{asset('js/jquery.min.js')}}'></script>

    <!-- Plyr CSS -->
<link rel="stylesheet" href="https://cdn.plyr.io/3.7.8/plyr.css" />
<!-- Plyr JS -->
<script src="https://cdn.plyr.io/3.7.8/plyr.polyfilled.js"></script>

<!-- HLS.js cho phát video m3u8 -->
<script src="https://cdn.jsdelivr.net/npm/hls.js@latest"></script>
<script src="{{asset('js/vendors/hls/hls-player.js')}}"></script>

</head>

<body>


    @include('partials.loading-screen')

    <!-- Header -->
    
    <header id="header">

        <div class="container">
            <div class="header-inner">
                <!-- Logo -->
                <a href="/" class="site-logo">
                    <img src="/imgs/logo.png" alt="NdnPhim" class="logo-image">
                    <h1 class="logo-text">Ndn<span>Phim</span></h1>
                </a>

                <!-- Search -->
                <div class="search-wrapper">
                    <form action="{{route('tim-kiem')}}" method="GET">

                    <div class="search-box">
                            <div type="button" class="search-icon">
                                <i class="fas fa-search"></i>
                            </div>
                            <input id="timkiem" name="search" type="text" placeholder="Tìm kiếm phim..." autocomplete="off">
                            <div class="clear-search">
                                <i class="fas fa-times"></i>
                            </div>
                        
                    </div>
                    </form>
                    <div class="search-results">
                        <ul id="result">
                            <!-- Search results will appear here -->
                        </ul>

                        <div id="no-results" style="display: none;">
                            <i class="fas fa-search"></i>
                            <p>Không tìm thấy kết quả phù hợp</p>
                            <small>Hãy thử tìm kiếm với từ khóa khác</small>
                        </div>

                        <div id="search-loading" style="display: none;">
                            <i class="fas fa-spinner"></i>
                            <p>Đang tìm kiếm...</p>
                            <small>Chờ một chút nhé</small>
                        </div>
                    </div>
                </div>

                <!-- User Actions -->
                <div class="user-actions">
                    <div class="bookmark-btn" id="get-bookmark">
                        <i class="fas fa-bookmark"></i>
                        <span>Bookmarks</span>
                        <span class="bookmark-count">0</span>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- Navigation -->
    <div class="navbar-container">

        <div class="container">
            <nav class="main-nav">
                <ul class="nav-menu">
                    <li class="nav-item active">
                        <a class="nav-link" href="{{route('homepage')}}">
                            <i class="fas fa-home"></i>Trang Chủ
                        </a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#">
                            <i class="fas fa-film"></i>Thể Loại
                        </a>
                        <ul class="dropdown-menu">
                            @foreach($genre as $gen)
                                <li><a class="dropdown-item" href="{{route('genre', $gen->slug)}}">{{$gen->title}}</a></li>
                            @endforeach
                        </ul>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#">
                            <i class="fas fa-globe"></i>Quốc Gia
                        </a>
                        <ul class="dropdown-menu">
                            @foreach($country as $count)
                                <li><a class="dropdown-item" href="{{route('country', $count->slug)}}">{{$count->title}}</a></li>
                            @endforeach
                        </ul>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#">
                            <i class="fas fa-calendar-alt"></i>Năm
                        </a>
                        <ul class="dropdown-menu">
                            @for($year=2025; $year>=2000; $year--)
                                <li><a class="dropdown-item" href="{{url('nam/'.$year)}}">{{$year}}</a></li>
                            @endfor
                        </ul>
                    </li>
                    @foreach($category as $cate)
                        <li class="nav-item">
                            <a class="nav-link" href="{{route('category',$cate->slug)}}">{{$cate->title}}</a>
                        </li>
                    @endforeach
                </ul>
                <a href="{{ route('advancedfilter') }}" class="filter-btn">
                    <i class="fas fa-filter"></i>Lọc Phim Nâng Cao
                </a>
            </nav>
        </div>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <div class="container">
            @yield('content')
        </div>
    </div>

    <!-- Footer -->
    <footer class="site-footer">
        <div class="container">
            <img src="https://example.com/logo.png" alt="NdnPhim" class="footer-logo">
            <div class="footer-content">
                <div class="footer-column">
                    <h3>Về chúng tôi</h3>
                    <p>NdnPhim cung cấp kho phim HD chất lượng cao miễn phí. Chúng tôi liên tục cập nhật những bộ phim mới nhất với chất lượng tốt nhất.</p>

                    <div class="footer-social">
                        <a href="#" class="social-icon"><i class="fab fa-facebook-f"></i></a>
                        <a href="#" class="social-icon"><i class="fab fa-twitter"></i></a>
                        <a href="#" class="social-icon"><i class="fab fa-instagram"></i></a>
                        <a href="#" class="social-icon"><i class="fab fa-youtube"></i></a>
                    </div>
                </div>
                <div class="footer-column">
                    <h3>Liên kết hữu ích</h3>
                    <ul class="footer-links">
                        <li><a href="#">Phim mới</a></li>
                        <li><a href="#">Phim lẻ</a></li>
                        <li><a href="#">Phim bộ</a></li>
                        <li><a href="#">Phim chiếu rạp</a></li>
                        <li><a href="#">TV Shows</a></li>
                    </ul>
                </div>
                <div class="footer-column">
                    <h3>Thông tin</h3>
                    <ul class="footer-links">
                        <li><a href="#">Điều khoản sử dụng</a></li>
                        <li><a href="#">Chính sách bảo mật</a></li>
                        <li><a href="#">FAQ</a></li>
                        <li><a href="#">Liên hệ</a></li>
                    </ul>
                </div>
                <div class="footer-column">
                    <h3>Liên hệ</h3>
                    <p>Liên hệ QC: <a href="mailto:contact@example.com">contact@example.com</a></p>
                    <p>Hỗ trợ: <a href="mailto:support@example.com">support@example.com</a></p>
                </div>
            </div>

            <div class="footer-bottom">
                <p>&copy; 2025 NdnPhim. Tất cả quyền được bảo lưu.</p>
            </div>
        </div>
    </footer>

    <!-- Back to Top Button -->
    <div id="back-to-top">
        <i class="fas fa-chevron-up"></i>
    </div>

    <!-- Toast Container -->
    <div class="toast-container"></div>

    <!-- Scripts -->
    <script type='text/javascript'>
        $(document).ready(function() {
           $.ajax({
              url: "{{url('/filter-topview-default')}}",
              method: "GET",
              
              success: function(data) {
                    $('#show_data_default').html(data);
              }
           });
        
     
        $('.filter-sidebar').click(function() {
           var href = $(this).attr('href');
           
           // Xác định giá trị dựa trên href
           if (href === '#ngay') {
              var value = 0;
           } else if (href === '#tuan') {
              var value = 1;
           } else {
              var value = 2;
           }
           
           // Thực hiện yêu cầu AJAX
           $.ajax({
              url: "{{url('/filter-topview-phim')}}",
              method: "POST",
              
              data: { value: value },
              headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},

              success: function(data) {
                    $('#halim-ajax-popular-post-default').css("display", "none");

                    $('#show_data').html(data);
              }
           });
        });
     })

  </script>
  
  
    <script src='{{asset('js/bootstrap.min.js')}}'></script>
    <script src='{{asset('js/owl.carousel.min.js')}}'></script>
    <script src='{{asset('js/search.js')}}'></script>
    <script>
      
        $(document).ready(function() {
        // Khởi tạo hệ thống đánh giá popup
        if ($('#movie-rating-system-popup').length) {
            initPopupRatingSystem();
        }
        
        function initPopupRatingSystem() {
            const ratingSystem = $('#movie-rating-system-popup');
            const movieId = ratingSystem.data('movie-id');
            const stars = ratingSystem.find('.star');
            const feedback = $('#rating-feedback-popup');
            const currentRating = parseInt($('.rating-stats .average').text()) || 0;
            
            // Hiệu ứng hover
            stars.on('mouseenter', function() {
            const value = $(this).data('value');
            resetStars();
            highlightStars(value);
            $(this).addClass('pulse');
            });
            
            ratingSystem.on('mouseleave', function() {
            resetStars();
            highlightStars(currentRating);
            stars.removeClass('pulse');
            });
            
            // Click để đánh giá
            stars.on('click', function() {
            const value = $(this).data('value');
            submitRating(movieId, value);
            });
            
            // Các hàm tiện ích
            function resetStars() {
            stars.removeClass('hover');
            }
            
            function highlightStars(count) {
            stars.each(function(index) {
                if (index < count) {
                $(this).addClass('hover');
                }
            });
            }
            
            function submitRating(movieId, rating) {
            $.ajax({
                url: '/add-rating',
                method: 'POST',
                data: {
                movie_id: movieId,
                index: rating
                },
                headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                beforeSend: function() {
                feedback.removeClass('success error')
                        .addClass('loading')
                        .text('Đang xử lý...')
                        .fadeIn();
                },
                success: function(response) {
                if (response === 'done') {
                    feedback.removeClass('loading error')
                        .addClass('success')
                        .html('<i class="fas fa-check-circle"></i> Cảm ơn bạn đã đánh giá ' + rating + ' sao!');
                    
                    // Cập nhật UI mà không cần tải lại trang
                    updateRatingDisplay(rating);
                    
                    // Đóng modal sau 2 giây
                    setTimeout(function() {
                    $('#ratingModal').modal('hide');
                    }, 2000);
                } else if (response === 'exist') {
                    feedback.removeClass('loading success')
                        .addClass('error')
                        .html('<i class="fas fa-exclamation-circle"></i> Bạn đã đánh giá phim này trước đó');
                } else {
                    feedback.removeClass('loading success')
                        .addClass('error')
                        .html('<i class="fas fa-exclamation-triangle"></i> Có lỗi xảy ra, vui lòng thử lại sau');
                }
                
                setTimeout(function() {
                    feedback.fadeOut();
                }, 5000);
                },
                error: function() {
                feedback.removeClass('loading success')
                        .addClass('error')
                        .html('<i class="fas fa-exclamation-triangle"></i> Lỗi kết nối, vui lòng thử lại sau');
                
                setTimeout(function() {
                    feedback.fadeOut();
                }, 5000);
                }
            });
            }
    
            function updateRatingDisplay(newRating) {
            // Cập nhật đánh giá trung bình trên trang và trong popup
            $('.rating-stats .average, .current-rating .average').text(newRating);
            
            // Cập nhật số lượt đánh giá
            let currentCount = parseInt($('.rating-stats .count').text().replace(/[()]/g, '')) || 0;
            $('.rating-stats .count, .current-rating small').text('(' + (currentCount + 1) + ' lượt đánh giá)');
            
            // Cập nhật sao trong popup
            resetStars();
            highlightStars(newRating);
            stars.removeClass('active');
            stars.each(function(index) {
                if (index < newRating) {
                $(this).addClass('active');
                }
            });
            }
        }
        
        // Hiển thị đánh giá khi mở modal
        $('#ratingModal').on('shown.bs.modal', function () {
            const currentRating = parseInt($('.rating-stats .average').text()) || 0;
            $('#movie-rating-system-popup .star').removeClass('active hover');
            $('#movie-rating-system-popup .star').each(function(index) {
            if (index < currentRating) {
                $(this).addClass('active');
            }
            });
        });
        });

    
// Đồng bộ lượt xem từ sessionStorage
$(document).ready(function() {
    // Lấy dữ liệu cập nhật lượt xem từ sessionStorage
    const viewUpdates = JSON.parse(sessionStorage.getItem('viewUpdates') || '{}');
    
    // Cập nhật các mục trong sidebar
    if (Object.keys(viewUpdates).length > 0) {
        $('#sidebar .popular-post .item').each(function() {
            const itemHref = $(this).find('a').attr('href');
            if (itemHref) {
                // Lấy slug từ href
                const slugMatch = itemHref.match(/\/phim\/([^\/]+)$/);
                if (slugMatch && slugMatch[1]) {
                    const movieSlug = slugMatch[1];
                    // Nếu có cập nhật cho phim này
                    if (viewUpdates[movieSlug]) {
                        $(this).find('.viewsCount').text(viewUpdates[movieSlug] + ' lượt xem');
                    }
                }
            }
        });
    }
});


  </script>

</body>
</html>
