@extends('layouts.app')

@section('content')
<style>
     .button-custom {
            width: 200px;
            /* Điều chỉnh kích thước theo ý muốn */
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
            margin: auto;



        }

        .button-custom:hover {
            background: linear-gradient(90deg, #ff4b2b, #ff416c);
            transform: scale(1.1);
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.4);
        }
        /*css them phim*/
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
        }
        
        .button-add {
            background: linear-gradient(90deg, #4CAF50, #2196F3);
            color: #FFD700; /* Màu vàng gold */
            text-shadow: 0 1px 2px rgba(0,0,0,0.3); /* Thêm đổ bóng cho chữ */
        }
        
        .button-add:hover {
            background: linear-gradient(90deg, #2196F3, #4CAF50);
            transform: scale(1.1);
            box-shadow: 0 6px 15px rgba(33, 150, 243, 0.4);
        }
</style>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                {{-- <div class="d-flex mb-3" style="gap: 15px;">
                    @if(isset($episode))
                    <a href="{{route('episode.create')}}" class="button-customadd">
                        <i>➕ THÊM TẬP PHIM 🎬</i>
                    </a>
                    
                    @endif
                    
                    <a href="{{route('episode.index')}}" class="button-custom">
                        <i>🎬 DS.TẬP PHIM</i>
                    </a>
                    <a href="{{route('movie.index')}}" class="button-custom">
                        <i>🎬 DANH SÁCH PHIM</i>
                    </a>
                </div> --}}

                <div class="card-header">Quản lý tập phim</div>
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
                        @if(!isset($episode))                    
                            {!! Form::open(['route' => 'episode.store', 'method'=>'POST','enctype'=>'multipart/form-data']) !!}
                        @else    
                            {!! Form::open(['route' => ['episode.update', $episode->id], 'method'=>'PUT','enctype'=>'multipart/form-data']) !!}

                        @endif
                            
                     
                            <div class="form-group">
                                {!! Form::label('movie_title','Tên phim',['class' => 'd-block mb-2']) !!}
                                {!! Form::text('movie_title',isset($movie)? $movie->title:'', ['class'=>'form-control ','readonly']) !!}
                                {!! Form::hidden('movie_id',isset($movie)? $movie->id:'') !!}
                            </div>
                            <br>
                            <div class="form-group">
                                {!! Form::label('link','Link phim',['class' => 'd-block mb-2']) !!}
                                {!! Form::text('link',isset($episode)? $episode->linkphim:'', ['class'=>'form-control ','placeholder'=>'Nhập vào dữ liệu...']) !!}
                            </div>
                            <br>
                            @if(isset($episode))
                                <div class="form-group">
                                    {!! Form::label('episode','Tập phim',[]) !!}
                                    {!! Form::text('episode',isset($episode)? $episode->episode:'', ['class'=>'form-control ','placeholder'=>'Nhập vào dữ liệu...',isset($episode)? 'readonly':'']) !!}
                                </div>
                            @else
                                <div class="form-group">
                                    {!! Form::label('episode','Tập phim',[]) !!}
                                    {!! Form::selectRange('episode',1, $movie->sotap, $movie->sotap, ['class'=>'form-control ']) !!}
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
        <!-- Phần này là danh sách tập phim -->
        <div class="col-md-12">
            {{-- <div class="card">
                <div class="card-header">Quản lý danh mục</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
                    @if(!isset($category))                    
                        {!! Form::open(['route' => 'category.store', 'method'=>'POST']) !!}
                    @else    
                        {!! Form::open(['route' => ['category.update', $category->id], 'method'=>'PUT']) !!}

                    @endif
                        <div class="form-group">
                            {!! Form::label('title','Tiêu đề',['class' => 'd-block mb-2']) !!}
                            {!! Form::text('title',isset($category)? $category->title:'', ['class'=>'form-control','placeholder'=>'Nhập vào dữ liệu...','id'=>'slug','onkeyup'=>'ChangeToSlug()']) !!}
                        </div>
                        <br>
                        <div class="form-group">
                            {!! Form::label('slug','Đường dẫn',['class' => 'd-block mb-2']) !!}
                            {!! Form::text('slug',isset($category)? $category->slug :'', ['class'=>'form-control','placeholder'=>'Nhập vào dữ liệu...','id'=>'convert_slug']) !!}
                        </div>
                        <br>
                        <div class="form-group">
                            {!! Form::label('description','Mô tả',['class' => 'd-block mb-2']) !!}
                            
                            {!! Form::textarea('description',isset($category)? $category->description:'', ['style'=>'resize:none','class'=>'form-control','placeholder'=>'Nhập vào dữ liệu...','id'=>'description']) !!}
                        </div>
                        <br>
                        <div class="form-group">
                            {!! Form::label('Active','Trạng thái',['class' => 'd-block mb-2']) !!}
                            {!! Form::select('status',['1'=>'Hiển thị','0'=>'Không'], isset($category) ? $category->status : '', ['class'=>'form-control']) !!}

                        </div>
                        <br>
                        @if(!isset($category))                    

                            {!! Form::submit('Thêm dữ liệu',['class'=>'btn btn-success']) !!}

                        @else
                            {!! Form::submit('Cập nhật',['class'=>'btn btn-success']) !!}
                        @endif
                    {!! Form::close() !!}

                </div>
            </div> --}}
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
                    {{-- <th scope="col">Hoạt động/Không hoạt động</th>
                    <th scope="col">Thời gian tạo</th>
                    <th scope="col">Thời gian cập nhật</th> --}}
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

                    {{-- <td>
                        @if($cate->status)
                            Hiển thị
                        @else
                            Không hiển thị
                        @endif
                    </td> --}}
                    {{-- <td>
                        {{$cate->ngaytao}}
                    </td>
                    <td>
                        {{$cate->ngaycapnhat}}
                    </td> --}}
                    <td>
                        {!! Form::open([
                            'method'=>'DELETE','route'=>['episode.destroy',$episode->id],])!!}
                        
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
@if(session('action_type') == 'xóa' || session('action_type') == 'thêm' || session('action_type') == 'cập nhật')
    <div class="success-notification-overlay" id="{{ session('action_type') == 'xóa' ? 'deleteSuccessPopup' : 'successPopup' }}">
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
            
            <div class="{{ session('action_type') == 'xóa' ? 'delete-notification-content' : 'success-notification-content' }}">
                <h2 class="{{ 
                    session('action_type') == 'xóa' ? 'delete-title' : 
                    (session('action_type') == 'cập nhật' ? 'success-title update-title' : 'success-title') 
                }}">Thành công!</h2>
                
                <p class="{{ session('action_type') == 'xóa' ? 'delete-message' : 'success-message' }}">
                    Tập phim "<span class="{{ session('action_type') == 'xóa' ? 'highlighted-title-delete' : 'highlighted-title' }}">{{ session('movie_title') }}</span>" 
                    {{ session('action_type') == 'xóa' ? session('delete_message') : session('success_message') }} 
                    <span class="action-highlight {{ 
                        session('action_type') == 'xóa' ? 'delete-action' : 
                        (session('action_type') == 'thêm' ? 'add-action' : 'update-action') 
                    }}">{{ session('action_type') }}</span> 
                    {{ session('action_type') == 'xóa' ? session('delete_end') : session('success_end') }}
                </p>
                
                <div class="{{ session('action_type') == 'xóa' ? 'delete-countdown-container' : 'countdown-container' }}">
                    <span>Tự động đóng sau </span>
                    <span class="countdown-number" id="{{ session('action_type') == 'xóa' ? 'deleteCountdown' : 'countdown' }}">3</span>
                    <span> giây</span>
                </div>
                
                <button class="{{ 
                    session('action_type') == 'xóa' ? 'delete-button' : 
                    (session('action_type') == 'thêm' ? 'success-button add-button' : 'success-button update-button') 
                }}" id="{{ session('action_type') == 'xóa' ? 'closeDeleteBtn' : 'closeSuccessBtn' }}">OK</button>
            </div>
        </div>
    </div>
    
    <style>
        /* Common notification overlay */
        .success-notification-overlay {
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
        
        /* Card styles */
        .success-notification-card, .delete-notification-card {
            background: #ffffff;
            border-radius: 12px;
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.15);
            width: 320px;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            animation: slideIn 0.4s ease-out;
        }
        
        /* Icon container styles */
        .success-icon-container, .delete-icon-container {
            padding: 20px 0 10px;
            display: flex;
            justify-content: center;
        }
        
        /* Content container styles */
        .success-notification-content, .delete-notification-content {
            padding: 0 20px 20px;
            text-align: center;
        }
        
        /* Checkmark styles */
        .success-checkmark, .delete-checkmark {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            display: block;
            stroke-width: 2;
            stroke-miterlimit: 10;
            animation: scale .3s ease-in-out .9s both;
        }
        
        /* Action-specific checkmark styles */
        .success-checkmark {
            animation: fill .4s ease-in-out .4s forwards, scale .3s ease-in-out .9s both;
        }
        
        .delete-checkmark {
            stroke: #d32f2f;
            box-shadow: inset 0px 0px 0px #d32f2f;
            animation: fill-red .4s ease-in-out .4s forwards, scale .3s ease-in-out .9s both;
        }
        
        .add-icon { stroke: #4caf50; }
        .add-icon .checkmark-circle, .add-icon .checkmark-check { stroke: #4caf50; }
        
        .update-icon { stroke: #ffc107; }
        .update-icon .checkmark-circle, .update-icon .checkmark-check { stroke: #ffc107; }
        
        /* Circle and check animations */
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
        
        /* Title styles */
        .success-title, .delete-title {
            font-size: 20px;
            font-weight: 600;
            margin: 0 0 10px;
        }
        
        .success-title { color: #4caf50; }
        .update-title { color: #ffc107; }
        .delete-title { color: #d32f2f; }
        
        /* Message styles */
        .success-message, .delete-message {
            color: #4a4a4a;
            font-size: 14px;
            line-height: 1.4;
            margin-bottom: 12px;
        }
        
        /* Highlighted title styles */
        .highlighted-title {
            font-weight: 700;
            color: #1e88e5;
            background: linear-gradient(to bottom, transparent 60%, rgba(76, 175, 80, 0.2) 40%);
            padding: 0 3px;
            border-radius: 3px;
            display: inline-block;
            text-shadow: 0 1px 1px rgba(255,255,255,0.7);
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
        
        /* Action highlight styles */
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
        .update-action { 
            background-color: #ffc107; 
            color: #212121; 
        }
        .delete-action {
            color: white;
            background-color: #d32f2f;
            box-shadow: 0 2px 5px rgba(211, 47, 47, 0.5);
            animation: delete-action-pulse 2s infinite;
        }
        
        /* Countdown container */
        .countdown-container, .delete-countdown-container {
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
        
        /* Button styles */
        .success-button, .delete-button {
            border: none;
            border-radius: 6px;
            padding: 8px 24px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s ease;
        }
        
        .success-button { color: white; }
        
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
        
        .delete-button {
            background-color: #d32f2f;
            color: white;
            box-shadow: 0 2px 6px rgba(211, 47, 47, 0.25);
        }
        
        .delete-button:hover {
            background-color: #b71c1c;
            box-shadow: 0 3px 8px rgba(211, 47, 47, 0.35);
            transform: translateY(-1px);
        }
        
        /* Animations */
        @keyframes fill { 100% { box-shadow: inset 0px 0px 0px 30px rgba(76, 175, 80, 0.1); } }
        @keyframes fill-red { 100% { box-shadow: inset 0px 0px 0px 30px rgba(211, 47, 47, 0.1); } }
        @keyframes stroke { 100% { stroke-dashoffset: 0; } }
        
        @keyframes scale {
            0%, 100% { transform: none; }
            50% { transform: scale3d(1.1, 1.1, 1); }
        }
        
        @keyframes delete-action-pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.05); box-shadow: 0 3px 8px rgba(211, 47, 47, 0.7); }
        }
        
        @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }
        @keyframes fadeOut { from { opacity: 1; } to { opacity: 0; } }
        
        @keyframes slideIn {
            from { transform: translateY(20px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }
        
        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.1); }
        }
    </style>
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

