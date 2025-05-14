@extends('layouts.app')

@section('content')
<link rel="stylesheet" href="{{ asset('css/leech_detail.css') }}">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css" />
<script src="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.js"></script>

<style>
    /* CSS cho các nút hành động */
    .header-actions {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
        position: relative;
        z-index: 10;
    }

    .action-buttons {
        display: flex;
        gap: 10px;
    }

    .action-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 8px 16px;
        border-radius: 6px;
        font-weight: 600;
        font-size: 0.95rem;
        transition: all 0.3s ease;
        text-decoration: none;
        color: white;
        border: none;
        cursor: pointer;
    }

    .action-btn i {
        margin-right: 6px;
    }

    .add-btn {
        background: linear-gradient(135deg, #4CAF50, #2E7D32);
        box-shadow: 0 3px 8px rgba(46, 125, 50, 0.3);
    }

    .add-btn:hover {
        background: linear-gradient(135deg, #2E7D32, #1B5E20);
        transform: translateY(-2px);
        box-shadow: 0 5px 12px rgba(46, 125, 50, 0.4);
    }

    .delete-btn {
        background: linear-gradient(135deg, #F44336, #C62828);
        box-shadow: 0 3px 8px rgba(198, 40, 40, 0.3);
    }

    .delete-btn:hover {
        background: linear-gradient(135deg, #C62828, #B71C1C);
        transform: translateY(-2px);
        box-shadow: 0 5px 12px rgba(198, 40, 40, 0.4);
    }

    .episode-btn {
        background: linear-gradient(135deg, #3F51B5, #303F9F);
        box-shadow: 0 3px 8px rgba(48, 63, 159, 0.3);
    }

    .episode-btn:hover {
        background: linear-gradient(135deg, #303F9F, #1A237E);
        transform: translateY(-2px);
        box-shadow: 0 5px 12px rgba(48, 63, 159, 0.4);
    }

    .back-btn {
        display: inline-flex;
        align-items: center;
        padding: 8px 16px;
        border-radius: 6px;
        font-weight: 600;
        font-size: 0.95rem;
        background: linear-gradient(135deg, #78909C, #546E7A);
        color: white;
        text-decoration: none;
        transition: all 0.3s ease;
        box-shadow: 0 3px 8px rgba(84, 110, 122, 0.3);
    }

    .back-btn:hover {
        background: linear-gradient(135deg, #546E7A, #37474F);
        transform: translateY(-2px);
        box-shadow: 0 5px 12px rgba(84, 110, 122, 0.4);
    }

    .back-btn i {
        margin-right: 6px;
    }

    /* Điều chỉnh form để không có khoảng cách không cần thiết */
    .add-movie-form,
    .destroy-movie-form {
        margin: 0;
        padding: 0;
    }

    /* Hiệu ứng khi nhấn nút */
    .button-clicked {
        transform: scale(0.95) !important;
        opacity: 0.9;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2) !important;
    }

    /* Notification */
    .notification-banner {
        position: fixed;
        top: 20px;
        right: 20px;
        background: linear-gradient(135deg, #4CAF50, #2E7D32);
        color: white;
        padding: 15px 20px;
        border-radius: 8px;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        z-index: 9999;
        display: flex;
        align-items: center;
        animation: slideIn 0.5s ease-out forwards;
    }

    .notification-banner.error {
        background: linear-gradient(135deg, #F44336, #C62828);
    }

    .notification-banner i {
        margin-right: 10px;
        font-size: 1.2rem;
    }

    .notification-content {
        flex-grow: 1;
    }

    .notification-close {
        background: rgba(255, 255, 255, 0.2);
        border: none;
        color: white;
        font-size: 1rem;
        width: 24px;
        height: 24px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-left: 10px;
        cursor: pointer;
        transition: all 0.2s;
    }

    .notification-close:hover {
        background: rgba(255, 255, 255, 0.3);
    }

    @keyframes slideIn {
        from {
            transform: translateX(100%);
            opacity: 0;
        }

        to {
            transform: translateX(0);
            opacity: 1;
        }
    }

    @keyframes slideOut {
        from {
            transform: translateX(0);
            opacity: 1;
        }

        to {
            transform: translateX(100%);
            opacity: 0;
        }
    }
</style>

<div class="movie-detail-wrapper">

    @if(session('success_message'))
    <div class="notification-banner" id="notification">
        <i class="fas fa-check-circle"></i>
        <div class="notification-content">
            <strong>{{ session('movie_title') }}</strong> {{ session('success_message') }} {{ session('action_type') }}
            {{ session('success_end') }}
        </div>
        <button class="notification-close" onclick="closeNotification()">×</button>
    </div>
    @endif

    <div class="movie-detail-container">
        @foreach ($resp_movie as $res)
        <div class="header-actions">
            <div class="back-button">
                <a href="{{route('leech-movie')}}" class="back-btn">
                    <i class="fas fa-arrow-left"></i> Quay lại
                </a>
            </div>

            @php
            $movie = App\Models\Movie::where('slug', $res['slug'])->first();
            @endphp
            <div class="action-buttons">
                @if(!$movie)
                <div class="add-movie-button">
                    <form method="POST" action="{{route('leech-store', $res['slug'])}}" class="add-movie-form">
                        @csrf
                        <button type="submit" class="action-btn add-btn">
                            <i class="fas fa-plus-circle"></i> Thêm phim
                        </button>
                    </form>
                </div>
                @else
                <div class="destroy-movie-button">
                    <form method="POST" action="{{route('movie.destroy', $movie->id)}}" class="destroy-movie-form"
                        onsubmit="return confirm('Bạn có chắc chắn muốn xóa phim {{$res['name']}}?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="action-btn delete-btn">
                            <i class="fas fa-trash"></i> Xóa phim
                        </button>
                    </form>
                </div>
                @endif

                @if($movie)
                <a href="{{route('leech-episode', $res['slug'])}}" class="action-btn episode-btn">
                    <i class="fas fa-list"></i> Thêm tập phim
                </a>
                @endif
            </div>
        </div>

        <!-- Header với background poster mờ -->
        <div class="movie-header-backdrop" style="background-image: url('{{$res['poster_url']}}')"></div>

        <div class="movie-header" data-aos="fade-down">
            <div class="movie-header-content">
                <div class="movie-poster">
                    <img src="{{$res['poster_url']}}" alt="{{$res['name']}}">
                    <div class="poster-overlay">
                        <i class="fas fa-expand-alt poster-zoom"></i>
                    </div>
                </div>

                <div class="movie-info-header">
                    <div class="movie-title-section">
                        <h1 class="movie-title">{{$res['name']}}</h1>
                        <h2 class="movie-original-title">{{$res['origin_name']}}</h2>
                    </div>

                    <div class="movie-badges">
                        <div class="badge-item">
                            <i class="fas fa-star"></i>
                            <span>{{$res['quality']}}</span>
                        </div>
                        <div class="badge-item">
                            <i class="fas fa-closed-captioning"></i>
                            <span>{{$res['lang']}}</span>
                        </div>
                    </div>

                    <div class="movie-meta">
                        <div class="meta-item">
                            <i class="fas fa-calendar-alt"></i> {{$res['year']}}
                        </div>
                        <div class="meta-item">
                            <i class="fas fa-clock"></i> {{$res['time']}}
                        </div>
                        <div class="meta-item">
                            <i class="fas fa-eye"></i> {{$res['view']}} lượt xem
                        </div>
                    </div>

                    <div class="movie-stats">
                        <div class="movie-status">
                            <span class="status-label">Trạng thái:</span>
                            <span class="status-badge">{{$res['status']}}</span>
                        </div>

                        <div class="movie-episodes">
                            <span class="episodes-label">Tập hiện tại:</span>
                            <span class="episodes-badge">{{$res['episode_current']}}</span>
                        </div>
                    </div>

                    @if($res['trailer_url'])
                    <div class="movie-trailer-btn">
                        <a href="{{$res['trailer_url']}}" class="pulse-btn" target="_blank">
                            <i class="fas fa-play"></i> Xem trailer
                        </a>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="movie-content">
            <!-- Tab Navigation -->
            <div class="movie-tabs-nav">
                <button class="tab-btn active" data-tab="info">
                    <i class="fas fa-info-circle"></i> Thông tin
                </button>
                <button class="tab-btn" data-tab="content">
                    <i class="fas fa-align-left"></i> Nội dung
                </button>
                <button class="tab-btn" data-tab="cast">
                    <i class="fas fa-users"></i> Diễn viên
                </button>
                <button class="tab-btn" data-tab="media">
                    <i class="fas fa-photo-video"></i> Hình ảnh
                </button>
            </div>

            <!-- Tab Content -->
            <div class="tab-content" id="tab-info" style="display: block;" data-aos="fade-up">
                <div class="content-section">
                    <div class="section-header">
                        <i class="fas fa-info-circle"></i>
                        <h3>Thông tin cơ bản</h3>
                    </div>
                    <div class="section-content">
                        <div class="info-grid">
                            <div class="info-item" data-aos="fade-right" data-aos-delay="100">
                                <div class="info-label"><i class="fas fa-fingerprint"></i> ID:</div>
                                <div class="info-value long-text">{{$res['_id']}}</div>
                            </div>
                            <div class="info-item" data-aos="fade-right" data-aos-delay="150">
                                <div class="info-label"><i class="fas fa-link"></i> Slug:</div>
                                <div class="info-value">{{$res['slug']}}</div>
                            </div>
                            <div class="info-item" data-aos="fade-right" data-aos-delay="200">
                                <div class="info-label"><i class="fas fa-film"></i> Loại:</div>
                                <div class="info-value badge-type">{{$res['type']}}</div>
                            </div>
                            <div class="info-item" data-aos="fade-right" data-aos-delay="250">
                                <div class="info-label"><i class="fas fa-calendar-week"></i> Showtimes:</div>
                                <div class="info-value">{{$res['showtimes']}}</div>
                            </div>
                            <div class="info-item" data-aos="fade-right" data-aos-delay="300">
                                <div class="info-label"><i class="fas fa-bell"></i> Notify:</div>
                                <div class="info-value">{{$res['notify']}}</div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="grid-section">
                    <div class="content-section" data-aos="fade-up" data-aos-delay="100">
                        <div class="section-header">
                            <i class="fas fa-tag"></i>
                            <h3>Thể loại</h3>
                        </div>
                        <div class="section-content">
                            <div class="tags-container">
                                @foreach ($res['category'] as $category)
                                <span class="tag category-tag">{{$category['name']}}</span>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <div class="content-section" data-aos="fade-up" data-aos-delay="200">
                        <div class="section-header">
                            <i class="fas fa-flag"></i>
                            <h3>Quốc gia</h3>
                        </div>
                        <div class="section-content">
                            <div class="tags-container">
                                @foreach ($res['country'] as $country)
                                <span class="tag country-tag">{{$country['name']}}</span>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="tab-content" id="tab-content" data-aos="fade-up">
                <div class="content-section">
                    <div class="section-header">
                        <i class="fas fa-align-left"></i>
                        <h3>Nội dung</h3>
                    </div>
                    <div class="section-content">
                        <div class="movie-synopsis">
                            {{$res['content']}}
                        </div>
                    </div>
                </div>
            </div>

            <div class="tab-content" id="tab-cast">
                <div class="content-section" data-aos="fade-up">
                    <div class="section-header">
                        <i class="fas fa-users"></i>
                        <h3>Diễn viên</h3>
                    </div>
                    <div class="section-content">
                        <div class="cast-grid">
                            @if(count($res['actor']) > 0)
                            @foreach ($res['actor'] as $actor)
                            <div class="actor-card" data-aos="zoom-in" data-aos-delay="{{$loop->index * 50}}">
                                <div class="actor-placeholder">
                                    <i class="fas fa-user"></i>
                                </div>
                                <div class="actor-name">{{$actor}}</div>
                            </div>
                            @endforeach
                            @else
                            <div class="no-data">Chưa cập nhật thông tin diễn viên</div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div class="tab-content" id="tab-media">
                <div class="content-section" data-aos="fade-up">
                    <div class="section-header">
                        <i class="fas fa-photo-video"></i>
                        <h3>Phương tiện</h3>
                    </div>
                    <div class="section-content">
                        <div class="media-grid">
                            <div class="media-card poster" data-aos="zoom-in">
                                <div class="media-card-header">Poster</div>
                                <div class="media-img">
                                    <img src="{{$res['poster_url']}}" alt="{{$res['name']}} - Poster">
                                </div>
                            </div>
                            <div class="media-card thumbnail" data-aos="zoom-in" data-aos-delay="100">
                                <div class="media-card-header">Thumbnail</div>
                                <div class="media-img">
                                    <img src="{{$res['thumb_url']}}" alt="{{$res['name']}} - Thumbnail">
                                </div>
                            </div>

                            @if($res['trailer_url'])
                            <div class="media-card trailer" data-aos="zoom-in" data-aos-delay="200">
                                <div class="media-card-header">Trailer</div>
                                <div class="trailer-container">
                                    <a href="{{$res['trailer_url']}}" class="trailer-button" target="_blank">
                                        <i class="fas fa-play-circle"></i> Xem trailer
                                    </a>
                                    <div class="trailer-url">{{$res['trailer_url']}}</div>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
            // Khởi tạo thư viện AOS (Animation On Scroll)
            AOS.init({
                duration: 800,
                once: true
            });
            
            // Xử lý tabs
            const tabButtons = document.querySelectorAll('.tab-btn');
            const tabContents = document.querySelectorAll('.tab-content');
            
            tabButtons.forEach(button => {
                button.addEventListener('click', function() {
                    // Xóa active class khỏi tất cả các tabs
                    tabButtons.forEach(btn => btn.classList.remove('active'));
                    tabContents.forEach(content => content.style.display = 'none');
                    
                    // Thêm active class cho tab được click
                    this.classList.add('active');
                    
                    // Hiển thị nội dung tương ứng
                    const tabId = 'tab-' + this.getAttribute('data-tab');
                    document.getElementById(tabId).style.display = 'block';
                });
            });
            
            // Xử lý hiệu ứng zoom poster
            const posterZoom = document.querySelector('.poster-zoom');
            const poster = document.querySelector('.movie-poster img');
            
            if(posterZoom && poster) {
                posterZoom.addEventListener('click', function() {
                    Swal.fire({
                        imageUrl: poster.src,
                        imageAlt: poster.alt,
                        imageWidth: 'auto',
                        imageHeight: 'auto',
                        showConfirmButton: false,
                        background: 'rgba(0, 0, 0, 0.9)',
                        showCloseButton: true
                    });
                });
            }
            
            // Hiệu ứng cho các nút hành động
            const actionButtons = document.querySelectorAll('.action-btn');
            actionButtons.forEach(button => {
                button.addEventListener('click', function() {
                    this.classList.add('button-clicked');
                    
                    // Nếu là nút thêm phim hoặc xóa phim, thêm hiệu ứng loading
                    if (this.closest('.add-movie-form') || this.closest('.destroy-movie-form')) {
                        const originalText = this.innerHTML;
                        this.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Đang xử lý...';
                        
                        // Khôi phục nội dung nút nếu form không được submit
                        setTimeout(() => {
                            if (this.classList.contains('button-clicked')) {
                                this.innerHTML = originalText;
                                this.classList.remove('button-clicked');
                            }
                        }, 5000);
                    }
                });
            });
            
            // Tự động ẩn thông báo sau 5 giây
            const notification = document.getElementById('notification');
            if (notification) {
                setTimeout(() => {
                    closeNotification();
                }, 5000);
            }
        });
        
        // Hàm đóng thông báo
        function closeNotification() {
            const notification = document.getElementById('notification');
            if (notification) {
                notification.style.animation = 'slideOut 0.5s ease-out forwards';
                setTimeout(() => {
                    notification.remove();
                }, 500);
            }
        }
</script>
@endsection