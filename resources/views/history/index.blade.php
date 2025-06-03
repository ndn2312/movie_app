@extends('layout')

@section('content')
<div class="history-container">
    <div class="card">
        <div class="card-header">
            <h2><i class="fas fa-history"></i> Lịch sử xem phim</h2>
        </div>
        <div class="card-body">
            @if($historyItems->isEmpty())
            <div class="empty-history">
                <i class="fas fa-film fa-3x"></i>
                <h3>Bạn chưa xem phim nào</h3>
                <p>Hãy khám phá kho phim của chúng tôi và quay lại đây để xem lịch sử xem phim của bạn.</p>
                <a href="{{ route('homepage') }}" class="btn btn-primary">Khám phá ngay</a>
            </div>
            @else
            <div class="history-list">
                @foreach($historyItems as $item)
                <div class="history-item">
                    <div class="history-thumbnail">
                        <a href="{{ route('movie', $item->movie->slug) }}">
                            <img src="{{ $item->movie->poster_path }}" alt="{{ $item->movie->title }}">
                        </a>
                    </div>
                    <div class="history-details">
                        <h4><a href="{{ route('movie', $item->movie->slug) }}">{{ $item->movie->title }}</a></h4>
                        <div class="metadata">
                            <span class="watched-time">
                                <i class="fas fa-clock"></i> Xem lần cuối: {{ $item->updated_at->diffForHumans() }}
                            </span>
                            <span class="duration">
                                <i class="fas fa-stopwatch"></i> Xem đến: {{ gmdate("H:i:s", $item->watch_time) }}
                            </span>
                        </div>
                        <div class="progress">
                            <div class="progress-bar" role="progressbar"
                                style="width: {{ ($item->watch_time / $item->movie->duration) * 100 }}%"
                                aria-valuenow="{{ ($item->watch_time / $item->movie->duration) * 100 }}"
                                aria-valuemin="0" aria-valuemax="100">
                            </div>
                        </div>
                        <div class="history-actions">
                            <a href="{{ route('movie', $item->movie->slug) }}" class="btn btn-sm btn-primary">
                                <i class="fas fa-play"></i> Tiếp tục xem
                            </a>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            <div class="pagination-container">
                {{ $historyItems->links() }}
            </div>
            @endif
        </div>
    </div>
</div>
@endsection