@extends('layouts.app')

@section('content')
<style>
    /* Nút tùy chỉnh */
    .button-custom {
        width: 200px;
        height: 50px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
        font-weight: bold;
        border-radius: 10px;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);
        text-decoration: none;
        transition: all 0.3s ease-in-out;
        max-width: 400px;
        margin: auto;
        color: white;
    }

    .button-custom:hover {
        transform: scale(1.1);
        box-shadow: 0 6px 15px rgba(0, 0, 0, 0.4);
    }

    /* Phim nút */
    .button-custom {
        background: linear-gradient(90deg, #ff416c, #ff4b2b);
    }

    .button-custom:hover {
        background: linear-gradient(90deg, #ff4b2b, #ff416c);
    }

    .button-add {
        background: linear-gradient(90deg, #4CAF50, #2196F3);
        color: #FFD700;
        text-shadow: 0 1px 2px rgba(0, 0, 0, 0.3);
    }

    .button-add:hover {
        background: linear-gradient(90deg, #2196F3, #4CAF50);
        box-shadow: 0 6px 15px rgba(33, 150, 243, 0.4);
    }

    /* Các style thông báo chung */
    .notification-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.65);
        display: flex;
        justify-content: center;
        align-items: center;
        z-index: 9999;
        backdrop-filter: blur(8px);
        animation: fadeIn 0.3s ease-out;
    }

    .notification-card {
        background: #ffffff;
        border-radius: 12px;
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.15);
        width: 320px;
        overflow: hidden;
        display: flex;
        flex-direction: column;
        animation: slideIn 0.4s ease-out;
    }

    .icon-container {
        padding: 20px 0 10px;
        display: flex;
        justify-content: center;
    }

    .notification-content {
        padding: 0 20px 20px;
        text-align: center;
    }

    .notification-icon {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        display: block;
        stroke-width: 2;
        stroke-miterlimit: 10;
        animation: scale .3s ease-in-out .9s both;
    }

    /* Tiêu đề thông báo */
    .notification-title {
        font-size: 20px;
        font-weight: 600;
        margin: 0 0 10px;
    }

    /* Nội dung thông báo */
    .notification-message {
        color: #4a4a4a;
        font-size: 14px;
        line-height: 1.4;
        margin-bottom: 12px;
    }

    /* Container đếm ngược */
    .countdown-container {
        display: flex;
        justify-content: center;
        align-items: center;
        margin-bottom: 15px;
        color: #757575;
        font-size: 13px;
    }

    .countdown-number {
        background-color: rgba(220, 53, 69, 0.1);
        color: #dc3545;
        width: 22px;
        height: 22px;
        line-height: 22px;
        text-align: center;
        border-radius: 50%;
        font-weight: bold;
        margin: 0 4px;
        animation: pulse 1s infinite;
    }

    /* Nút đóng thông báo */
    .notification-button {
        border: none;
        border-radius: 6px;
        padding: 8px 24px;
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .notification-button:hover {
        transform: translateY(-1px);
    }

    /* Style đặc biệt cho từng loại thông báo */
    /* Thành công */
    .success-checkmark {
        stroke: #4caf50;
        animation: fill .4s ease-in-out .4s forwards, scale .3s ease-in-out .9s both;
    }

    .success-title {
        color: #4caf50;
    }

    .success-button {
        background-color: #4caf50;
        color: white;
        box-shadow: 0 2px 6px rgba(76, 175, 80, 0.25);
    }

    .success-button:hover {
        background-color: #43a047;
        box-shadow: 0 3px 8px rgba(76, 175, 80, 0.35);
    }

    /* Cập nhật */
    .update-checkmark {
        stroke: #ffc107;
    }

    .update-title {
        color: #ffc107;
    }

    .update-button {
        background-color: #ffc107;
        color: #212121;
        box-shadow: 0 2px 6px rgba(255, 193, 7, 0.25);
    }

    .update-button:hover {
        background-color: #ffb300;
        box-shadow: 0 3px 8px rgba(255, 193, 7, 0.35);
    }

    /* Xóa */
    .delete-checkmark {
        stroke: #d32f2f;
        box-shadow: inset 0px 0px 0px #d32f2f;
        animation: fill-red .4s ease-in-out .4s forwards, scale .3s ease-in-out .9s both;
    }

    .delete-title {
        color: #d32f2f;
    }

    .delete-button {
        background-color: #d32f2f;
        color: white;
        box-shadow: 0 2px 6px rgba(211, 47, 47, 0.25);
    }

    .delete-button:hover {
        background-color: #b71c1c;
        box-shadow: 0 3px 8px rgba(211, 47, 47, 0.35);
    }

    /* Lỗi */
    .error-icon {
        stroke: #e74c3c;
        animation: error-fill .4s ease-in-out .4s forwards, scale .3s ease-in-out .9s both;
    }

    .error-title {
        color: #e74c3c;
    }

    .error-button {
        background-color: #e74c3c;
        color: white;
        box-shadow: 0 2px 6px rgba(231, 76, 60, 0.25);
    }

    .error-button:hover {
        background-color: #c0392b;
        box-shadow: 0 3px 8px rgba(231, 76, 60, 0.35);
    }

    /* Style cho element chung */
    .checkmark-circle,
    .error-circle {
        stroke-dasharray: 166;
        stroke-dashoffset: 166;
        stroke-width: 2;
        stroke-miterlimit: 10;
        fill: none;
        animation: stroke 0.6s cubic-bezier(0.65, 0, 0.45, 1) forwards;
    }

    .checkmark-check {
        transform-origin: 50% 50%;
        stroke-dasharray: 48;
        stroke-dashoffset: 48;
        stroke-width: 3;
        animation: stroke 0.3s cubic-bezier(0.65, 0, 0.45, 1) 0.8s forwards;
    }

    .error-x {
        transform-origin: 50% 50%;
        stroke-dasharray: 48;
        stroke-dashoffset: 48;
        stroke-width: 3;
        stroke: #e74c3c;
        animation: stroke 0.7s cubic-bezier(0.65, 0, 0.45, 1) 0.8s forwards;
    }

    /* Style cho đoạn text được highlight */
    .highlighted-title {
        font-weight: 700;
        color: #1e88e5;
        background: linear-gradient(to bottom, transparent 60%, rgba(76, 175, 80, 0.2) 40%);
        padding: 0 3px;
        border-radius: 3px;
        display: inline-block;
        text-shadow: 0 1px 1px rgba(255, 255, 255, 0.7);
    }

    .highlighted-title-delete {
        font-weight: 700;
        color: #c62828;
        background: linear-gradient(to bottom, transparent 60%, rgba(220, 53, 69, 0.2) 40%);
        padding: 0 3px;
        border-radius: 3px;
        display: inline-block;
        text-shadow: 0 1px 1px rgba(255, 255, 255, 0.7);
    }

    .highlighted-title-error {
        font-weight: 700;
        color: #e74c3c;
        background: linear-gradient(to bottom, transparent 60%, rgba(231, 76, 60, 0.2) 40%);
        padding: 0 3px;
        border-radius: 3px;
        display: inline-block;
        text-shadow: 0 1px 1px rgba(255, 255, 255, 0.7);
    }

    /* Style cho highlight action */
    .action-highlight {
        font-weight: 800;
        font-size: 15px;
        padding: 2px 8px;
        border-radius: 4px;
        display: inline-block;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: white;
    }

    .add-action {
        background-color: #4caf50;
    }

    .update-action {
        background-color: #ffc107;
        color: #212121;
    }

    .delete-action {
        color: white;
        background-color: #d32f2f;
        box-shadow: 0 2px 5px rgba(211, 47, 47, 0.5);
        animation: action-pulse 2s infinite;
    }

    .error-action {
        background-color: #e74c3c;
        box-shadow: 0 2px 5px rgba(231, 76, 60, 0.5);
        animation: action-pulse 2s infinite;
    }

    /* Animations */
    @keyframes fill {
        100% {
            box-shadow: inset 0px 0px 0px 30px rgba(76, 175, 80, 0.1);
        }
    }

    @keyframes fill-red {
        100% {
            box-shadow: inset 0px 0px 0px 30px rgba(211, 47, 47, 0.1);
        }
    }

    @keyframes error-fill {
        100% {
            box-shadow: inset 0px 0px 0px 30px rgba(231, 76, 60, 0.1);
        }
    }

    @keyframes stroke {
        100% {
            stroke-dashoffset: 0;
        }
    }

    @keyframes scale {

        0%,
        100% {
            transform: none;
        }

        50% {
            transform: scale3d(1.1, 1.1, 1);
        }
    }

    @keyframes action-pulse {

        0%,
        100% {
            transform: scale(1);
        }

        50% {
            transform: scale(1.05);
            box-shadow: 0 3px 8px rgba(231, 76, 60, 0.7);
        }
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
        }

        to {
            opacity: 1;
        }
    }

    @keyframes fadeOut {
        from {
            opacity: 1;
        }

        to {
            opacity: 0;
        }
    }

    @keyframes slideIn {
        from {
            transform: translateY(20px);
            opacity: 0;
        }

        to {
            transform: translateY(0);
            opacity: 1;
        }
    }

    @keyframes pulse {

        0%,
        100% {
            transform: scale(1);
        }

        50% {
            transform: scale(1.1);
        }
    }
</style>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">Quản lý tập phim</div>

                <div class="card-body">
                    @if(session('success') || session('error'))
                    <div class="position-fixed top-0 end-0 mt-3 me-3 alert alert-{{ session('success') ? 'success' : 'danger' }} alert-dismissible fade show small p-2"
                        role="alert" style="max-width: 250px;">
                        <strong>{{ session('success') ? '✔' : '✖' }}</strong> {{ session('success') ?? session('error')
                        }}
                    </div>
                    @endif

                    <script>
                        setTimeout(() => document.querySelectorAll('.alert').forEach(alert => alert.remove()), 3000);
                    </script>

                    @if (session('status'))
                    <div class="alert alert-success" role="alert">
                        {{ session('status') }}
                    </div>
                    @endif

                    @if(!isset($episode))
                    {!! Form::open(['route' => 'episode.store', 'method'=>'POST','enctype'=>'multipart/form-data']) !!}
                    @else
                    {!! Form::open(['route' => ['episode.update', $episode->id],
                    'method'=>'PUT','enctype'=>'multipart/form-data']) !!}
                    @endif

                    <div class="form-group">
                        {!! Form::label('movie_title','Tên phim',['class' => 'd-block mb-2']) !!}
                        {!! Form::text('movie_title',isset($movie)? $movie->title:'',
                        ['class'=>'form-control','readonly']) !!}
                        {!! Form::hidden('movie_id',isset($movie)? $movie->id:'') !!}
                    </div>
                    <br>
                    <div class="form-group">
                        {!! Form::label('link','Link phim',['class' => 'd-block mb-2']) !!}
                        {!! Form::text('link',isset($episode)? $episode->linkphim:'', ['class'=>'form-control',
                        'placeholder'=>'Nhập vào link iframe hoặc để trống nếu upload file']) !!}
                    </div>

                    <div class="form-group mt-3">
                        <label for="video_file" class="d-block mb-2">Hoặc upload video</label>
                        <input type="file" name="video_file" id="video_file" class="form-control"
                            accept="video/mp4,video/x-m4v,video/*">
                        @if(isset($episode) && strpos($episode->linkphim, '<iframe')===false) <div class="mt-2">
                            <small class="text-success">Video hiện tại: {{ basename($episode->linkphim) }}</small>
                    </div>
                    @endif
                </div>
                <br>
                @if(!isset($episode))
                <div class="form-group">
                    {!! Form::label('episode','Tập phim',[]) !!}
                    @if($movie->thuocphim == 'phimbo')
                    {!! Form::selectRange('episode',1, $movie->sotap, $movie->sotap, ['class'=>'form-control']) !!}
                    @else
                    <select name="episode" class="form-control">
                        <option value="HD">HD</option>
                        <option value="FullHD">FullHD</option>
                        <option value="Cam">Cam</option>
                    </select>
                    @endif
                </div>
                @else
                <div class="form-group">
                    {!! Form::label('episode','Tập phim',[]) !!}
                    {!! Form::text('episode',isset($episode)? $episode->episode:'',
                    ['class'=>'form-control','placeholder'=>'Nhập vào dữ liệu...',isset($episode)? 'readonly':''])
                    !!}
                </div>
                @endif

                <br>
                @if(!isset($episode))
                {!! Form::submit('Thêm tập phim',['class'=>'btn btn-success']) !!}
                @else
                {!! Form::submit('Cập nhật tập phim',['class'=>'btn btn-success']) !!}
                @endif
                {!! Form::close() !!}
            </div>
        </div>
    </div>

    <!-- Danh sách tập phim -->
    <div class="col-md-12">
        <a href="{{route('episode.index')}}" class="button-custom">
            <i>🎬 DS.TẬP PHIM</i>
        </a>
        <br>
        <a href="{{route('movie.index')}}" class="button-custom">
            <i>🎬 DANH SÁCH PHIM</i>
        </a>

        <table class="table table-responsive" id="tablephim">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Tên phim</th>
                    <th scope="col">Ảnh phim</th>
                    <th scope="col">Tập phim</th>
                    <th scope="col">Link phim</th>
                    <th scope="col">Quản lý</th>
                </tr>
            </thead>
            <tbody>
                @foreach($list_episode as $key => $episode)
                <tr>
                    <th scope="row">{{$key}}</th>
                    <td>{{$episode->movie->title}}</td>
                    <td>
                        <img src="{{ asset('uploads/movie/'.$episode->movie->image) }}"
                            style="width: 100px; height: auto; object-fit: cover;">
                    </td>
                    <td>{{$episode->episode}}</td>
                    <td>{{$episode->linkphim}}</td>
                    <td>
                        {!! Form::open(['method'=>'DELETE','route'=>['episode.destroy',$episode->id]]) !!}
                        {!! Form::submit('Xóa', ['class' => 'btn btn-danger', 'onsubmit' => 'return confirm()']) !!}
                        {!! Form::close() !!}
                        <br>
                        <a href="{{route('episode.edit', $episode->id)}}" class="btn btn-warning">Sửa</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
</div>

<!-- Các thông báo -->
@if(session('action_type') == 'xóa' || session('action_type') == 'thêm' || session('action_type') == 'cập nhật')
<div class="notification-overlay" id="{{ session('action_type') == 'xóa' ? 'deleteSuccessPopup' : 'successPopup' }}">
    <div class="notification-card">
        <div class="icon-container">
            <svg class="notification-icon {{ session('action_type') == 'xóa' ? 'delete-checkmark' : (session('action_type') == 'thêm' ? 'success-checkmark' : 'update-checkmark') }}"
                xmlns="http://www.w3.org/2000/svg" viewBox="0 0 52 52">
                <circle class="checkmark-circle" cx="26" cy="26" r="25" fill="none" />
                <path class="checkmark-check" fill="none" d="M14.1 27.2l7.1 7.2 16.7-16.8" />
            </svg>
        </div>

        <div class="notification-content">
            <h2
                class="notification-title {{ session('action_type') == 'xóa' ? 'delete-title' : (session('action_type') == 'cập nhật' ? 'update-title' : 'success-title') }}">
                Thành công!</h2>

            <p class="notification-message">
                Tập phim "<span
                    class="{{ session('action_type') == 'xóa' ? 'highlighted-title-delete' : 'highlighted-title' }}">{{
                    session('movie_title') }}</span>"
                {{ session('action_type') == 'xóa' ? session('delete_message') : session('success_message') }}
                <span
                    class="action-highlight {{ session('action_type') == 'xóa' ? 'delete-action' : (session('action_type') == 'thêm' ? 'add-action' : 'update-action') }}">{{
                    session('action_type') }}</span>
                {{ session('action_type') == 'xóa' ? session('delete_end') : session('success_end') }}
            </p>

            <div class="countdown-container">
                <span>Tự động đóng sau </span>
                <span class="countdown-number" id="notificationCountdown">3</span>
                <span> giây</span>
            </div>

            <button
                class="notification-button {{ session('action_type') == 'xóa' ? 'delete-button' : (session('action_type') == 'thêm' ? 'success-button' : 'update-button') }}"
                id="closeNotificationBtn">OK</button>
        </div>
    </div>
</div>
@endif

<!-- Thông báo lỗi trùng lặp -->
@if(session('error_duplicate'))
<div class="notification-overlay" id="duplicateErrorPopup">
    <div class="notification-card">
        <div class="icon-container">
            <svg class="notification-icon error-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 52 52">
                <circle class="error-circle" cx="26" cy="26" r="25" fill="none" />
                <path class="error-x" fill="none" d="M16 16 36 36 M36 16 16 36" />
            </svg>
        </div>

        <div class="notification-content">
            <h2 class="notification-title error-title">Tập phim trùng lặp!</h2>

            <p class="notification-message">
                Tập phim "<span class="highlighted-title-error">{{ session('movie_title') }}</span>"
                {{ session('error_message') }}
                <span class="action-highlight error-action">{{ session('action_type') }}</span>
                {{ session('error_end') }}
            </p>

            <div class="countdown-container">
                <span>Tự động đóng sau </span>
                <span class="countdown-number" id="errorCountdown">3</span>
                <span> giây</span>
            </div>

            <button class="notification-button error-button" id="closeErrorBtn">Đóng</button>
        </div>
    </div>
</div>
@endif

<script>
    document.addEventListener('DOMContentLoaded', function() {
    // Xử lý chung cho tất cả các loại thông báo
    function setupNotification(popupId, countdownId, closeBtnId) {
        const popup = document.getElementById(popupId);
        if (!popup) return;
        
        const countdownElement = document.getElementById(countdownId);
        const closeBtn = document.getElementById(closeBtnId);
        let secondsLeft = 3;
        
        // Thiết lập sự kiện
        if (closeBtn) {
            closeBtn.addEventListener('click', () => closePopup(popup));
        }
        
        popup.addEventListener('click', e => {
            if (e.target === popup) closePopup(popup);
        });
        
        document.addEventListener('keydown', e => {
            if ((e.key === 'Enter' || e.key === 'Escape') && popup.style.display !== 'none') {
                closePopup(popup);
            }
        });
        
        // Đếm ngược
        const interval = setInterval(() => {
            secondsLeft--;
            if (countdownElement) countdownElement.textContent = secondsLeft;
            if (secondsLeft <= 0) {
                clearInterval(interval);
                closePopup(popup);
            }
        }, 1000);
        
        // Lưu interval ID để có thể clear
        popup.dataset.intervalId = interval;
        
        // Hàm đóng popup
        function closePopup(element) {
            clearInterval(element.dataset.intervalId);
            element.style.animation = 'fadeOut 0.3s forwards';
            setTimeout(() => element.style.display = 'none', 300);
        }
    }
    
    // Khởi tạo các notification
    if (document.getElementById('successPopup') || document.getElementById('deleteSuccessPopup')) {
        const isDeleteAction = "{{ session('action_type') }}" === "xóa";
        setupNotification(
            isDeleteAction ? 'deleteSuccessPopup' : 'successPopup',
            'notificationCountdown',
            'closeNotificationBtn'
        );
    }
    
    if (document.getElementById('duplicateErrorPopup')) {
        setupNotification('duplicateErrorPopup', 'errorCountdown', 'closeErrorBtn');
    }
});
</script>
@endsection