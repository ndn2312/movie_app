@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">Quản lý quốc gia phim</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
                    @if(!isset($country))                    
                        {!! Form::open(['route' => 'country.store', 'method'=>'POST']) !!}
                    @else    
                        {!! Form::open(['route' => ['country.update', $country->id], 'method'=>'PUT']) !!}

                    @endif
                    <div class="form-group">
                            {!! Form::label('title','Tiêu đề',['class' => 'd-block mb-2']) !!}
                            {!! Form::text('title',isset($country)? $country->title:'', ['class'=>'form-control','placeholder'=>'Nhập vào dữ liệu...','id'=>'slug','onkeyup'=>'ChangeToSlug()']) !!}
                        </div>
                        <br>
                        <div class="form-group">
                            {!! Form::label('slug','Đường dẫn',['class' => 'd-block mb-2']) !!}
                            {!! Form::text('slug',isset($country)? $country->slug:'', ['class'=>'form-control','placeholder'=>'Nhập vào dữ liệu...','id'=>'convert_slug']) !!}
                        </div>
                        <br>
                        <div class="form-group">
                            {!! Form::label('description','Mô tả',['class' => 'd-block mb-2']) !!}
                            
                            {!! Form::textarea('description',isset($country)? $country->description:'', ['style'=>'resize:none','class'=>'form-control','placeholder'=>'Nhập vào dữ liệu...','id'=>'description']) !!}
                        </div>
                        <br>
                        <div class="form-group">
                            {!! Form::label('Active','Trạng thái',['class' => 'd-block mb-2']) !!}
                            {!! Form::select('status',['1'=>'Hiển thị','0'=>'Không'], isset($country) ? $country->status : '', ['class'=>'form-control']) !!}

                        </div>
                        <br>
                        @if(!isset($country))                    

                            {!! Form::submit('Thêm dữ liệu',['class'=>'btn btn-success']) !!}

                        @else
                            {!! Form::submit('Cập nhật',['class'=>'btn btn-success']) !!}
                        @endif
                    {!! Form::close() !!}

                </div>
            </div>
            <table class="table" id="tablephim">
                <thead>
                  <tr>
                    <th scope="col">#</th>
                    <th scope="col">Quốc gia</th>
                    <th scope="col">Mô tả</th>
                    <th scope="col">Đường dẫn</th>
                    <th scope="col">Hoạt động/Không hoạt động</th>
                    <th scope="col">Quản lý</th>
                  </tr>
                </thead>
                <tbody>
                    @foreach($list as $key => $cate)
                  <tr>
                    <th scope="row">{{$key}}</th>
                    <td>{{$cate->title}}</td>
                    <td>{{$cate->description}}</td>
                    <td>{{$cate->slug}}</td>

                    <td>
                        @if($cate->status)
                            Hiển thị
                        @else
                            Không hiển thị
                        @endif
                    </td>
                    <td>
                        {!! Form::open([
                            'method'=>'DELETE','route'=>['country.destroy',$cate->id],])!!}
                        
                        {!! Form::submit('Xóa', ['class' => 'btn btn-danger', 'onsubmit' => 'return confirm()']) !!}

                        {!! Form::close() !!}
                        <br>
                        <a href="{{route('country.edit', $cate->id)}}" class="btn btn-warning">Sửa</a>
                    </td>
                  </tr>
                    @endforeach
                </tbody>
                
                    
              </table>
        </div>
    </div>
</div>
@if (session('success'))
<style>
    .notification-modal {
        position: fixed;
        inset: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        background-color: rgba(0, 0, 0, 0.6);
        z-index: 1050;
        backdrop-filter: blur(5px);
        animation: modalFadeIn 0.4s cubic-bezier(0.165, 0.84, 0.44, 1);
    }
    
    .notification-dialog {
        width: 100%;
        max-width: 420px;
        margin: 1.75rem auto;
        perspective: 1200px;
    }
    
    .notification-content {
        display: flex;
        flex-direction: column;
        width: 100%;
        padding: 2rem;
        text-align: center;
        background: linear-gradient(135deg, #ffffff 0%, #f5f7fa 100%);
        border-radius: 12px;
        border: 1px solid rgba(255, 255, 255, 0.3);
        box-shadow: 0 15px 35px rgba(0, 0, 0, 0.25);
        animation: modalPop 0.5s cubic-bezier(0.165, 0.84, 0.44, 1);
    }
    
    .success-icon-container {
        position: relative;
        margin: 0 auto 1.5rem;
        width: 85px;
        height: 85px;
    }
    
    .success-icon-circle {
        width: 85px;
        height: 85px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .icon-add {
        background: radial-gradient(circle, rgba(76, 175, 80, 0.15) 0%, rgba(76, 175, 80, 0.05) 70%);
        animation: pulseGlowGreen 2s infinite;
    }
    
    .icon-update {
        background: radial-gradient(circle, rgba(255, 193, 7, 0.15) 0%, rgba(255, 193, 7, 0.05) 70%);
        animation: pulseGlowYellow 2s infinite;
    }
    
    .icon-delete {
        background: radial-gradient(circle, rgba(244, 67, 54, 0.15) 0%, rgba(244, 67, 54, 0.05) 70%);
        animation: pulseGlowRed 2s infinite;
    }
    
    .success-icon { position: relative; z-index: 2; }
    
    .checkmark {
        transform-origin: 50% 50%;
        stroke-dasharray: 48;
        stroke-dashoffset: 48;
        animation: drawCheckmark 0.8s ease forwards;
    }
    
    .notification-title {
        margin-bottom: 0.75rem;
        font-size: 1.75rem;
        color: #333;
        font-weight: 700;
        text-shadow: 0 1px 2px rgba(0,0,0,0.05);
    }
    
    .notification-message {
        color: #555;
        margin-bottom: 1.25rem;
        font-size: 1.1rem;
        line-height: 1.6;
    }
    
    .action-highlight {
        display: inline-block;
        font-weight: 700;
        color: white;
        padding: 3px 10px;
        border-radius: 20px;
        margin: 0 3px;
        animation: actionPulse 2s infinite;
        text-shadow: 0 1px 1px rgba(0,0,0,0.2);
        box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    }
    
    .action-add { background: linear-gradient(135deg, #4CAF50, #2E7D32); }
    .action-update { background: linear-gradient(135deg, #FFC107, #FFA000); color: #212121; }
    .action-delete { background: linear-gradient(135deg, #F44336, #C62828); }
    
    .highlighted-text { color: #2563eb; font-weight: 600; }
    
    .notification-countdown {
        color: #555;
        font-size: 1rem;
        margin-bottom: 1.25rem;
        font-weight: 500;
    }
    
    .timer-container {
        display: inline-block;
        background: #f0f0f0;
        width: 36px; height: 36px;
        line-height: 36px;
        border-radius: 50%;
        color: #333;
        font-weight: bold;
        margin: 0 5px;
        box-shadow: inset 0 0 5px rgba(0,0,0,0.1);
        animation: timerPulse 1s infinite;
    }
    
    .notification-button {
        background: linear-gradient(135deg, #6366F1, #4F46E5);
        color: white;
        border: none;
        padding: 0.7rem 2.5rem;
        border-radius: 50px;
        cursor: pointer;
        font-weight: 600;
        font-size: 1rem;
        transition: all 0.3s;
        margin: 0 auto;
        max-width: 140px;
        box-shadow: 0 4px 10px rgba(99, 102, 241, 0.4);
    }
    
    .notification-button:hover {
        background: linear-gradient(135deg, #4F46E5, #3730A3);
        transform: translateY(-2px);
        box-shadow: 0 6px 15px rgba(79, 70, 229, 0.45);
    }
    
    .notification-button:active {
        transform: translateY(0);
        box-shadow: 0 2px 5px rgba(79, 70, 229, 0.4);
    }
    
    /* Animations */
    @keyframes drawCheckmark { 0% { stroke-dashoffset: 48; } 100% { stroke-dashoffset: 0; } }
    @keyframes modalFadeIn { from { opacity: 0; } to { opacity: 1; } }
    @keyframes fadeOut { from { opacity: 1; transform: scale(1); } to { opacity: 0; transform: scale(0.95); } }
    
    @keyframes modalPop {
        0% { transform: scale(0.9) rotateX(5deg); opacity: 0; }
        40% { transform: scale(1.02) rotateX(0); }
        60% { transform: scale(0.98) rotateX(0); }
        100% { transform: scale(1) rotateX(0); opacity: 1; }
    }
    
    @keyframes pulseGlowGreen {
        0%, 100% { transform: scale(1); box-shadow: 0 0 0 0 rgba(76, 175, 80, 0.4); }
        50% { transform: scale(1.05); box-shadow: 0 0 20px 10px rgba(76, 175, 80, 0.2); }
    }
    
    @keyframes pulseGlowYellow {
        0%, 100% { transform: scale(1); box-shadow: 0 0 0 0 rgba(255, 193, 7, 0.4); }
        50% { transform: scale(1.05); box-shadow: 0 0 20px 10px rgba(255, 193, 7, 0.2); }
    }
    
    @keyframes pulseGlowRed {
        0%, 100% { transform: scale(1); box-shadow: 0 0 0 0 rgba(244, 67, 54, 0.4); }
        50% { transform: scale(1.05); box-shadow: 0 0 20px 10px rgba(244, 67, 54, 0.2); }
    }
    
    @keyframes actionPulse { 0%, 100% { transform: scale(1); } 50% { transform: scale(1.05); } }
    @keyframes timerPulse { 0%, 100% { transform: scale(1); } 50% { transform: scale(1.08); } }
</style>

<div id="successModal" class="notification-modal">
    <div class="notification-dialog">
        <div class="notification-content">
            @php
                $action = session('action');
                $iconColor = '#4CAF50'; // Mặc định màu xanh lá
                $iconClass = 'icon-add';
                
                if ($action == 'cập nhật') {
                    $iconColor = '#FFC107';
                    $iconClass = 'icon-update';
                } elseif ($action == 'xóa') {
                    $iconColor = '#F44336';
                    $iconClass = 'icon-delete';
                }
            @endphp
            
            <div class="success-icon-container">
                <div class="success-icon-circle {{ $iconClass }}">
                    <svg xmlns="http://www.w3.org/2000/svg" width="42" height="42" viewBox="0 0 24 24" fill="none" stroke="{{ $iconColor }}" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                        <polyline class="checkmark" points="4 12 9 17 20 6"></polyline>
                    </svg>
                </div>
            </div>
            
            <h3 class="notification-title">Thành công!</h3>
            <p class="notification-message">
                Đã <span class="action-highlight action-{{ $action == 'thêm' ? 'add' : ($action == 'xóa' ? 'delete' : 'update') }}">
                    {{ $action }}
                </span>
                <span class="highlighted-text">{{ session('item_type') }} "<strong>{{ session('item_name') }}</strong>"</span> thành công.
            </p>
            
            <p class="notification-countdown">
                Tự động đóng sau <span class="timer-container"><span id="timer">3</span></span> giây
            </p>
            
            <button id="okButton" class="notification-button">OK</button>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const modal = document.getElementById('successModal');
        const okButton = document.getElementById('okButton');
        const timerElement = document.getElementById('timer');
        const modalContent = document.querySelector('.notification-content');
        let timeLeft = 3;
        
        // Bộ đếm ngược và xử lý đóng
        const countdownTimer = setInterval(() => {
            if (timeLeft <= 0) {
                clearInterval(countdownTimer);
                closeModal();
            } else {
                timerElement.textContent = timeLeft;
            }
            timeLeft--;
        }, 1000);
        
        // Xử lý sự kiện
        okButton.addEventListener('click', () => {
            closeModal();
            clearInterval(countdownTimer);
        });
        
        modal.addEventListener('click', (e) => {
            if (e.target === modal) {
                closeModal();
                clearInterval(countdownTimer);
            }
        });
        
        // Đóng modal với animation
        function closeModal() {
            modalContent.style.animation = 'fadeOut 0.3s forwards';
            modal.style.animation = 'modalFadeIn 0.4s reverse forwards';
            setTimeout(() => modal.style.display = 'none', 380);
        }
    });
</script>
@endif

@endsection

