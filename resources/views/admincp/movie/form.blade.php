@extends('layouts.app')

@section('content')
<style>
    .button-custom {
        width: 200px; /* Điều chỉnh kích thước theo ý muốn */
        height: 50px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
        font-weight: bold;
        border-radius: 10px;
        background: linear-gradient(90deg, #ff416c, #ff4b2b);
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);
        color: white;
        text-decoration: none;
        transition: all 0.3s ease-in-out;
        max-width: 400px;
        margin:auto;


    }
    .button-custom:hover{
        background: linear-gradient(90deg, #ff4b2b, #ff416c);
        transform: scale(1.1);
        box-shadow: 0 6px 15px rgba(0, 0, 0, 0.4);
    } 
    /*css them phim*/
    /*css them phim*/
    .button-customadd {
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
        
        /* Thêm màu nền gradient xanh */
        background: linear-gradient(90deg, #4CAF50, #2196F3);
        /* Thêm màu chữ vàng */
        color: #FFD700;
        /* Thêm đổ bóng cho chữ */
        text-shadow: 0 1px 2px rgba(0,0,0,0.3);
    }

    /* Thêm hiệu ứng hover */
    .button-customadd:hover {
        background: linear-gradient(90deg, #2196F3, #4CAF50);
        transform: scale(1.1);
        box-shadow: 0 6px 15px rgba(33, 150, 243, 0.4);
    }


</style>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <!-- Container sử dụng flex-start thay vì between -->
                <div class="d-flex mb-3" style="gap: 15px;">
                    <!-- Nút THÊM PHIM đặt trước -->
                    @if(isset($movie))
                    <a href="{{route('movie.create')}}" class="button-customadd">
                        <i>➕ THÊM PHIM 🎬</i>
                    </a>
                    
                    @endif
                    
                    <!-- Nút DANH SÁCH PHIM đặt sau -->
                    <a href="{{route('movie.index')}}" class="button-custom">
                        <i>🎬 DANH SÁCH PHIM</i>
                    </a>
                </div>

                <div class="card-header">Quản lý phim</div>
                <!-- Phần còn lại của form -->

                <div class="card-body">
                    @if(session('success') || session('error'))
                    <div class="position-fixed top-0 end-0 mt-3 me-3 alert alert-{{ session('success') ? 'success' : 'danger' }} alert-dismissible fade show small p-2" role="alert" style="max-width: 250px;">
                        <strong>{{ session('success') ? '✔' : '✖' }}</strong> {{ session('success') ?? session('error') }}
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
                    @if(!isset($movie))                    
                        {!! Form::open(['route' => 'movie.store', 'method'=>'POST','enctype'=>'multipart/form-data']) !!}
                    @else    
                        {!! Form::open(['route' => ['movie.update', $movie->id], 'method'=>'PUT','enctype'=>'multipart/form-data']) !!}

                    @endif
                        <div class="form-group">
                            {!! Form::label('title','Tiêu đề',['class' => 'd-block mb-2']) !!}
                            {!! Form::text('title',isset($movie)? $movie->title:'', ['class'=>'form-control','placeholder'=>'Nhập vào dữ liệu...','id'=>'slug','onkeyup'=>'ChangeToSlug()']) !!}
                        </div>
                        <br>
                        <div class="form-group">
                            {!! Form::label('thoiluong','Thời lượng phim',['class' => 'd-block mb-2']) !!}
                            {!! Form::text('thoiluong',isset($movie)? $movie->thoiluong:'', ['class'=>'form-control','placeholder'=>'Nhập vào dữ liệu...']) !!}
                        </div>
                        <br>
                        <div class="form-group">
                            {!! Form::label('tên tiếng anh','Tên tiếng anh',['class' => 'd-block mb-2']) !!}
                            {!! Form::text('name_eng',isset($movie)? $movie->name_eng:'', ['class'=>'form-control','placeholder'=>'Nhập vào dữ liệu...']) !!}
                        </div>
                        <br>
                        <div class="form-group">
                            {!! Form::label('trailer','Trailer',['class' => 'd-block mb-2']) !!}
                            {!! Form::text('trailer',isset($movie)? $movie->trailer:'', ['class'=>'form-control','placeholder'=>'Nhập vào dữ liệu...']) !!}
                        </div>
                        <br>
                        <div class="form-group">
                            {!! Form::label('slug','Đường dẫn',['class' => 'd-block mb-2']) !!}
                            {!! Form::text('slug',isset($movie)? $movie->slug:'', ['class'=>'form-control','placeholder'=>'Nhập vào dữ liệu...','id'=>'convert_slug']) !!}
                        </div>
                        <br>
                        <div class="form-group">
                            {!! Form::label('description','Mô tả',['class' => 'd-block mb-2']) !!}
                            
                            {!! Form::textarea('description',isset($movie)? $movie->description:'', ['style'=>'resize:none','class'=>'form-control','placeholder'=>'Nhập vào dữ liệu...','id'=>'description']) !!}
                        </div>
                        <br>
                        <div class="form-group">
                            {!! Form::label('tags','Tags phim',['class' => 'd-block mb-2']) !!}
                            
                            {!! Form::textarea('tags',isset($movie)? $movie->tags:'', ['style'=>'resize:none','class'=>'form-control','placeholder'=>'Nhập vào dữ liệu...']) !!}
                        </div>
                        <br>
                        <div class="form-group">
                            {!! Form::label('Active','Trạng thái',['class' => 'd-block mb-2']) !!}
                            {!! Form::select('status',['1'=>'Hiển thị','0'=>'Không'], isset($movie) ? $movie->status : '', ['class'=>'form-control']) !!}

                        </div>
                        <br>
                        <div class="form-group">
                            {!! Form::label('resolution','Định dạng',['class' => 'd-block mb-2']) !!}
                            {!! Form::select('resolution',['0'=>'HD','1'=>'SD','2'=>'HDcam','3'=>'Cam','4'=>'FullHD', '5'=>'Trailer'], isset($movie) ? $movie->resolution : '', ['class'=>'form-control']) !!}

                        </div>
                        <br>
                        <div class="form-group">
                            {!! Form::label('phude','Phiên bản',['class' => 'd-block mb-2']) !!}
                            {!! Form::select('phude',['0'=>'Phụ đề','1'=>'Thuyết minh'], isset($movie) ? $movie->phude : '', ['class'=>'form-control']) !!}

                        </div>
                        <br>
                        <div class="form-group">
                            {!! Form::label('Category','Danh mục',['class' => 'd-block mb-2']) !!}
                            {!! Form::select('category_id', $category,isset($movie) ? $movie->category_id : '', ['class'=>'form-control']) !!}

                        </div>
                        <br>
                        <div class="form-group">
                            {!! Form::label('Country','Quốc gia',['class' => 'd-block mb-2']) !!}
                            {!! Form::select('country_id',$country, isset($movie) ? $movie->country_id : '', ['class'=>'form-control']) !!}

                        </div>
                        <br>
                        <div class="form-group">
                            {!! Form::label('Genre','Thể loại',['class' => 'd-block mb-2']) !!}
                            {!! Form::select('genre_id',$genre, isset($movie) ? $movie->genre_id : '', ['class'=>'form-control']) !!}

                        </div>
                        <br>
                        <div class="form-group">
                            {!! Form::label('Hot','Phim Hot',['class' => 'd-block mb-2']) !!}
                            {!! Form::select('phim_hot',['1'=>'Có','0'=>'Không'], isset($movie) ? $movie->phim_hot : '', ['class'=>'form-control']) !!}

                        </div>
                        <br>
                        <div class="form-group">
                            {!! Form::label('image', 'Hình ảnh', ['class' => 'd-block mb-2']) !!}
                            {!! Form::file('image', ['class' => 'form-control-file']) !!}

                            @if(isset($movie))
                                <img src="{{ asset('uploads/movie/'.$movie->image) }}" style="width: 100px; height: auto; object-fit: cover; display: block; margin-top: 10px;">
                            @endif

                        </div>
                        
                        <br>
                        @if(!isset($movie))                    

                            {!! Form::submit('Thêm dữ liệu',['class'=>'btn btn-success']) !!}

                        @else
                            {!! Form::submit('Cập nhật',['class'=>'btn btn-success']) !!}
                        @endif
                    {!! Form::close() !!}

                </div>
            </div>
            
        </div>
    </div>
</div>
@if(session('action_type') == 'thêm' || session('action_type') == 'cập nhật')
<div class="success-notification-overlay" id="successPopup">
    <div class="success-notification-card">
        <div class="success-icon-container">
            <svg class="success-checkmark {{ session('action_type') == 'thêm' ? 'add-icon' : 'update-icon' }}" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 52 52">
                <circle class="checkmark-circle" cx="26" cy="26" r="25" fill="none" />
                <path class="checkmark-check" fill="none" d="M14.1 27.2l7.1 7.2 16.7-16.8" />
            </svg>
        </div>
        <div class="success-notification-content">
            <h2 class="success-title {{ session('action_type') == 'cập nhật' ? 'update-title' : '' }}">Thành công!</h2>
            <p class="success-message">
                Phim "<span class="highlighted-title">{{ session('movie_title') }}</span>" 
                {{ session('success_message') }}
                <span class="action-highlight {{ session('action_type') == 'thêm' ? 'add-action' : 'update-action' }}">{{ session('action_type') }}</span>
                {{ session('success_end') }}
            </p>
            <div class="countdown-container">
                <span>Tự động đóng sau </span>
                <span class="countdown-number" id="countdown">3</span>
                <span> giây</span>
            </div>
            <button class="success-button {{ session('action_type') == 'thêm' ? 'add-button' : 'update-button' }}" id="closeSuccessBtn">OK</button>
        </div>
    </div>
</div>

<style>
    .success-notification-overlay {
        position: fixed;
        top: 0; left: 0;
        width: 100%; height: 100%;
        background-color: rgba(0, 0, 0, 0.65);
        display: flex;
        justify-content: center;
        align-items: center;
        z-index: 9999;
        backdrop-filter: blur(8px);
        animation: fadeIn 0.3s ease-out;
    }
    
    .success-notification-card {
        background: #ffffff;
        border-radius: 12px;
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.15);
        width: 320px;
        overflow: hidden;
        display: flex;
        flex-direction: column;
        animation: slideIn 0.4s ease-out;
    }
    
    .success-icon-container {
        padding: 20px 0 10px;
        display: flex;
        justify-content: center;
    }
    
    .success-notification-content {
        padding: 0 20px 20px;
        text-align: center;
    }
    
    .success-checkmark {
        width: 60px; height: 60px;
        border-radius: 50%;
        display: block;
        stroke-width: 2;
        stroke-miterlimit: 10;
        box-shadow: inset 0px 0px 0px;
        animation: fill .4s ease-in-out .4s forwards, scale .3s ease-in-out .9s both;
    }
    
    .add-icon { stroke: #4caf50; }
    .add-icon .checkmark-circle, .add-icon .checkmark-check { stroke: #4caf50; }
    
    .update-icon { stroke: #ffc107; }
    .update-icon .checkmark-circle, .update-icon .checkmark-check { stroke: #ffc107; }
    
    .success-title {
        font-size: 20px;
        font-weight: 600;
        margin: 0 0 10px;
        color: #4caf50;
    }
    
    .update-title { color: #ffc107; }
    
    .success-message {
        color: #4a4a4a;
        font-size: 14px;
        line-height: 1.4;
        margin-bottom: 12px;
    }
    
    .highlighted-title {
        font-weight: 700;
        color: #1e88e5;
        background: linear-gradient(to bottom, transparent 60%, rgba(76, 175, 80, 0.2) 40%);
        padding: 0 3px;
        border-radius: 3px;
        display: inline-block;
        text-shadow: 0 1px 1px rgba(255,255,255,0.7);
    }
    
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
    
    .add-action { background-color: #4caf50; }
    .update-action { background-color: #ffc107; color: #212121; }
    
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
        width: 22px; height: 22px;
        line-height: 22px;
        text-align: center;
        border-radius: 50%;
        font-weight: bold;
        margin: 0 4px;
        animation: pulse 1s infinite;
    }
    
    .success-button {
        border: none;
        border-radius: 6px;
        padding: 8px 24px;
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s ease;
        color: white;
    }
    
    .add-button {
        background-color: #4caf50;
        box-shadow: 0 2px 6px rgba(76, 175, 80, 0.25);
    }
    
    .add-button:hover {
        background-color: #43a047;
        box-shadow: 0 3px 8px rgba(76, 175, 80, 0.35);
        transform: translateY(-1px);
    }
    
    .update-button {
        background-color: #ffc107;
        box-shadow: 0 2px 6px rgba(255, 193, 7, 0.25);
        color: #212121;
    }
    
    .update-button:hover {
        background-color: #ffb300;
        box-shadow: 0 3px 8px rgba(255, 193, 7, 0.35);
        transform: translateY(-1px);
    }
    
    .checkmark-circle {
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
    
    /* Animations */
    @keyframes fill { 100% { box-shadow: inset 0px 0px 0px 30px rgba(76, 175, 80, 0.1); } }
    @keyframes stroke { 100% { stroke-dashoffset: 0; } }
    @keyframes scale { 0%, 100% { transform: none; } 50% { transform: scale3d(1.1, 1.1, 1); } }
    @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }
    @keyframes fadeOut { from { opacity: 1; } to { opacity: 0; } }
    @keyframes slideIn { from { transform: translateY(20px); opacity: 0; } to { transform: translateY(0); opacity: 1; } }
    @keyframes pulse { 0%, 100% { transform: scale(1); } 50% { transform: scale(1.1); } }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const popup = document.getElementById('successPopup');
        const closeBtn = document.getElementById('closeSuccessBtn');
        const countdownElement = document.getElementById('countdown');
        let secondsLeft = 3;
        
        // Thiết lập sự kiện
        if (closeBtn) closeBtn.addEventListener('click', closeSuccessNotification);
        if (popup) popup.addEventListener('click', e => { if (e.target === popup) closeSuccessNotification(); });
        document.addEventListener('keydown', e => { if (e.key === 'Enter' || e.key === 'Escape') closeSuccessNotification(); });
        
        // Bộ đếm ngược
        const countdownInterval = setInterval(function() {
            secondsLeft--;
            if (countdownElement) countdownElement.textContent = secondsLeft;
            if (secondsLeft <= 0) {
                clearInterval(countdownInterval);
                closeSuccessNotification();
            }
        }, 1000);
        
        window.countdownIntervalId = countdownInterval;
        
        function closeSuccessNotification() {
            if (window.countdownIntervalId) clearInterval(window.countdownIntervalId);
            
            if (popup) {
                popup.style.animation = 'fadeOut 0.3s forwards';
                setTimeout(() => popup.style.display = 'none', 300);
            }
        }
    });
</script>
@endif


@endsection

