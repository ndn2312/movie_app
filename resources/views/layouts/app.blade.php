<!Doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }}</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    
    <!-- jQuery UI CSS -->
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.14.1/themes/base/jquery-ui.css">
    <link rel="stylesheet" href="/resources/demos/style.css">
    
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="//cdn.datatables.net/2.2.2/css/dataTables.dataTables.min.css">
    
    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    
    <!-- SweetAlert2 (Thêm mới) -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <style>
        /* Modal container và nội dung */
        .custom-modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.5);
            z-index: 9999;
            justify-content: center;
            align-items: center;
        }

        .modal-content {
            background-color: white;
            padding: 30px;
            border-radius: 10px;
            text-align: center;
            max-width: 450px;
            width: 100%;
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
            animation: fadeInDown 0.3s ease-out;
        }

        @keyframes fadeInDown {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Icon và tiêu đề */
        .success-icon {
            width: 70px;
            height: 70px;
            border-radius: 50%;
            background-color: #f5f5f5;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 15px;
        }
        
        .success-icon i {
            color: #ffc107;
            font-size: 40px;
        }
        
        .success-title {
            color: #ffc107;
            font-size: 24px;
            margin-bottom: 15px;
        }

        /* Phần đếm ngược */
        .countdown {
            font-size: 14px;
            color: #666;
            margin: 15px 0;
        }
        
        .countdown i {
            margin-right: 5px;
        }

        /* Nút OK */
        .ok-button {
            background-color: #ffc107;
            color: #000;
            border: none;
            padding: 8px 40px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            font-weight: bold;
            transition: background-color 0.2s ease;
        }
        
        .ok-button:hover {
            background-color: #e0aa00;
        }

        /* Các phần highlight */
        .highlight, .highlight-year, .highlight-name {
            padding: 2px 6px;
            font-weight: bold;
            border-radius: 4px;
        }
        
        .highlight {
            background-color: #ffc107;
            color: #000;
        }
        
        .highlight-year {
            background-color: #4CAF50;
            color: white;
        }
        
        .highlight-name {
            background-color: #2196F3;
            color: white;
        }
        
        /* Hiệu ứng nhấp nháy cho sắp xếp */
        @keyframes glowBlink {
            0% {
                background-color: white;
                box-shadow: 0 0 5px rgba(220, 53, 69, 0.2);
            }
            50% {
                background-color: rgba(220, 53, 69, 0.5);
                box-shadow: 0 0 15px rgba(220, 53, 69, 0.5);
            }
            100% {
                background-color: rgba(220, 53, 69, 0.2);
                box-shadow: 0 0 5px rgba(220, 53, 69, 0.2);
            }
        }

        .ui-state-highlight {
            border: 2px dashed #dc3545;
            border-radius: 8px;
            height: 50px;
            animation: glowBlink 0.8s infinite alternate;
            transition: background-color 0.3s ease-in-out;
        }
    </style>
</head>

<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/') }}">Admin</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent"> 
                    <!-- Left Side Of Navbar --> 
                    <ul class="navbar-nav me-auto"></ul>

                    <!-- Right Side Of Navbar --> 
                    <ul class="navbar-nav ms-auto"> 
                        <!-- Authentication Links --> 
                        @guest 
                            @if (Route::has('login')) 
                                <li class="nav-item"> 
                                    <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a> 
                                </li> 
                            @endif

                            @if (Route::has('register')) 
                                <li class="nav-item"> 
                                    <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a> 
                                </li> 
                            @endif 
                        @else 
                            <li class="nav-item dropdown"> 
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" 
                                data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre> 
                                    {{ Auth::user()->name }} 
                                </a>

                                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown"> 
                                    <a class="dropdown-item" href="{{ route('logout') }}" 
                                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();"> 
                                        {{ __('Logout') }} 
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none"> 
                                        @csrf 
                                    </form> 
                                </div> 
                            </li> 
                        @endguest 
                    </ul> 
                </div>
            </div>
        </nav> 
        <main class="py-4"> 
            @if(Auth::id()) 
                <div class="container"> 
                    @include('layouts.navbar') 
                </div> 
            @endif 
            @yield('content') 
        </main>
    </div>

    <!-- Modal thông báo tùy chỉnh --> 
    <div id="success-modal" class="custom-modal"> 
        <div class="modal-content"> 
            <div class="success-icon"> 
                <i class="fas fa-check"></i> 
            </div> 
            <h3 class="success-title">Thành công!</h3> 
            <p class="success-message"></p> 
            <p class="countdown"><i class="fas fa-clock"></i> tự động đóng sau: <span id="countdown-timer">3</span> giây</p> 
            <button class="ok-button">OK</button> 
        </div> 
    </div>

    <!-- jQuery (một phiên bản duy nhất) -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
    </script>
    
    <!-- jQuery UI -->
    <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
    <script src="https://code.jquery.com/ui/1.14.1/jquery-ui.js"></script>
    
    <!-- DataTables -->
    <script type="text/javascript" src="//cdn.datatables.net/2.2.2/js/dataTables.min.js"></script>

    <!-- Script cho modal cập nhật năm và topview --> 
    <script>
    $(document).ready(function() {
            // ----- Biến toàn cục cho bộ đếm thời gian ----- 
            let modalCountdownTimer;
            
            // ----- Khởi tạo DataTables -----
            $('#tablephim').DataTable();
            
            // ----- Xử lý cập nhật năm phim ----- 
            $(document).on('change', '.select-year', function() {
            var year = $(this).find(':selected').val(),
            id_phim = $(this).attr('id'),
            ten_phim = $(this).attr('title');
        
            $.ajax({
                    url: "{{url('/update-year-phim')}}",
                    method: "GET",
                    data: {year: year, id_phim: id_phim, ten_phim: ten_phim},
                    success: function() {
                        const message = 'Phim "<span class="highlight-name">' + ten_phim + 
                            '</span>" đã được <span class="highlight">CẬP NHẬT</span> thành năm ' + 
                            '<span class="highlight-year">' + year + '</span> thành công! ';
                        showCustomModal(message);
                    }
                });
            });
            
            // ----- Xử lý cập nhật topview phim ----- 
        $(document).on('change', '.select-topview', function() {
        // Triển khai tương tự với mã topview hiện có của bạn
                var topview = $(this).find(':selected').val(),
                    id_phim = $(this).attr('id'),
                    ten_phim = $(this).attr('title');

                var textTopview;
                if(topview == 0){
                    textTopview = 'Ngày';
                } else if(topview == 1){
                    textTopview = 'Tuần';
                } else if(topview == 2){
                    textTopview = 'Tháng';
                }

                $.ajax({
                    url: "{{url('/update-topview-phim')}}",
                    method: "GET",
                    data: {topview: topview, id_phim: id_phim, ten_phim: ten_phim},
                    success: function() {
                        const message = 'Phim "<span class="highlight-name">' + ten_phim + 
                            '</span>" đã được <span class="highlight">CẬP NHẬT</span> theo topview ' + 
                            '<span class="highlight-year">' + textTopview + '</span> thành công! ';
                        showCustomModal(message);
                    }
                });
            });
            
            // ----- Xử lý cập nhật season phim ----- 
            $(document).on('change', '.select-season', function() {
                var season = $(this).find(":selected").val(), 
                    id_phim = $(this).attr("id"),
                    ten_phim = $(this).attr("title"),
                    _token = $('input[name="_token"]').val();
                
                $.ajax({
                    url: "{{url('/update-season-phim')}}",
                    method: "POST",
                    data: {season: season, id_phim: id_phim, ten_phim:ten_phim, _token: _token},
                    success: function() {
                        const message = 'Phim "<span class="highlight-name">' + ten_phim + '</span>" đã được <span class="highlight">CẬP NHẬT</span> thành season ' + '<span class="highlight-year">' + season + '</span> thành công!';
                        showCustomModal(message);
                    }
                });
            });
                
        // ----- Hàm hiển thị modal tùy chỉnh ----- 
        function showCustomModal(messageHtml, seconds = 3) { 
            // Thiết lập nội dung thông báo 
            $('.success-message').html(messageHtml);
            // Hiển thị modal
            $('#success-modal').css('display', 'flex');
            
            // Thiết lập bộ đếm 
            $('#countdown-timer').text(seconds);
            
            // Hủy bỏ bộ đếm trước đó (nếu có) 
            if (modalCountdownTimer) clearInterval(modalCountdownTimer);
            
            // Bắt đầu đếm ngược mới 
            modalCountdownTimer = setInterval(function() { 
                seconds--; 
                $('#countdown-timer').text(seconds);
                
                if (seconds <= 0) { 
                    clearInterval(modalCountdownTimer); 
                    closeCustomModal(); 
                } 
            }, 1000);
        }
        
        // ----- Hàm đóng modal tùy chỉnh ----- 
        function closeCustomModal() { 
            $('#success-modal').css('display', 'none'); 
            if (modalCountdownTimer) clearInterval(modalCountdownTimer); 
        }
        
        // ----- Sự kiện click nút OK ----- 
        $(document).on('click', '.ok-button', closeCustomModal);
    });
    </script>

    <!-- Script cho chức năng tạo slug --> 
    <script>
    function ChangeToSlug() { 
        var slug; 
        // Lấy text từ thẻ input title 
        title = document.getElementById("slug").value;
        
        // Đổi chữ hoa thành chữ thường 
        slug = title.toLowerCase();
        
        // Đổi ký tự có dấu thành không dấu 
        slug = slug.replace(/á|à|ả|ạ|ã|ă|ắ|ằ|ẳ|ẵ|ặ|â|ấ|ầ|ẩ|ẫ|ậ/gi, 'a'); 
        slug = slug.replace(/é|è|ẻ|ẽ|ẹ|ê|ế|ề|ể|ễ|ệ/gi, 'e'); 
        slug = slug.replace(/i|í|ì|ỉ|ĩ|ị/gi, 'i'); 
        slug = slug.replace(/ó|ò|ỏ|õ|ọ|ô|ố|ồ|ổ|ỗ|ộ|ơ|ớ|ờ|ở|ỡ|ợ/gi, 'o'); 
        slug = slug.replace(/ú|ù|ủ|ũ|ụ|ư|ứ|ừ|ử|ữ|ự/gi, 'u'); 
        slug = slug.replace(/ý|ỳ|ỷ|ỹ|ỵ/gi, 'y'); 
        slug = slug.replace(/đ/gi, 'd');
        
        // Xóa các ký tự đặt biệt 
        slug = slug.replace(/\`|\~|\!|\@|\#|\||\$|\%|\^|\&|\*|\(|\)|\+|\=|\,|\.|\/|\?|\>|\<|\'|\"|\:|\;|_/gi, '');
        
        // Đổi khoảng trắng thành ký tự gạch ngang 
        slug = slug.replace(/ /gi, "-");
        
        // Đổi nhiều ký tự gạch ngang liên tiếp thành 1 ký tự gạch ngang 
        slug = slug.replace(/\-\-\-\-\-/gi, '-'); 
        slug = slug.replace(/\-\-\-\-/gi, '-'); 
        slug = slug.replace(/\-\-\-/gi, '-'); 
        slug = slug.replace(/\-\-/gi, '-');
        
        // Xóa các ký tự gạch ngang ở đầu và cuối 
        slug = '@' + slug + '@'; 
        slug = slug.replace(/\@\-|\-\@|\@/gi, '');
        
        // In slug ra textbox có id "slug" 
        document.getElementById('convert_slug').value = slug; 
    }
    </script>

    <!-- Script cho chức năng sắp xếp với SweetAlert --> 
<script>
    $(document).ready(function() { 
        // Lưu thứ tự ban đầu 
        let originalOrder = [];
        
        $(".order_position").sortable({ 
            placeholder: "ui-state-highlight", // Áp dụng hiệu ứng nhấp nháy 
            start: function() { 
                originalOrder = $(".order_position tr").map(function() { 
                    return $(this).attr("id"); 
                }).get(); 
            }, 
            update: function() { 
                let newOrder = [];
                
                $(".order_position tr").each(function() { 
                    newOrder.push($(this).attr("id")); 
                });
                
                // Kiểm tra xem có thay đổi thực sự không 
                if (JSON.stringify(originalOrder) === JSON.stringify(newOrder)) { 
                    console.log("Không có thay đổi, không gửi AJAX."); 
                    return; 
                }
                
                $.ajax({ 
                    headers: { 
                        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content") 
                    }, 
                    url: "{{ route('resorting') }}", 
                    method: "POST", 
                    data: { 
                        array_id: newOrder 
                    }, 
                    success: function() { 
                        showSuccessMessage(); 
                    }, 
                    error: function(xhr) { 
                        console.error("Lỗi:", xhr); 
                        showErrorMessage(); 
                    } 
                }); 
            } 
        });
        
        // Thông báo thành công khi sắp xếp - sử dụng SweetAlert 
        function showSuccessMessage() { 
            let timeLeft = 3; 
            let timerInterval;
            
            Swal.fire({ 
                title: "✅ Thành công!", 
                html: `Sắp xếp thứ tự đã được cập nhật.<br><b>Tự động đóng sau <span id="countdown">${timeLeft}</span> giây</b>`, 
                icon: "success", 
                timer: timeLeft * 1000, 
                timerProgressBar: true, 
                showConfirmButton: true, 
                didOpen: () => { 
                    const countdownEl = document.getElementById("countdown"); 
                    timerInterval = setInterval(() => { 
                        timeLeft--; 
                        countdownEl.textContent = timeLeft; 
                        if (timeLeft <= 0) { 
                            clearInterval(timerInterval); 
                        } 
                    }, 1000); 
                }, 
                willClose: () => { 
                    clearInterval(timerInterval); 
                } 
            }); 
        }
        
        // Thông báo lỗi khi sắp xếp - sử dụng SweetAlert 
        function showErrorMessage() { 
            Swal.fire({ 
                title: "❌ Lỗi!", 
                text: "Đã có lỗi xảy ra, vui lòng thử lại.", 
                icon: "error", 
                timer: 2500, 
                timerProgressBar: true, 
                showConfirmButton: true 
            }); 
        } 
    });
</script>


</body>
</html>
