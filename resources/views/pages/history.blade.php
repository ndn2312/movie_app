@extends('layout')
@section('content')
<div class="container">
    <div class="section-bar clearfix">
        <h3 class="section-title"><span>Lịch sử xem phim</span></h3>
    </div>

    <div class="history-actions text-right" style="margin-bottom: 20px;">
        <button id="clear-history" class="btn btn-danger">
            <i class="fas fa-trash-alt"></i> Xóa tất cả lịch sử
        </button>
    </div>

    @if($histories->isEmpty())
    <div class="empty-history">
        <div class="text-center" style="padding: 40px 0; background: rgba(0, 0, 0, 0.1); border-radius: 8px;">
            <i class="fas fa-history" style="font-size: 50px; color: #666; margin-bottom: 15px;"></i>
            <h4 style="color: #fff;">Bạn chưa xem phim nào</h4>
            <p style="color: #aaa;">Các phim bạn đã xem sẽ hiển thị ở đây</p>
            <a href="{{ route('homepage') }}" class="btn btn-primary" style="margin-top: 15px;">
                <i class="fas fa-home"></i> Khám phá phim ngay
            </a>
        </div>
    </div>
    @else
    <div class="row history-list">
        @foreach($histories as $history)
        <div class="col-lg-3 col-md-4 col-sm-6 col-xs-12 history-item" data-id="{{ $history->id }}">
            <div class="history-card">
                <a href="{{ route('watch', ['slug' => $history->movie->slug, 'tap' => 'tap-'.$history->episode]) }}"
                    class="history-thumb">
                    <div class="poster-container">
                        @php
                        $image = $history->thumbnail;
                        if (!Str::startsWith($image, ['http://', 'https://'])) {
                        $image = asset('uploads/movie/'.$image);
                        }
                        @endphp
                        <img src="{{ $image }}" alt="{{ $history->movie->title }}" class="img-responsive">
                    </div>
                </a>

                <button class="remove-history" data-id="{{ $history->id }}">
                    <i class="fas fa-times"></i>
                </button>

                <div class="history-info">
                    <h4 class="movie-title">{{ $history->movie->title }}</h4>
                    <div class="history-meta">
                        <span>
                            @if($history->episode)
                            {{ $history->episode }}
                            @else
                            Full
                            @endif
                        </span>
                        <span>{{ \Carbon\Carbon::parse($history->last_watched_at)->diffForHumans() }}</span>
                    </div>

                    <!-- Progress bar and time info moved here -->
                    <div class="progress-container">
                        <div class="watch-time">
                            @php
                            $currentTime = gmdate("H\\h i\\m", $history->current_time);
                            $totalTime = gmdate("H\\h i\\m", $history->duration);

                            // Nếu thời gian dưới 1 giờ, hiển thị định dạng khác
                            if ($history->current_time < 3600) { $currentTime=gmdate("i\\m", $history->current_time);
                                }
                                if ($history->duration < 3600) { $totalTime=gmdate("i\\m", $history->duration);
                                    }
                                    @endphp
                                    {{ $currentTime }} / {{ $totalTime }}
                        </div>
                        <div class="progress">
                            <div class="progress-bar" role="progressbar"
                                style="width: {{ $history->progress }}%; background: #04c1e7;"
                                aria-valuenow="{{ $history->progress }}" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Phân trang -->
    <div class="pagination-wrapper">
        {{ $histories->links() }}
    </div>
    @endif
</div>

<script>
    $(document).ready(function() {
        // Xóa một mục lịch sử
        $('.remove-history').on('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            const historyId = $(this).data('id');
            const historyItem = $(this).closest('.history-item');
            
            if (confirm('Bạn có chắc muốn xóa mục này khỏi lịch sử xem?')) {
                $.ajax({
                    url: "{{ url('/history/remove') }}/" + historyId,
                    type: "DELETE",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        if (response.success) {
                            // Xóa phần tử khỏi DOM với hiệu ứng
                            historyItem.fadeOut(300, function() {
                                $(this).remove();
                                
                                // Kiểm tra nếu không còn mục nào
                                if ($('.history-item').length === 0) {
                                    // Hiển thị thông báo trống
                                    const emptyMessage = `
                                        <div class="empty-history">
                                            <div class="text-center" style="padding: 40px 0; background: rgba(0, 0, 0, 0.1); border-radius: 8px;">
                                                <i class="fas fa-history" style="font-size: 50px; color: #666; margin-bottom: 15px;"></i>
                                                <h4 style="color: #fff;">Bạn chưa xem phim nào</h4>
                                                <p style="color: #aaa;">Các phim bạn đã xem sẽ hiển thị ở đây</p>
                                                <a href="{{ route('homepage') }}" class="btn btn-primary" style="margin-top: 15px;">
                                                    <i class="fas fa-home"></i> Khám phá phim ngay
                                                </a>
                                            </div>
                                        </div>
                                    `;
                                    $('.history-list').replaceWith(emptyMessage);
                                    $('.history-actions').hide();
                                    $('.pagination-wrapper').hide();
                                }
                            });
                        }
                    }
                });
            }
        });
        
        // Xóa tất cả lịch sử
        $('#clear-history').on('click', function() {
            if (confirm('Bạn có chắc muốn xóa tất cả lịch sử xem?')) {
                $.ajax({
                    url: "{{ route('history.clear') }}",
                    type: "DELETE",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        if (response.success) {
                            // Tải lại trang
                            location.reload();
                        }
                    }
                });
            }
        });
    });
</script>

<style>
    .history-card {
        margin-bottom: 20px;
        background: #1c1c1c;
        border-radius: 8px;
        overflow: hidden;
        position: relative;
        transition: all 0.3s ease;
        height: 100%;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.4);
        max-width: 200px;
        margin-left: auto;
        margin-right: auto;
    }

    .history-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.5);
    }

    .poster-container {
        position: relative;
        width: 100%;
    }

    .poster-container img {
        width: 100%;
        height: 220px;
        object-fit: cover;
        display: block;
    }

    .progress-container {
        margin-top: 10px;
    }

    .progress {
        height: 5px;
        margin: 5px 0 0;
        background: rgba(0, 0, 0, 0.5);
        border-radius: 0;
    }

    .history-info {
        padding: 12px;
    }

    .movie-title {
        color: #fff;
        font-size: 16px;
        font-weight: 600;
        margin: 0 0 5px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .history-meta {
        display: flex;
        justify-content: space-between;
        color: #aaa;
        font-size: 13px;
        margin-bottom: 8px;
    }

    .remove-history {
        position: absolute;
        top: 10px;
        right: 10px;
        background: rgba(0, 0, 0, 0.6);
        color: #fff;
        border: none;
        width: 30px;
        height: 30px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        z-index: 5;
        opacity: 0;
        transition: opacity 0.2s ease, background 0.2s ease;
    }

    .history-card:hover .remove-history {
        opacity: 1;
    }

    .remove-history:hover {
        background: rgba(255, 0, 0, 0.7) !important;
    }

    .pagination {
        justify-content: center;
        margin-top: 20px;
    }

    .watch-time {
        color: #aaa;
        font-size: 12px;
        font-weight: 400;
        margin-bottom: 2px;
        text-align: right;
        font-family: 'Roboto Mono', monospace;
    }

    /* Responsive adjustments */
    @media (max-width: 1200px) {
        .history-card {
            max-width: 180px;
        }
    }

    @media (max-width: 767px) {
        .col-sm-6.col-xs-12.history-item {
            padding-left: 7px;
            padding-right: 7px;
        }

        .history-card {
            max-width: 160px;
        }

        .poster-container img {
            height: 180px;
        }
    }

    @media (max-width: 480px) {
        .col-xs-12.history-item {
            width: 50%;
            float: left;
        }

        .history-card {
            max-width: 140px;
        }

        .poster-container img {
            height: 160px;
        }

        .movie-title {
            font-size: 14px;
        }
    }
</style>
@endsection