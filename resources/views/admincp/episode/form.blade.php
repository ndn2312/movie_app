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
                        <i>➕ THÊM TẬP PHIM 🎬</i>
                    </a>
                    
                    @endif
                    
                    <!-- Nút DANH SÁCH PHIM đặt sau -->
                    <a href="{{route('movie.index')}}" class="button-custom">
                        <i>🎬 DS.TẬP PHIM</i>
                    </a>
                </div>

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
                                {!! Form::label('movie','Chọn phim',['class' => 'd-block mb-2']) !!}
                                {!! Form::select('movie_id',['0'=>'Chọn phim','Phim'=>$list_movie], isset($episode) ? $episode->movie_id : '', ['class'=>'form-control select-movie']) !!}

                            </div>
                            <br>
                            <div class="form-group">
                                {!! Form::label('link','Link phim',['class' => 'd-block mb-2']) !!}
                                {!! Form::text('link',isset($episode)? $episode->linkphim:'', ['class'=>'form-control ','placeholder'=>'Nhập vào dữ liệu...']) !!}
                            </div>
                            <br>
                            <div class="form-group">
                                {!! Form::label('link','Tập phim',[]) !!}
                                    <select name="episode" class="form-control" id="episode">
                                        

                                    </select>
                            </div>
                    
                            
                            <br>
                            @if(!isset($movie))                    

                                {!! Form::submit('Thêm tập phim',['class'=>'btn btn-success']) !!}

                            @else
                                {!! Form::submit('Cập nhật tập phim',['class'=>'btn btn-success']) !!}
                            @endif
                        {!! Form::close() !!}

                </div>
            </div>
            
        </div>
    </div>
</div>


@endsection

