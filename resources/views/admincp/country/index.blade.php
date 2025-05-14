@extends('layouts.app')

@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
<style>
    /* Main grid layout */
    .country-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 20px;
        margin-bottom: 30px;
        transition: all 0.3s ease;
    }

    @media (max-width: 1200px) {
        .country-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media (max-width: 768px) {
        .country-grid {
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
    .country-card {
        background-color: #fff;
        border-radius: 12px;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
        overflow: hidden;
        transition: all 0.3s;
        border: 1px solid #eaeaea;
        display: flex;
        flex-direction: column;
        height: 100%;
        animation: fadeIn 0.5s ease-out;
    }

    .country-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.12);
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(20px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* Card header */
    .country-card-header {
        padding: 15px 20px;
        background: linear-gradient(45deg, #4a6bff, #6e8fff);
        color: #fff;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .country-title {
        margin: 0;
        font-size: 1.25rem;
        font-weight: 600;
        text-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
        max-width: 70%;
    }

    .country-status {
        padding: 4px 10px;
        border-radius: 20px;
        font-size: 0.85rem;
        font-weight: 500;
        display: flex;
        align-items: center;
    }

    .country-status.active {
        background-color: rgba(46, 213, 115, 0.2);
        color: rgb(21, 255, 0);
    }

    .country-status.inactive {
        background-color: rgba(255, 71, 87, 0.2);
        color: #ff0015;
    }

    .country-status i {
        margin-right: 5px;
        font-size: 0.7rem;
    }

    /* Card info section */
    .country-info {
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

    /* Card actions */
    .country-actions {
        padding: 15px 20px;
        background-color: #f9fafb;
        border-top: 1px solid #eaeaea;
        display: flex;
        justify-content: flex-end;
        gap: 10px;
    }

    .country-actions .btn {
        padding: 5px 15px;
    }

    .country-actions .btn i {
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

    /* Search form styles */
    .search-container {
        margin-bottom: 2rem;
        position: relative;
    }

    .search-form .input-group {
        border-radius: 12px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        overflow: hidden;
        transition: all 0.3s;
    }

    .search-form .input-group:focus-within {
        box-shadow: 0 6px 16px rgba(74, 107, 255, 0.16);
        transform: translateY(-2px);
    }

    .search-form .form-control {
        border: none;
        padding: 0.8rem 1.2rem;
        font-size: 1rem;
        height: auto;
        transition: all 0.3s;
    }

    .search-form .btn {
        padding: 0.8rem 1.5rem;
        font-weight: 500;
        transition: all 0.3s;
    }

    .search-form .btn-primary {
        background: linear-gradient(90deg, #4a6bff, #6e8fff);
        border: none;
    }

    .search-form .btn-primary:hover {
        transform: translateX(3px);
    }

    .search-form .btn-secondary {
        background: #f1f5f9;
        border: none;
        color: #64748b;
    }

    .search-form .btn-secondary:hover {
        background: #e2e8f0;
    }

    /* Animated spinner */
    .search-spinner {
        display: none;
        position: absolute;
        right: 20px;
        top: 50%;
        transform: translateY(-50%);
        width: 24px;
        height: 24px;
        border: 3px solid rgba(74, 107, 255, 0.3);
        border-radius: 50%;
        border-top-color: #4a6bff;
        animation: spin 1s linear infinite;
    }

    @keyframes spin {
        to {
            transform: translateY(-50%) rotate(360deg);
        }
    }

    /* Search result message */
    .search-result-msg {
        background-color: #f1f5f9;
        border-radius: 8px;
        padding: 12px 20px;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        color: #334155;
        animation: slideIn 0.4s ease-out;
    }

    @keyframes slideIn {
        from {
            opacity: 0;
            transform: translateX(-20px);
        }

        to {
            opacity: 1;
            transform: translateX(0);
        }
    }

    .search-result-msg i {
        margin-right: 10px;
        color: #4a6bff;
        font-size: 1.2rem;
    }

    .search-result-highlight {
        font-weight: 600;
        color: #4a6bff;
    }

    .no-results {
        text-align: center;
        padding: 3rem;
        background-color: #f9fafb;
        border-radius: 12px;
        margin: 2rem 0;
        animation: fadeIn 0.5s ease-out;
    }

    .no-results i {
        font-size: 3rem;
        color: #cbd5e1;
        margin-bottom: 1rem;
    }

    .no-results h3 {
        font-weight: 600;
        color: #334155;
        margin-bottom: 0.5rem;
    }

    .no-results p {
        color: #64748b;
    }

    /* Loading overlay */
    .loading-overlay {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(255, 255, 255, 0.7);
        display: none;
        justify-content: center;
        align-items: center;
        z-index: 1000;
        backdrop-filter: blur(4px);
    }

    .loading-spinner {
        width: 50px;
        height: 50px;
        border: 5px solid rgba(74, 107, 255, 0.2);
        border-radius: 50%;
        border-top-color: #4a6bff;
        animation: spin 1s linear infinite;
    }

    /* Toast notification */
    .toast-container {
        position: fixed;
        top: 20px;
        right: 20px;
        z-index: 9999;
    }

    .toast-notification {
        background: white;
        border-radius: 8px;
        box-shadow: 0 10px 15px rgba(0, 0, 0, 0.1);
        padding: 16px 20px;
        margin-bottom: 10px;
        display: flex;
        align-items: center;
        transform: translateX(120%);
        animation: slideInRight 0.3s forwards;
        border-left: 4px solid #4a6bff;
    }

    @keyframes slideInRight {
        to {
            transform: translateX(0);
        }
    }

    .toast-notification.success {
        border-left-color: #2ed573;
    }

    .toast-notification.error {
        border-left-color: #ff4757;
    }

    .toast-icon {
        margin-right: 12px;
        font-size: 20px;
    }

    .toast-icon.success {
        color: #2ed573;
    }

    .toast-icon.error {
        color: #ff4757;
    }

    .toast-message {
        flex-grow: 1;
        font-size: 14px;
        color: #333;
    }

    .toast-close {
        background: none;
        border: none;
        color: #777;
        cursor: pointer;
        font-size: 16px;
        padding: 0;
        margin-left: 10px;
    }

    /* Notification modal styles */
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
            <h2 class="section-title">
                <i class="fas fa-globe-asia"></i> Quản lý quốc gia phim
            </h2>

            <div class="d-flex justify-content-between align-items-center mb-4">
                <a href="{{ route('country.create') }}" class="button-custom button-add">
                    <i class="fas fa-plus-circle"></i> Thêm quốc gia
                </a>
            </div>

            <!-- Form tìm kiếm quốc gia -->
            <div class="search-container">
                <form id="searchForm" class="search-form">
                    <div class="input-group">
                        <input type="text" name="search" id="searchInput" class="form-control"
                            placeholder="Tìm kiếm quốc gia..." value="{{ request('search') }}" autocomplete="off">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-search"></i> Tìm kiếm
                        </button>
                        <button type="button" id="clearSearch" class="btn btn-secondary"
                            style="{{ request('search') ? '' : 'display: none;' }}">
                            <i class="fas fa-times"></i> Xóa
                        </button>
                    </div>
                    <div class="search-spinner" id="searchSpinner"></div>
                </form>
            </div>

            <!-- Thông báo kết quả tìm kiếm -->
            <div class="search-result-msg" id="searchResultMsg" style="display: none;">
                <i class="fas fa-info-circle"></i>
                <div id="searchResultText"></div>
            </div>

            <!-- Hiển thị quốc gia dạng grid -->
            <div id="countryGrid" class="country-grid">
                @foreach($list as $key => $country)
                <div class="country-card">
                    <div class="country-card-header">
                        <h4 class="country-title">{{ $country->title }}</h4>
                        <div class="country-status {{ $country->status == 1 ? 'active' : 'inactive' }}">
                            <i class="fas fa-circle"></i>
                            <span>{{ $country->status == 1 ? 'Hiển thị' : 'Ẩn' }}</span>
                        </div>
                    </div>

                    <div class="country-info">
                        <div class="info-group">
                            <label><i class="fas fa-info-circle"></i> Mô tả:</label>
                            <p class="description">{{ $country->description ? $country->description : 'Chưa có mô tả' }}
                            </p>
                        </div>
                        <div class="info-group">
                            <label><i class="fas fa-link"></i> Đường dẫn:</label>
                            <p class="slug">{{ $country->slug }}</p>
                        </div>
                    </div>

                    <div class="country-actions">
                        <a href="{{ route('country.edit', $country->id) }}" class="btn btn-warning btn-sm">
                            <i class="fas fa-edit"></i> Sửa
                        </a>

                        <form method="POST" action="{{ route('country.destroy', $country->id) }}"
                            class="d-inline delete-form">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm delete-btn">
                                <i class="fas fa-trash-alt"></i> Xóa
                            </button>
                        </form>
                    </div>
                </div>
                @endforeach
            </div>

            <!-- Hiển thị không có kết quả -->
            <div class="no-results" id="noResults" style="display: none;">
                <i class="fas fa-search"></i>
                <h3>Không tìm thấy quốc gia nào</h3>
                <p>Vui lòng thử lại với từ khóa khác hoặc tạo quốc gia mới</p>
                <a href="{{ route('country.create') }}" class="btn btn-primary mt-3">
                    <i class="fas fa-plus-circle"></i> Thêm quốc gia mới
                </a>
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-center" id="paginationContainer">
                {{ $list->links() }}
            </div>

            <!-- Loading overlay -->
            <div class="loading-overlay" id="loadingOverlay">
                <div class="loading-spinner"></div>
            </div>

            <!-- Toast container -->
            <div class="toast-container" id="toastContainer"></div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        let typingTimer;
        const doneTypingInterval = 500; // Time in ms
        let currentPage = 1;
        let lastSearchTerm = '';

        // Xử lý submit form tìm kiếm
        $('#searchForm').on('submit', function(e) {
            e.preventDefault();
            const searchTerm = $('#searchInput').val().trim();
            performSearch(searchTerm, 1);
        });

        // Xử lý tìm kiếm khi gõ (debounce)
        $('#searchInput').on('input', function() {
            clearTimeout(typingTimer);
            
            const searchTerm = $(this).val().trim();
            
            // Hiển thị hoặc ẩn nút xóa tìm kiếm
            if (searchTerm.length > 0) {
                $('#clearSearch').show();
            } else {
                $('#clearSearch').hide();
            }
            
            // Đặt hẹn giờ cho việc tìm kiếm
            typingTimer = setTimeout(function() {
                performSearch(searchTerm, 1);
            }, doneTypingInterval);
        });

        // Xử lý khi nhấn nút xóa tìm kiếm
        $('#clearSearch').on('click', function() {
            $('#searchInput').val('');
            $(this).hide();
            performSearch('', 1);
        });

        // Xử lý phân trang Ajax
        $(document).on('click', '#paginationContainer .page-link', function(e) {
            e.preventDefault();
            const url = $(this).attr('href');
            
            if (!url) return; // Nếu là nút disabled
            
            // Trích xuất số trang từ URL
            const pageMatch = url.match(/page=(\d+)/);
            if (pageMatch && pageMatch[1]) {
                const pageNumber = pageMatch[1];
                performSearch($('#searchInput').val().trim(), pageNumber);
            }
        });

        // Xử lý xóa quốc gia bằng Ajax
        $(document).on('submit', '.delete-form', function(e) {
            e.preventDefault();
            
            if (confirm('Bạn có chắc muốn xóa quốc gia này?')) {
                const form = $(this);
                const url = form.attr('action');
                
                $.ajax({
                    url: url,
                    type: 'POST',
                    data: form.serialize(),
                    beforeSend: function() {
                        showLoading();
                    },
                    success: function(response) {
                        hideLoading();
                        showToast('success', 'Xóa quốc gia thành công!');
                        performSearch($('#searchInput').val().trim(), currentPage);
                    },
                    error: function(xhr) {
                        hideLoading();
                        showToast('error', 'Có lỗi xảy ra khi xóa quốc gia!');
                    }
                });
            }
        });

        // Hàm thực hiện tìm kiếm Ajax
        function performSearch(searchTerm, page) {
            // Nếu tìm kiếm giống lần trước và trang giống nhau, không làm gì cả
            if (searchTerm === lastSearchTerm && page === currentPage) {
                return;
            }
            
            lastSearchTerm = searchTerm;
            currentPage = page;
            
            // Hiển thị spinner tìm kiếm
            $('#searchSpinner').show();
            
            $.ajax({
                url: '{{ route("country.index") }}',
                type: 'GET',
                data: {
                    search: searchTerm,
                    page: page,
                    ajax: 1 // Dấu hiệu để controller biết đây là request Ajax
                },
                success: function(response) {
                    updateUI(response, searchTerm);
                    $('#searchSpinner').hide();
                    
                    // Cập nhật URL trong trình duyệt không tải lại trang
                    const url = new URL(window.location);
                    if (searchTerm) {
                        url.searchParams.set('search', searchTerm);
                    } else {
                        url.searchParams.delete('search');
                    }
                    url.searchParams.set('page', page);
                    window.history.pushState({}, '', url);
                },
                error: function(xhr) {
                    $('#searchSpinner').hide();
                    showToast('error', 'Có lỗi xảy ra khi tìm kiếm. Vui lòng thử lại!');
                }
            });
        }
        
        // Cập nhật giao diện với kết quả tìm kiếm
        function updateUI(response, searchTerm) {
            // Cập nhật grid quốc gia
            $('#countryGrid').html(response.html);
            
            // Cập nhật phân trang
            $('#paginationContainer').html(response.pagination);
            
            // Hiển thị/ẩn thông báo kết quả tìm kiếm
            if (searchTerm) {
                $('#searchResultMsg').show();
                if (response.total > 0) {
                    $('#searchResultText').html(`Tìm thấy <span class="search-result-highlight">${response.total}</span> kết quả cho <span class="search-result-highlight">"${searchTerm}"</span>`);
                } else {
                    $('#searchResultText').html(`Không tìm thấy kết quả nào cho <span class="search-result-highlight">"${searchTerm}"</span>`);
                }
            } else {
                $('#searchResultMsg').hide();
            }
            
            // Hiển thị thông báo không có kết quả nếu cần
            if (response.total === 0) {
                $('#countryGrid').hide();
                $('#paginationContainer').hide();
                $('#noResults').show();
            } else {
                $('#countryGrid').show();
                $('#paginationContainer').show();
                $('#noResults').hide();
                
                // Thêm hiệu ứng cho các card mới
                $('.country-card').each(function(index) {
                    $(this).css('animation-delay', (index * 0.1) + 's');
                });
            }
        }
        
        // Hiển thị loading overlay
        function showLoading() {
            $('#loadingOverlay').css('display', 'flex');
        }
        
        // Ẩn loading overlay
        function hideLoading() {
            $('#loadingOverlay').css('display', 'none');
        }
        
        // Hiển thị toast notification
        function showToast(type, message) {
            const toast = `
                <div class="toast-notification ${type}">
                    <i class="toast-icon ${type} fas ${type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle'}"></i>
                    <div class="toast-message">${message}</div>
                    <button class="toast-close">&times;</button>
                </div>
            `;
            
            $('#toastContainer').append(toast);
            
            // Tự động đóng toast sau 5 giây
            setTimeout(function() {
                $('#toastContainer').find('.toast-notification').first().remove();
            }, 5000);
            
            // Xử lý nút đóng toast
            $('.toast-close').off('click').on('click', function() {
                $(this).closest('.toast-notification').remove();
            });
        }
    });
</script>

@if (session('success'))
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