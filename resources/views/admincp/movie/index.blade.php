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

    /* Css xóa admin */
    .deleted-item {
        display: inline-block;
        padding: 3px 8px;
        border-radius: 12px;
        background: linear-gradient(135deg, #ff6b6b 0%, #dc3545 100%);
        color: white;
        font-weight: 500;
        box-shadow: 0 2px 5px rgba(220, 53, 69, 0.3);
        font-size: 0.9rem;
        transition: all 0.3s ease;
        animation: soft-pulse 2s infinite;
    }

    .deleted-item i {
        margin-right: 5px;
        font-size: 0.85rem;
    }

    .deleted-type {
    font-weight: 700;
    letter-spacing: 0.5px;
    margin-right: 3px;
    color: #fff; 
    text-shadow: 0px 1px 1px rgba(0,0,0,0.3); /* Thêm đổ bóng nhẹ để nổi bật hơn */
}


    .deleted-item:hover {
        box-shadow: 0 4px 8px rgba(220, 53, 69, 0.5);
        transform: translateY(-2px);
    }
    .deleted-name {
    color: #e5f508;
    font-weight: 600;
    font-style: italic;
}


    @keyframes soft-pulse {
        0% {
            opacity: 1;
        }

        50% {
            opacity: 0.85;
        }

        100% {
            opacity: 1;
        }
    }
</style>
<!-- Thêm vào phần head của layout -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        });
    });
</script>



<div class="container-fluid">

    <div class="row justify-content-center">
        <div class="col-md-12">

            <a href="{{route('movie.create')}}" class="button-custom button-add">
                <i>➕ THÊM PHIM 🎬</i>
            </a>
            

            <table class="table" id="tablephim">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Tên phim</th>
                        <th scope="col">Tags</th>
                        <th scope="col">Thời lượng phim</th>
                        <th scope="col">Nổi bật</th>
                        <th scope="col">Định dạng</th>
                        <th scope="col">Phiên bản</th>
                        <th scope="col">Ảnh</th>

                        {{-- <th scope="col">Description</th> --}}
                        <th scope="col">Đường dẫn</th>
                        <th scope="col">Hoạt động/Không hoạt động</th>
                        <th scope="col">Danh mục</th>
                        <th scope="col">Thể loại</th>
                        <th scope="col">Quốc gia</th>
                        <th scope="col">Thời gian tạo</th>
                        <th scope="col">Thời gian cập nhật</th>
                        <th scope="col">Năm phim</th>
                        <th scope="col">Top view</th>

                        <th scope="col">Quản lý</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($list as $key => $cate)
                    <tr>
                        <th scope="row">{{$key}}</th>
                        <td>{{$cate->title}}</td>
                        <td>{{$cate->tags}}</td>
                        <td>{{$cate->thoiluong}}</td>
                        <td>
                            @if($cate->phim_hot==0)
                            Không
                            @else
                            Có
                            @endif
                        </td>
                        <td>
                            @if($cate->resolution==0)
                            HD
                            @elseif($cate->resolution==1)
                            SD
                            @elseif($cate->resolution==2)
                            HDCam
                            @elseif($cate->resolution==3)
                            Cam
                            @elseif($cate->resolution==4)
                            FullHD
                            @endif
                        </td>
                        <td>
                            @if($cate->phude==0)
                            Phụ đề
                            @else
                            Thuyết minh
                            @endif
                        </td>
                        {{-- <td><img width="70%" src="{{asset('uploads/movie/'.$cate->image)}}"></td> --}}
                        <td>
                            <img src="{{ asset('uploads/movie/'.$cate->image) }}"
                                style="width: 100px; height: auto; object-fit: cover;">
                        </td>

                        {{-- <td>{{$cate->description}}</td> --}}
                        <td>{{$cate->slug}}</td>

                        <td>
                            @if($cate->status)
                            Hiển thị
                            @else
                            Không hiển thị
                            @endif
                        </td>
                       
                    <td>
                        @if($cate->category)
                            {{ $cate->category->title }}
                        @else
                            @php
                                $deleted_category = \App\Models\Category::withTrashed()
                                    ->find($cate->category_id);
                            @endphp
                            
                            <span class="position-relative deleted-item" data-bs-toggle="tooltip"
                                data-bs-placement="top" title="Danh mục này đã bị xóa khỏi hệ thống">
                                <i class="fas fa-exclamation-triangle"></i>
                                <span class="deleted-type">DANH MỤC</span> 
                                <span class="deleted-name">{{ $deleted_category ? $deleted_category->title : '' }}</span> đã bị xóa
                            </span>
                        @endif
                    </td>

                <!-- Cho thể loại -->
                    <td>
                        @if($cate->genre)
                            {{ $cate->genre->title }}
                        @else
                            @php
                                $deleted_genre = \App\Models\Genre::withTrashed()
                                    ->find($cate->genre_id);
                            @endphp
                            
                            <span class="position-relative deleted-item" data-bs-toggle="tooltip"
                                data-bs-placement="top" title="Thể loại này đã bị xóa khỏi hệ thống">
                                <i class="fas fa-exclamation-triangle"></i>
                                <span class="deleted-type">THỂ LOẠI</span> 
                                <span class="deleted-name">{{ $deleted_genre ? $deleted_genre->title : '' }}</span> đã bị xóa
                            </span>
                        @endif
                    </td>

                <!-- Cho quốc gia -->
                    <td>
                        @if($cate->country)
                            {{ $cate->country->title }}
                        @else
                            @php
                                $deleted_country = \App\Models\Country::withTrashed()
                                    ->find($cate->country_id);
                            @endphp
                            
                            <span class="position-relative deleted-item" data-bs-toggle="tooltip"
                                data-bs-placement="top" title="Quốc gia này đã bị xóa khỏi hệ thống">
                                <i class="fas fa-exclamation-triangle"></i>
                                <span class="deleted-type">QUỐC GIA</span> 
                                <span class="deleted-name">{{ $deleted_country ? $deleted_country->title : '' }}</span> đã bị xóa
                            </span>
                        @endif
                    </td>

                <!-- Ngày tạo, ngày cập nhật -->
                <td>
                    @php
                    date_default_timezone_set('Asia/Ho_Chi_Minh');

                    \Carbon\Carbon::setLocale('vi');

                    $ngaytao = \Carbon\Carbon::parse($cate->ngaytao);
                
                    // Xác định thứ trong tuần
                    $dayOfWeek = $ngaytao->dayOfWeek;  // 0 = Chủ nhật, 6 = Thứ 7
                
                    // Xác định màu cho thứ trong tuần
                    $dayColor = ($dayOfWeek == 0 || $dayOfWeek == 6) ? '#FF0000' : '#000000';
                
                    // Lấy riêng phần ngày tháng năm và giờ phút giây
                    $dateOnly = $ngaytao->format('d/m/Y');
                    $timeOnly = $ngaytao->format('H:i:s');
                
                    // Lấy tên thứ trong tuần tiếng Việt
                    $thuViettat = ucfirst($ngaytao->locale('vi')->isoFormat('dddd'));
                    
                    // Tạo chuỗi diffForHumans bằng tiếng Việt
                    $diffHumans = $ngaytao->locale('vi')->diffForHumans();
                
                    // Hiển thị với màu sắc theo yêu cầu
                    echo "<span style='color: {$dayColor};'>{$thuViettat}, </span>";
                    echo "<span style='color: #000000;'>{$dateOnly}</span> ";
                    echo "<span style='color: #4CAF50;'>{$timeOnly}</span><br>";
                    echo "<span style='color: #0066CC; font-style: italic;'>{$diffHumans}</span>";
                    @endphp
                </td>
                <td>
                    @php
                    date_default_timezone_set('Asia/Ho_Chi_Minh');

                    \Carbon\Carbon::setLocale('vi');

                    $ngaycapnhat = \Carbon\Carbon::parse($cate->ngaycapnhat);
                
                    // Xác định thứ trong tuần
                    $dayOfWeek = $ngaycapnhat->dayOfWeek;  // 0 = Chủ nhật, 6 = Thứ 7
                
                    // Xác định màu cho thứ trong tuần
                    $dayColor = ($dayOfWeek == 0 || $dayOfWeek == 6) ? '#FF0000' : '#000000';
                
                    // Lấy riêng phần ngày tháng năm và giờ phút giây
                    $dateOnly = $ngaycapnhat->format('d/m/Y');
                    $timeOnly = $ngaycapnhat->format('H:i:s');
                
                    // Lấy tên thứ trong tuần tiếng Việt
                    $thuViettat = ucfirst($ngaycapnhat->locale('vi')->isoFormat('dddd'));
                    
                    // Tạo chuỗi diffForHumans bằng tiếng Việt
                    $diffHumans = $ngaycapnhat->locale('vi')->diffForHumans();
                
                    // Hiển thị với màu sắc theo yêu cầu
                    echo "<span style='color: {$dayColor};'>{$thuViettat}, </span>";
                    echo "<span style='color: #000000;'>{$dateOnly}</span> ";
                    echo "<span style='color: #4CAF50;'>{$timeOnly}</span><br>";
                    echo "<span style='color: #0066CC; font-style: italic;'>{$diffHumans}</span>";
                    @endphp
                </td>
                
                <!--Năm phim -->
                    <td>
                        {!! Form::selectYear('year',2000,2025,isset($cate->year) ? $cate->year:'',['class'=>'select-year','id'=>$cate->id, 'title'=>$cate->title] ) !!}

                    </td>
                    <td>
                        {!! Form::select('topview',['0'=>'Ngày','1'=>'Tuần', '2'=>'Tháng'], isset($cate->topview) ? $cate->topview : '', ['class'=>'select-topview','id'=>$cate->id,'title'=>$cate->title]) !!}

                    </td>
                    <td>
                        {!! Form::open([
                            'method'=>'DELETE','route'=>['movie.destroy',$cate->id]])!!}

                        {!! Form::submit('Xóa', ['class' => 'btn btn-danger','onsubmit' => 'return confirm()']) !!}

                        {!! Form::close() !!}
                            <br>
                            <a href="{{route('movie.edit', $cate->id)}}" class="btn btn-warning">Sửa</a>    
                    </td>
                    </tr>
                    @endforeach
                </tbody>


            </table>
        </div>
    </div>
    @if(session('action_type') == 'xóa')
    <div class="success-notification-overlay" id="deleteSuccessPopup">
        <div class="delete-notification-card">
            <div class="delete-icon-container">
                <svg class="delete-checkmark" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 52 52">
                    <circle class="checkmark-circle" cx="26" cy="26" r="25" fill="none" />
                    <path class="checkmark-check" fill="none" d="M14.1 27.2l7.1 7.2 16.7-16.8" />
                </svg>
            </div>
            <div class="delete-notification-content">
                <h2 class="delete-title">Thành công!</h2>
                <p class="delete-message">
                    Phim "<span class="highlighted-title-delete">{{ session('movie_title') }}</span>" 
                    {{ session('delete_message') }}
                    <span class="action-highlight delete-action">{{ session('action_type') }}</span>
                    {{ session('delete_end') }}
                </p>
                <div class="delete-countdown-container">
                    <span>Tự động đóng sau </span>
                    <span class="countdown-number" id="deleteCountdown">3</span>
                    <span> giây</span>
                </div>
                <button class="delete-button" id="closeDeleteBtn">OK</button>
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
        
        .delete-notification-card {
            background: #ffffff;
            border-radius: 12px;
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.15);
            width: 320px;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            animation: slideIn 0.4s ease-out;
        }
        
        .delete-icon-container {
            padding: 20px 0 10px;
            display: flex;
            justify-content: center;
        }
        
        .delete-notification-content {
            padding: 0 20px 20px;
            text-align: center;
        }
        
        .delete-checkmark {
            width: 60px; height: 60px;
            border-radius: 50%;
            display: block;
            stroke-width: 2;
            stroke: #d32f2f;
            stroke-miterlimit: 10;
            box-shadow: inset 0px 0px 0px #d32f2f;
            animation: fill-red .4s ease-in-out .4s forwards, scale .3s ease-in-out .9s both;
        }
        
        .checkmark-circle {
            stroke-dasharray: 166;
            stroke-dashoffset: 166;
            stroke-width: 2;
            stroke-miterlimit: 10;
            stroke: #d32f2f;
            fill: none;
            animation: stroke 0.6s cubic-bezier(0.65, 0, 0.45, 1) forwards;
        }
        
        .checkmark-check {
            transform-origin: 50% 50%;
            stroke-dasharray: 48;
            stroke-dashoffset: 48;
            stroke-width: 3;
            stroke: #d32f2f;
            animation: stroke 0.3s cubic-bezier(0.65, 0, 0.45, 1) 0.8s forwards;
        }
        
        .delete-title {
            color: #d32f2f;
            font-size: 20px;
            font-weight: 600;
            margin: 0 0 10px;
        }
        
        .delete-message {
            color: #4a4a4a;
            font-size: 14px;
            line-height: 1.4;
            margin-bottom: 12px;
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
        
        .delete-action {
            color: white;
            background-color: #d32f2f;
            box-shadow: 0 2px 5px rgba(211, 47, 47, 0.5);
            font-weight: 800;
            font-size: 15px;
            padding: 2px 8px;
            border-radius: 4px;
            display: inline-block;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            animation: delete-action-pulse 2s infinite;
        }
        
        .delete-countdown-container {
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
        
        .delete-button {
            background-color: #d32f2f;
            color: white;
            border: none;
            border-radius: 6px;
            padding: 8px 24px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s ease;
            box-shadow: 0 2px 6px rgba(211, 47, 47, 0.25);
        }
        
        .delete-button:hover {
            background-color: #b71c1c;
            box-shadow: 0 3px 8px rgba(211, 47, 47, 0.35);
            transform: translateY(-1px);
        }
        
        /* Animations */
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
            const popup = document.getElementById('deleteSuccessPopup');
            const closeBtn = document.getElementById('closeDeleteBtn');
            const countdownElement = document.getElementById('deleteCountdown');
            let secondsLeft = 3;
            
            // Thiết lập sự kiện lắng nghe
            if (closeBtn) closeBtn.addEventListener('click', closeDeleteNotification);
            if (popup) popup.addEventListener('click', e => { if (e.target === popup) closeDeleteNotification(); });
            document.addEventListener('keydown', e => { if (e.key === 'Enter' || e.key === 'Escape') closeDeleteNotification(); });
            
            // Bộ đếm ngược
            const countdownInterval = setInterval(function() {
                secondsLeft--;
                if (countdownElement) countdownElement.textContent = secondsLeft;
                if (secondsLeft <= 0) {
                    clearInterval(countdownInterval);
                    closeDeleteNotification();
                }
            }, 1000);
            
            // Lưu ID interval toàn cục
            window.deleteCountdownIntervalId = countdownInterval;
            
            function closeDeleteNotification() {
                if (window.deleteCountdownIntervalId) clearInterval(window.deleteCountdownIntervalId);
                
                if (popup) {
                    popup.style.animation = 'fadeOut 0.3s forwards';
                    setTimeout(() => popup.style.display = 'none', 300);
                }
            }
        });
    </script>
    @endif
    

@endsection