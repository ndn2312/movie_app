@extends('layouts.app')

@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
<style>
    /* Main grid layout */
    .category-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 20px;
        margin-bottom: 30px;
    }

    @media (max-width: 1200px) {
        .category-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media (max-width: 768px) {
        .category-grid {
            grid-template-columns: 1fr;
        }
    }

    /* Section title */
    .section-title {
        font-size: 1.8rem;
        font-weight: 600;
        color: #333;
        margin-bottom: 1.5rem;
        padding-bottom: 0.75rem;
        border-bottom: 2px solid #e5e7eb;
    }

    /* Card styles */
    .category-card {
        background-color: #fff;
        border-radius: 12px;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
        overflow: hidden;
        transition: all 0.3s;
        border: 1px solid #eaeaea;
        display: flex;
        flex-direction: column;
        height: 100%;
    }

    .category-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.12);
    }

    /* Card header */
    .category-card-header {
        padding: 15px 20px;
        background: linear-gradient(45deg, #4a6bff, #6e8fff);
        color: #fff;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .category-title {
        margin: 0;
        font-size: 1.25rem;
        font-weight: 600;
        text-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
        max-width: 70%;
    }

    .category-status {
        padding: 4px 10px;
        border-radius: 20px;
        font-size: 0.85rem;
        font-weight: 500;
        display: flex;
        align-items: center;
    }

    .category-status.active {
        background-color: rgba(46, 213, 115, 0.2);
        color: rgb(21, 255, 0);
    }

    .category-status.inactive {
        background-color: rgba(255, 71, 87, 0.2);
        color: #ff0015;
    }

    .category-status i {
        margin-right: 5px;
        font-size: 0.7rem;
    }

    /* Card info section */
    .category-info {
        padding: 20px;
        flex-grow: 1;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }

    .info-group {
        margin-bottom: 15px;
    }

    .info-group label {
        display: block;
        font-weight: 600;
        color: #555;
        margin-bottom: 5px;
        font-size: 0.9rem;
    }

    .info-group label i {
        margin-right: 5px;
        color: #4a6bff;
    }

    .info-group p {
        margin: 0;
        color: #444;
        word-break: break-word;
        font-size: 1rem;
    }

    .description {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .slug {
        color: #6b7280;
        font-family: monospace;
        background-color: #f1f5f9;
        padding: 5px 8px;
        border-radius: 4px;
        font-size: 0.85rem;
    }

    .info-dates {
        display: flex;
        justify-content: space-between;
        border-top: 1px solid #eaeaea;
        padding-top: 15px;
        margin-top: 10px;
    }

    .date-group {
        font-size: 0.8rem;
        color: #777;
        display: flex;
        align-items: center;
    }

    .date-group i {
        margin-right: 5px;
        color: #6b7280;
    }

    /* Card actions */
    .category-actions {
        padding: 15px 20px;
        background-color: #f9fafb;
        border-top: 1px solid #eaeaea;
        display: flex;
        justify-content: flex-end;
        gap: 10px;
    }

    .category-actions .btn {
        padding: 5px 15px;
    }

    .category-actions .btn i {
        margin-right: 5px;
    }

    /* Add button */
    .button-custom {
        width: auto;
        height: 46px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1rem;
        font-weight: 600;
        border-radius: 8px;
        background: linear-gradient(90deg, #4a6bff, #6e8fff);
        box-shadow: 0 4px 10px rgba(74, 107, 255, 0.3);
        color: white;
        text-decoration: none;
        transition: all 0.3s ease-in-out;
        padding: 0 20px;
    }

    .button-custom:hover {
        background: linear-gradient(90deg, #6e8fff, #4a6bff);
        transform: translateY(-2px);
        box-shadow: 0 6px 15px rgba(74, 107, 255, 0.4);
    }

    .button-add {
        background: linear-gradient(90deg, #2ed573, #7bed9f);
        box-shadow: 0 4px 10px rgba(46, 213, 115, 0.3);
    }

    .button-add:hover {
        background: linear-gradient(90deg, #7bed9f, #2ed573);
        box-shadow: 0 6px 15px rgba(46, 213, 115, 0.4);
    }

    /* Pagination styles */
    .custom-pagination {
        background-color: #fff;
        border-radius: 30px;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        display: inline-flex;
        overflow: hidden;
        padding: 5px;
    }

    .custom-pagination .page-item {
        margin: 0 3px;
    }

    .custom-pagination .page-link {
        border: none;
        border-radius: 50%;
        color: #333;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 500;
        height: 36px;
        min-width: 36px;
        padding: 0;
        transition: all 0.3s;
    }

    .custom-pagination .page-item:first-child .page-link,
    .custom-pagination .page-item:last-child .page-link {
        border-radius: 50%;
    }

    .custom-pagination .page-item.active .page-link {
        background: linear-gradient(135deg, #4a6bff, #6e8fff);
        color: #fff;
        box-shadow: 0 2px 10px rgba(74, 107, 255, 0.5);
    }

    .custom-pagination .page-item.disabled .page-link {
        color: #aaa;
        background-color: transparent;
    }

    .custom-pagination .page-link:hover:not(.disabled) {
        background: linear-gradient(135deg, #6e8fff, #4a6bff);
        color: #fff;
        transform: translateY(-2px);
        box-shadow: 0 5px 10px rgba(74, 107, 255, 0.3);
    }

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

    .success-icon {
        position: relative;
        z-index: 2;
    }

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
        text-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
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
        text-shadow: 0 1px 1px rgba(0, 0, 0, 0.2);
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    }

    .action-add {
        background: linear-gradient(135deg, #4CAF50, #2E7D32);
    }

    .action-update {
        background: linear-gradient(135deg, #FFC107, #FFA000);
        color: #212121;
    }

    .action-delete {
        background: linear-gradient(135deg, #F44336, #C62828);
    }

    .highlighted-text {
        color: #2563eb;
        font-weight: 600;
    }

    .notification-countdown {
        color: #555;
        font-size: 1rem;
        margin-bottom: 1.25rem;
        font-weight: 500;
    }

    .timer-container {
        display: inline-block;
        background: #f0f0f0;
        width: 36px;
        height: 36px;
        line-height: 36px;
        border-radius: 50%;
        color: #333;
        font-weight: bold;
        margin: 0 5px;
        box-shadow: inset 0 0 5px rgba(0, 0, 0, 0.1);
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
    @keyframes drawCheckmark {
        0% {
            stroke-dashoffset: 48;
        }

        100% {
            stroke-dashoffset: 0;
        }
    }

    @keyframes modalFadeIn {
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
            transform: scale(1);
        }

        to {
            opacity: 0;
            transform: scale(0.95);
        }
    }

    @keyframes modalPop {
        0% {
            transform: scale(0.9) rotateX(5deg);
            opacity: 0;
        }

        40% {
            transform: scale(1.02) rotateX(0);
        }

        60% {
            transform: scale(0.98) rotateX(0);
        }

        100% {
            transform: scale(1) rotateX(0);
            opacity: 1;
        }
    }

    @keyframes pulseGlowGreen {

        0%,
        100% {
            transform: scale(1);
            box-shadow: 0 0 0 0 rgba(76, 175, 80, 0.4);
        }

        50% {
            transform: scale(1.05);
            box-shadow: 0 0 20px 10px rgba(76, 175, 80, 0.2);
        }
    }

    @keyframes pulseGlowYellow {

        0%,
        100% {
            transform: scale(1);
            box-shadow: 0 0 0 0 rgba(255, 193, 7, 0.4);
        }

        50% {
            transform: scale(1.05);
            box-shadow: 0 0 20px 10px rgba(255, 193, 7, 0.2);
        }
    }

    @keyframes pulseGlowRed {

        0%,
        100% {
            transform: scale(1);
            box-shadow: 0 0 0 0 rgba(244, 67, 54, 0.4);
        }

        50% {
            transform: scale(1.05);
            box-shadow: 0 0 20px 10px rgba(244, 67, 54, 0.2);
        }
    }

    @keyframes actionPulse {

        0%,
        100% {
            transform: scale(1);
        }

        50% {
            transform: scale(1.05);
        }
    }

    @keyframes timerPulse {

        0%,
        100% {
            transform: scale(1);
        }

        50% {
            transform: scale(1.08);
        }
    }
</style>
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <!-- Tiêu đề và nút thêm mới -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="section-title"><i class="fas fa-list-alt me-2"></i>Quản lý danh mục phim</h2>
                <a href="{{ route('category.create') }}" class="button-custom button-add">
                    <i class="fas fa-plus-circle me-2"></i> THÊM DANH MỤC
                </a>
            </div>

            <!-- Hiển thị danh mục dạng grid -->
            <div class="category-grid" id="categoryGrid">
                @foreach($list as $key => $cate)
                <div class="category-card" data-status="{{ $cate->status }}" data-id="{{ $cate->id }}" onclick="window.location.href='{{ route('category.edit', $cate->id) }}'">
                    <!-- Phần header của card -->
                    <div class="category-card-header">
                        <h4 class="category-title">{{ $cate->title }}</h4>
                        <div class="category-status {{ $cate->status ? 'active' : 'inactive' }}">
                            <i class="fas fa-circle"></i>
                            <span>{{ $cate->status ? 'Hiển thị' : 'Ẩn' }}</span>
                        </div>
                    </div>

                    <!-- Phần thông tin của card -->
                    <div class="category-info">
                        <div class="info-group">
                            <label><i class="fas fa-info-circle"></i> Mô tả:</label>
                            <p class="description">{{ $cate->description ?: 'Chưa có mô tả' }}</p>
                        </div>
                        <div class="info-group">
                            <label><i class="fas fa-link"></i> Đường dẫn:</label>
                            <p class="slug">{{ $cate->slug }}</p>
                        </div>
                        <div class="info-dates">
                            <div class="date-group">
                                <i class="fas fa-calendar-plus"></i>
                                <span>Tạo: {{ \Carbon\Carbon::parse($cate->ngaytao)->format('d/m/Y H:i') }}</span>
                            </div>
                            <div class="date-group">
                                <i class="fas fa-calendar-check"></i>
                                <span>Cập nhật: {{ \Carbon\Carbon::parse($cate->ngaycapnhat)->format('d/m/Y H:i')
                                    }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Phần hành động của card -->
                    <div class="category-actions">
                        <a href="{{ route('category.edit', $cate->id) }}" class="btn btn-warning btn-sm">
                            <i class="fas fa-edit"></i> Sửa
                        </a>

                        {!! Form::open([
                        'method' => 'DELETE',
                        'route' => ['category.destroy', $cate->id],
                        'class' => 'd-inline delete-form'
                        ]) !!}
                        <button type="submit" class="btn btn-danger btn-sm"
                            onclick="return confirm('Bạn có chắc muốn xóa danh mục này?')">
                            <i class="fas fa-trash-alt"></i> Xóa
                        </button>
                        {!! Form::close() !!}
                    </div>
                </div>
                @endforeach
            </div>

            <!-- Phân trang đẹp -->
            @if(isset($list) && $list instanceof \Illuminate\Pagination\LengthAwarePaginator && $list->lastPage() > 1)
            <div class="pagination-container d-flex justify-content-center mt-4">
                <ul class="pagination custom-pagination">
                    {{-- Nút First và Previous --}}
                    <li class="page-item {{ ($list->currentPage() == 1) ? 'disabled' : '' }}">
                        <a class="page-link" href="{{ $list->url(1) }}" aria-label="First">
                            <i class="fas fa-angle-double-left"></i>
                        </a>
                    </li>
                    <li class="page-item {{ ($list->currentPage() == 1) ? 'disabled' : '' }}">
                        <a class="page-link" href="{{ $list->url($list->currentPage() - 1) }}" aria-label="Previous">
                            <i class="fas fa-angle-left"></i>
                        </a>
                    </li>

                    {{-- Hiển thị các số trang --}}
                    @php
                    $start = max(1, $list->currentPage() - 2);
                    $end = min($start + 4, $list->lastPage());
                    $start = max(1, $end - 4);
                    @endphp

                    @if($start > 1)
                    <li class="page-item"><a class="page-link" href="{{ $list->url(1) }}">1</a></li>
                    @if($start > 2)
                    <li class="page-item disabled"><span class="page-link">...</span></li>
                    @endif
                    @endif

                    @for ($i = $start; $i <= $end; $i++) <li
                        class="page-item {{ ($list->currentPage() == $i) ? 'active' : '' }}">
                        <a class="page-link" href="{{ $list->url($i) }}">{{ $i }}</a>
                        </li>
                        @endfor

                        @if($end < $list->lastPage())
                            @if($end < $list->lastPage() - 1)
                                <li class="page-item disabled"><span class="page-link">...</span></li>
                                @endif
                                <li class="page-item"><a class="page-link" href="{{ $list->url($list->lastPage()) }}">{{
                                        $list->lastPage() }}</a></li>
                                @endif

                                {{-- Nút Next và Last --}}
                                <li
                                    class="page-item {{ ($list->currentPage() == $list->lastPage()) ? 'disabled' : '' }}">
                                    <a class="page-link" href="{{ $list->url($list->currentPage() + 1) }}"
                                        aria-label="Next">
                                        <i class="fas fa-angle-right"></i>
                                    </a>
                                </li>
                                <li
                                    class="page-item {{ ($list->currentPage() == $list->lastPage()) ? 'disabled' : '' }}">
                                    <a class="page-link" href="{{ $list->url($list->lastPage()) }}" aria-label="Last">
                                        <i class="fas fa-angle-double-right"></i>
                                    </a>
                                </li>
                </ul>
            </div>
            @endif
        </div>
    </div>
</div>

@if (session('success'))
<style>

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
                    <svg xmlns="http://www.w3.org/2000/svg" width="42" height="42" viewBox="0 0 24 24" fill="none"
                        stroke="{{ $iconColor }}" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                        <polyline class="checkmark" points="4 12 9 17 20 6"></polyline>
                    </svg>
                </div>
            </div>

            <h3 class="notification-title">Thành công!</h3>
            <p class="notification-message">
                Đã <span
                    class="action-highlight action-{{ $action == 'thêm' ? 'add' : ($action == 'xóa' ? 'delete' : 'update') }}">
                    {{ $action }}
                </span>
                <span class="highlighted-text">{{ session('item_type') }} "<strong>{{ session('item_name')
                        }}</strong>"</span> thành công.
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