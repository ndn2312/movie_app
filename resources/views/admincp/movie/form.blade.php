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
                            {!! Form::label('title','Số tập phim',['class' => 'd-block mb-2']) !!}
                            {!! Form::text('sotap',isset($movie)? $movie->sotap:'', ['class'=>'form-control','placeholder'=>'Nhập vào dữ liệu...']) !!}
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
                            {!! Form::label('thuocphim','Thuộc phim',['class' => 'd-block mb-2']) !!}
                            {!! Form::select('thuocphim', ['phimle'=>'Phim lẻ','phimbo'=>'Phim bộ'],isset($movie) ? $movie->thuocphim : '', ['class'=>'form-control']) !!}

                        </div>
                        <br>
                        <div class="form-group">
                            {!! Form::label('Country','Quốc gia',['class' => 'd-block mb-2']) !!}
                            {!! Form::select('country_id',$country, isset($movie) ? $movie->country_id : '', ['class'=>'form-control']) !!}

                        </div>
                        <br>
                        <div class="form-group">
                            {!! Form::label('Genre','Thể loại',['class' => 'd-block mb-2']) !!}
                            {{-- {!! Form::select('genre_id',$genre, isset($movie) ? $movie->genre_id : '', ['class'=>'form-control']) !!} --}}
                            @foreach($list_genre as $key => $gen)
                                    @if(isset($movie))                     
                                {!! Form::checkbox('genre[]', $gen->id, isset($movie_genre) && $movie_genre->contains($gen->id) ? true : false )!!}
                                    @else
                                {!! Form::checkbox('genre[]', $gen->id,'')!!}
                                    @endif
                                {!! Form::Label('genre', $gen->title )!!}

                                
                            @endforeach
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


@endsection

