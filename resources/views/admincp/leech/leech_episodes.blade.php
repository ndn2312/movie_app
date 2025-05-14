@extends('layouts.app')

@section('content')
<div class="dashboard-container py-4">
    <div class="page-header mb-4">
        <div class="glass-card">
            <h3 class="page-title text-gradient">Quản Lý Tập Phim</h3>
            <p class="text-muted">Tổng hợp tập phim đã leech</p>
        </div>
    </div>

    <!-- Thêm bộ lọc và tìm kiếm -->
    <div class="filter-section mb-4">
        <div class="search-box">
            <i class="fas fa-search"></i>
            <input type="text" id="episode-search" class="form-control" placeholder="Tìm kiếm tập phim...">
        </div>
        <div class="filter-buttons">
            <button class="btn btn-primary btn-sm me-2" id="refresh-btn"><i class="fas fa-sync-alt me-1"></i> Làm
                mới</button>
            <button class="btn btn-secondary btn-sm" id="filter-btn"><i class="fas fa-filter me-1"></i> Lọc</button>
        </div>
    </div>

    <!-- Bảng hiển thị desktop -->
    <div class="data-table-container d-none d-xl-block mb-5">
        <div class="glass-card">
            <div class="table-responsive">
                <table class="table custom-table">
                    <thead>
                        <tr>
                            <th><span class="th-content">#</span></th>
                            <th><span class="th-content">ID</span></th>
                            <th><span class="th-content">Tên phim</span></th>
                            <th><span class="th-content">Tên tiếng Anh</span></th>
                            <th><span class="th-content">Slug</span></th>
                            <th><span class="th-content">Tập</span></th>
                            <th><span class="th-content">Server</span></th>
                            <th><span class="th-content">Thao tác</span></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($resp['episodes'] as $key => $res)
                        <tr class="table-row-hover">
                            <td>
                                <div class="badge badge-circle">{{$key}}</div>
                            </td>
                            <td>
                                <span class="id-tag">{{$resp['movie']['_id']}}</span>
                            </td>
                            <td>
                                <div class="movie-title">{{$resp['movie']['name']}}</div>
                            </td>
                            <td>
                                <div class="movie-origin">{{$resp['movie']['origin_name']}}</div>
                            </td>
                            <td>
                                <div class="slug-text text-truncate">{{$resp['movie']['slug']}}</div>
                            </td>
                            <td>
                                <div class="episode-badge">
                                    <span class="current">{{$resp['movie']['episode_current']}}</span>
                                    <span class="divider">/</span>
                                    <span class="total">{{$resp['movie']['episode_total']}}</span>
                                </div>
                            </td>
                            <td>
                                <button class="btn btn-outline-info btn-rounded server-btn" data-bs-toggle="modal"
                                    data-bs-target="#modal-{{$key}}">
                                    <i class="fas fa-server me-1"></i> {{$res['server_name']}}
                                </button>
                            </td>
                            <td>
                                <div class="action-buttons">
                                    <form method="post"
                                        action="{{route('leech-episode-store', [$resp['movie']['slug']])}}">
                                        @csrf
                                        <input type="hidden" name="server_name" value="{{$res['server_name']}}">
                                        <button type="submit" class="btn btn-success btn-icon" data-bs-toggle="tooltip"
                                            data-bs-placement="top"
                                            title="Thêm tập phim server {{$res['server_name']}}">
                                            <i class="fas fa-plus"></i>
                                        </button>
                                    </form>
                                    <form method="post">
                                        @csrf
                                        <button type="submit" class="btn btn-danger btn-icon" data-bs-toggle="tooltip"
                                            data-bs-placement="top" title="Xóa tập phim">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                    <button class="btn btn-primary btn-icon" data-bs-toggle="tooltip"
                                        data-bs-placement="top" title="Xem chi tiết">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Grid layout chính -->
    <div class="row g-4" id="episode-grid-container">
        @foreach ($resp['episodes'] as $key => $res)
        <div class="col-xxl-3 col-xl-4 col-lg-4 col-md-6 col-sm-12">
            <div class="movie-card" data-name="{{$resp['movie']['name']}}"
                data-origin-name="{{$resp['movie']['origin_name']}}" data-id="{{$resp['movie']['_id']}}">
                <div class="movie-card-header">
                    <div class="episode-number">Server {{$key+1}}</div>
                    <div class="server-badge">{{$res['server_name']}}</div>
                </div>
                <div class="movie-card-body">
                    <h4 class="movie-name">{{$resp['movie']['name']}}</h4>
                    <div class="movie-original-name">{{$resp['movie']['origin_name']}}</div>

                    <div class="movie-info">
                        <div class="info-item">
                            <span class="info-label">ID:</span>
                            <span class="info-value id-value">{{$resp['movie']['_id']}}</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Slug:</span>
                            <span class="info-value slug-value text-truncate">{{$resp['movie']['slug']}}</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Tập:</span>
                            <span class="info-value episode-count">{{count($res['server_data'])}} tập</span>
                        </div>
                    </div>

                    <button class="btn btn-primary btn-block server-modal-btn" data-bs-toggle="modal"
                        data-bs-target="#modal-{{$key}}">
                        <i class="fas fa-server me-2"></i> Xem thông tin server
                    </button>

                    <div class="action-row">
                        <form method="post" action="{{route('leech-episode-store', [$resp['movie']['slug']])}}"
                            class="w-100">
                            @csrf
                            <input type="hidden" name="server_name" value="{{$res['server_name']}}">
                            <button type="submit" class="btn btn-success btn-sm w-100">
                                <i class="fas fa-plus me-1"></i> Thêm Server {{$res['server_name']}}
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Phân trang -->
    <div class="pagination-container mt-4">
        <nav aria-label="Page navigation">
            <ul class="pagination justify-content-center" id="pagination">
                <!-- Phân trang sẽ được tạo bằng JavaScript -->
            </ul>
        </nav>
    </div>
</div>

<!-- Modals for each episode's server information -->
@foreach ($resp['episodes'] as $key => $res)
<div class="modal fade custom-modal" id="modal-{{$key}}" tabindex="-1" aria-labelledby="serverModalLabel-{{$key}}"
    aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <div class="modal-title-wrapper">
                    <h5 class="modal-title" id="serverModalLabel-{{$key}}">
                        <i class="fas fa-film text-primary me-2"></i>
                        <span class="text-gradient">{{$resp['movie']['name']}}</span>
                        <span class="server-badge-sm ms-2">{{$res['server_name']}}</span>
                    </h5>
                    <span class="modal-subtitle">Thông tin chi tiết server</span>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="server-info-header mb-3">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="server-detail">
                            <span class="server-label">Server:</span>
                            <span class="server-value">{{$res['server_name']}}</span>
                        </div>
                        <div class="episode-count-badge">
                            <i class="fas fa-film me-1"></i> {{count($res['server_data'])}} tập phim
                        </div>
                    </div>
                </div>
                <ul class="nav nav-pills custom-tabs mb-3" id="serverTab-{{$key}}" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="embed-tab-{{$key}}" data-bs-toggle="tab"
                            data-bs-target="#embed-{{$key}}" type="button" role="tab">
                            <i class="fas fa-code me-2"></i>Embed Links
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="m3u8-tab-{{$key}}" data-bs-toggle="tab"
                            data-bs-target="#m3u8-{{$key}}" type="button" role="tab">
                            <i class="fas fa-play-circle me-2"></i>M3U8 Links
                        </button>
                    </li>
                </ul>
                <div class="tab-content" id="serverTabContent-{{$key}}">
                    <div class="tab-pane fade show active" id="embed-{{$key}}" role="tabpanel">
                        @foreach($res['server_data'] as $server_key => $server)
                        <div class="link-item">
                            <div class="link-header">
                                <div class="d-flex align-items-center">
                                    <span class="server-name">{{$server['name']}}</span>
                                    <span class="episode-tag ms-2">Tập {{$server['name']}}</span>
                                </div>
                                <div class="d-flex align-items-center">
                                    <div class="server-label me-2">{{$res['server_name']}}</div>
                                    <div class="server-type">Embed</div>
                                </div>
                            </div>
                            <div class="link-content">
                                <div class="input-group">
                                    <input type="text" class="form-control" value="{{$server['link_embed']}}" readonly>
                                    <button class="btn btn-copy copy-btn" type="button"
                                        data-clipboard-text="{{$server['link_embed']}}">
                                        <i class="fas fa-copy"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    <div class="tab-pane fade" id="m3u8-{{$key}}" role="tabpanel">
                        @foreach($res['server_data'] as $server_key => $server)
                        <div class="link-item">
                            <div class="link-header">
                                <div class="d-flex align-items-center">
                                    <span class="server-name">{{$server['name']}}</span>
                                    <span class="episode-tag ms-2">Tập {{$server['name']}}</span>
                                </div>
                                <div class="d-flex align-items-center">
                                    <div class="server-label me-2">{{$res['server_name']}}</div>
                                    <div class="server-type">M3U8</div>
                                </div>
                            </div>
                            <div class="link-content">
                                <div class="input-group">
                                    <input type="text" class="form-control" value="{{$server['link_m3u8']}}" readonly>
                                    <button class="btn btn-copy copy-btn" type="button"
                                        data-clipboard-text="{{$server['link_m3u8']}}">
                                        <i class="fas fa-copy"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Đóng</button>
                <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Lưu</button>
            </div>
        </div>
    </div>
</div>
@endforeach

<script>
    document.addEventListener('DOMContentLoaded', function() {
    // Initialize tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
    
    // Clipboard functionality for copy buttons
    document.querySelectorAll('.copy-btn').forEach(button => {
        button.addEventListener('click', function() {
            const text = this.getAttribute('data-clipboard-text');
            navigator.clipboard.writeText(text).then(() => {
                // Add the copied class to button
                this.classList.add('copied');
                this.innerHTML = '<i class="fas fa-check"></i>';
                
                // Show toast notification
                const toast = document.createElement('div');
                toast.className = 'toast-notification';
                toast.innerHTML = '<i class="fas fa-check-circle"></i> Link đã được sao chép!';
                document.body.appendChild(toast);
                
                setTimeout(() => {
                    toast.classList.add('show');
                }, 100);
                
                setTimeout(() => {
                    toast.classList.remove('show');
                    setTimeout(() => {
                        document.body.removeChild(toast);
                    }, 300);
                }, 2000);
                
                // Reset the button after animation
                setTimeout(() => {
                    this.classList.remove('copied');
                    this.innerHTML = '<i class="fas fa-copy"></i>';
                }, 1500);
            });
        });
    });
    
    // Animate cards on load
    const cards = document.querySelectorAll('.movie-card');
    cards.forEach((card, index) => {
        setTimeout(() => {
            card.classList.add('show');
        }, 100 * index);
    });
    
    // Tìm kiếm và phân trang
    const searchInput = document.getElementById('episode-search');
    const movieCards = document.querySelectorAll('.movie-card');
    const cardsPerPage = 12;
    let currentPage = 1;
    let filteredCards = Array.from(movieCards);
    
    // Hàm cập nhật hiển thị thẻ phim
    function updateDisplay() {
        // Tính toán vị trí bắt đầu và kết thúc cho trang hiện tại
        const startIndex = (currentPage - 1) * cardsPerPage;
        const endIndex = startIndex + cardsPerPage;
        
        // Ẩn tất cả các card
        movieCards.forEach(card => {
            card.style.display = 'none';
        });
        
        // Hiển thị chỉ những card nằm trong phạm vi của trang hiện tại
        filteredCards.slice(startIndex, endIndex).forEach(card => {
            card.style.display = 'block';
        });
        
        // Cập nhật phân trang
        updatePagination();
    }
    
    // Hàm cập nhật phân trang
    function updatePagination() {
        const paginationContainer = document.getElementById('pagination');
        const totalPages = Math.ceil(filteredCards.length / cardsPerPage);
        
        // Xóa các nút phân trang cũ
        paginationContainer.innerHTML = '';
        
        // Nút Previous
        const prevLi = document.createElement('li');
        prevLi.className = `page-item ${currentPage === 1 ? 'disabled' : ''}`;
        const prevLink = document.createElement('a');
        prevLink.className = 'page-link';
        prevLink.href = '#';
        prevLink.innerHTML = '&laquo;';
        prevLink.addEventListener('click', function(e) {
            e.preventDefault();
            if (currentPage > 1) {
                currentPage--;
                updateDisplay();
            }
        });
        prevLi.appendChild(prevLink);
        paginationContainer.appendChild(prevLi);
        
        // Các nút số trang
        for (let i = 1; i <= totalPages; i++) {
            const pageLi = document.createElement('li');
            pageLi.className = `page-item ${i === currentPage ? 'active' : ''}`;
            const pageLink = document.createElement('a');
            pageLink.className = 'page-link';
            pageLink.href = '#';
            pageLink.textContent = i;
            pageLink.addEventListener('click', function(e) {
                e.preventDefault();
                currentPage = i;
                updateDisplay();
            });
            pageLi.appendChild(pageLink);
            paginationContainer.appendChild(pageLi);
        }
        
        // Nút Next
        const nextLi = document.createElement('li');
        nextLi.className = `page-item ${currentPage === totalPages ? 'disabled' : ''}`;
        const nextLink = document.createElement('a');
        nextLink.className = 'page-link';
        nextLink.href = '#';
        nextLink.innerHTML = '&raquo;';
        nextLink.addEventListener('click', function(e) {
            e.preventDefault();
            if (currentPage < totalPages) {
                currentPage++;
                updateDisplay();
            }
        });
        nextLi.appendChild(nextLink);
        paginationContainer.appendChild(nextLi);
    }
    
    // Xử lý tìm kiếm
    searchInput.addEventListener('input', function() {
        const searchTerm = this.value.toLowerCase();
        
        filteredCards = Array.from(movieCards).filter(card => {
            const movieName = card.getAttribute('data-name').toLowerCase();
            const originName = card.getAttribute('data-origin-name').toLowerCase();
            const movieId = card.getAttribute('data-id').toLowerCase();
            
            return movieName.includes(searchTerm) || 
                   originName.includes(searchTerm) || 
                   movieId.includes(searchTerm);
        });
        
        currentPage = 1; // Reset về trang 1 khi tìm kiếm
        updateDisplay();
    });
    
    // Nút làm mới
    document.getElementById('refresh-btn').addEventListener('click', function() {
        searchInput.value = '';
        filteredCards = Array.from(movieCards);
        currentPage = 1;
        updateDisplay();
    });
    
    // Khởi tạo hiển thị ban đầu
    updateDisplay();
});
</script>

<style>
    /* Google Fonts */
    @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');

    /* Base Styles */
    body {
        font-family: 'Poppins', sans-serif;
        background-color: #f8f9ff;
        color: #334155;
    }

    .dashboard-container {
        max-width: 1400px;
        margin: 0 auto;
        padding: 0 20px;
    }

    /* Text Gradient */
    .text-gradient {
        background: linear-gradient(120deg, #2563eb, #7c3aed);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        font-weight: 600;
    }

    /* Glass Cards */
    .glass-card {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        border-radius: 16px;
        padding: 24px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
        border: 1px solid rgba(255, 255, 255, 0.2);
        transition: all 0.3s ease;
    }

    .glass-card:hover {
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.12);
    }

    /* Page Header */
    .page-header {
        margin-bottom: 30px;
    }

    .page-title {
        font-size: 28px;
        margin-bottom: 5px;
        font-weight: 700;
    }

    /* Filter Section */
    .filter-section {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 15px;
        margin-bottom: 20px;
    }

    .search-box {
        position: relative;
        width: 300px;
        max-width: 100%;
    }

    .search-box i {
        position: absolute;
        left: 15px;
        top: 50%;
        transform: translateY(-50%);
        color: #94a3b8;
    }

    .search-box input {
        padding-left: 40px;
        border-radius: 25px;
        border: 1px solid #e2e8f0;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        width: 100%;
        height: 42px;
        transition: all 0.3s ease;
    }

    .search-box input:focus {
        box-shadow: 0 4px 15px rgba(37, 99, 235, 0.1);
        border-color: #3b82f6;
    }

    .filter-buttons {
        display: flex;
        gap: 10px;
    }

    /* Custom Table */
    .custom-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0 8px;
        margin-bottom: 0;
    }

    .custom-table thead th {
        border: none;
        padding: 12px 16px;
        font-weight: 600;
        font-size: 13px;
        color: #64748b;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .custom-table .th-content {
        display: flex;
        align-items: center;
    }

    .custom-table tbody tr {
        background: white;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.03);
        border-radius: 10px;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .custom-table tbody tr:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.08);
    }

    .custom-table tbody td {
        padding: 16px;
        border: none;
        vertical-align: middle;
        border-top: 1px solid rgba(0, 0, 0, 0.02);
        border-bottom: 1px solid rgba(0, 0, 0, 0.02);
    }

    .custom-table tbody td:first-child {
        border-radius: 10px 0 0 10px;
        border-left: 1px solid rgba(0, 0, 0, 0.02);
    }

    .custom-table tbody td:last-child {
        border-radius: 0 10px 10px 0;
        border-right: 1px solid rgba(0, 0, 0, 0.02);
    }

    /* ID Tag */
    .id-tag {
        font-family: 'Courier New', monospace;
        font-size: 11px;
        background: #f1f5f9;
        padding: 4px 8px;
        border-radius: 4px;
        color: #475569;
    }

    /* Movie Card */
    .movie-card {
        background: white;
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.06);
        transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        opacity: 0;
        transform: translateY(20px);
        border: 1px solid rgba(0, 0, 0, 0.05);
        height: 100%;
    }

    .movie-card.show {
        opacity: 1;
        transform: translateY(0);
    }

    .movie-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 20px 30px rgba(0, 0, 0, 0.12);
    }

    .movie-card-header {
        padding: 16px 20px;
        background: linear-gradient(120deg, #4338ca, #3b82f6);
        color: white;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .movie-card-body {
        padding: 20px;
    }

    .episode-number {
        font-weight: 600;
        font-size: 18px;
    }

    .movie-name {
        font-size: 18px;
        font-weight: 600;
        margin-bottom: 4px;
        color: #1e293b;
    }

    .movie-original-name {
        color: #64748b;
        font-size: 14px;
        margin-bottom: 16px;
    }

    .movie-info {
        background: #f8fafc;
        border-radius: 8px;
        padding: 12px;
        margin-bottom: 16px;
    }

    .info-item {
        display: flex;
        margin-bottom: 8px;
        font-size: 13px;
    }

    .info-item:last-child {
        margin-bottom: 0;
    }

    .info-label {
        color: #64748b;
        font-weight: 500;
        width: 40px;
    }

    .info-value {
        flex: 1;
        font-weight: 500;
    }

    .id-value {
        font-family: 'Courier New', monospace;
        color: #475569;
    }

    .action-row {
        display: flex;
        margin-top: 16px;
    }

    /* Episode Badge */
    .episode-badge {
        display: inline-flex;
        align-items: center;
        padding: 5px 10px;
        background: linear-gradient(120deg, #3b82f6, #8b5cf6);
        border-radius: 20px;
        color: white;
        font-weight: 500;
        font-size: 13px;
    }

    .server-badge {
        display: inline-flex;
        align-items: center;
        padding: 5px 10px;
        background: linear-gradient(120deg, #e11d48, #ff6d00);
        border-radius: 20px;
        color: white;
        font-weight: 500;
        font-size: 13px;
    }

    .server-badge-sm {
        display: inline-flex;
        align-items: center;
        padding: 3px 8px;
        background: linear-gradient(120deg, #e11d48, #ff6d00);
        border-radius: 12px;
        color: white;
        font-weight: 500;
        font-size: 12px;
    }

    .server-label {
        font-size: 12px;
        background: #e2e8f0;
        padding: 3px 8px;
        border-radius: 4px;
        color: #475569;
        font-weight: 500;
    }

    .episode-tag {
        font-size: 11px;
        background: #dbeafe;
        padding: 2px 6px;
        border-radius: 4px;
        color: #3b82f6;
        font-weight: 500;
    }

    .server-info-header {
        background: #f8fafc;
        border-radius: 8px;
        padding: 12px;
    }

    .server-detail {
        display: flex;
        align-items: center;
    }

    .server-value {
        font-weight: 600;
        color: #334155;
    }

    .episode-count-badge {
        background: #ecfdf5;
        color: #10b981;
        padding: 4px 10px;
        border-radius: 20px;
        font-size: 13px;
        font-weight: 500;
    }

    .episode-badge .current {
        font-weight: 700;
    }

    .episode-badge .divider {
        margin: 0 3px;
        opacity: 0.7;
    }

    .episode-badge-card {
        display: inline-flex;
        align-items: center;
        padding: 4px 10px;
        background: rgba(255, 255, 255, 0.2);
        border-radius: 20px;
        color: white;
        font-weight: 500;
        font-size: 13px;
    }

    /* Badge Circle */
    .badge-circle {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 32px;
        height: 32px;
        background: linear-gradient(120deg, #4338ca, #3b82f6);
        color: white;
        border-radius: 50%;
        font-weight: 600;
        font-size: 14px;
    }

    /* Server Button */
    .server-btn {
        padding: 6px 14px;
        border-radius: 20px;
        transition: all 0.3s ease;
        font-weight: 500;
        font-size: 13px;
    }

    .btn-rounded {
        border-radius: 20px;
    }

    .btn-icon {
        width: 38px;
        height: 38px;
        border-radius: 50%;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        margin: 0 3px;
        padding: 0;
    }

    .action-buttons {
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .action-buttons form {
        margin: 0;
    }

    /* Button Styles */
    .btn-primary {
        background: linear-gradient(120deg, #2563eb, #4f46e5);
        border: none;
        box-shadow: 0 4px 10px rgba(37, 99, 235, 0.2);
    }

    .btn-primary:hover {
        background: linear-gradient(120deg, #1d4ed8, #4338ca);
        box-shadow: 0 6px 15px rgba(37, 99, 235, 0.3);
        transform: translateY(-2px);
    }

    .btn-success {
        background: linear-gradient(120deg, #16a34a, #059669);
        border: none;
        box-shadow: 0 4px 10px rgba(22, 163, 74, 0.2);
    }

    .btn-success:hover {
        background: linear-gradient(120deg, #15803d, #047857);
        box-shadow: 0 6px 15px rgba(22, 163, 74, 0.3);
        transform: translateY(-2px);
    }

    .btn-danger {
        background: linear-gradient(120deg, #dc2626, #b91c1c);
        border: none;
        box-shadow: 0 4px 10px rgba(220, 38, 38, 0.2);
    }

    .btn-danger:hover {
        background: linear-gradient(120deg, #b91c1c, #991b1b);
        box-shadow: 0 6px 15px rgba(220, 38, 38, 0.3);
        transform: translateY(-2px);
    }

    .btn-block {
        display: block;
        width: 100%;
    }

    .server-modal-btn {
        font-weight: 500;
        font-size: 14px;
        padding: 10px;
    }

    /* Custom Modal */
    .custom-modal .modal-content {
        border-radius: 20px;
        border: none;
        box-shadow: 0 25px 50px rgba(0, 0, 0, 0.1);
        overflow: hidden;
    }

    .custom-modal .modal-header {
        border-bottom: 1px solid #f1f5f9;
        padding: 20px 24px;
    }

    .modal-title-wrapper {
        display: flex;
        flex-direction: column;
    }

    .modal-subtitle {
        font-size: 12px;
        color: #64748b;
        margin-top: 2px;
    }

    .custom-modal .modal-body {
        padding: 24px;
    }

    .custom-modal .modal-footer {
        border-top: 1px solid #f1f5f9;
        padding: 16px 24px;
    }

    /* Custom Tabs */
    .custom-tabs {
        border-bottom: 1px solid #e2e8f0;
        padding-bottom: 1px;
        margin-bottom: 20px;
    }

    .custom-tabs .nav-link {
        border-radius: 8px;
        padding: 8px 16px;
        font-weight: 500;
        color: #64748b;
        transition: all 0.2s ease;
        margin-right: 8px;
    }

    .custom-tabs .nav-link:hover {
        background-color: #f1f5f9;
        color: #334155;
    }

    .custom-tabs .nav-link.active {
        background: linear-gradient(120deg, #2563eb, #4f46e5);
        color: white;
        box-shadow: 0 4px 10px rgba(37, 99, 235, 0.2);
    }

    /* Link Items */
    .link-item {
        background: #f8fafc;
        border-radius: 12px;
        margin-bottom: 16px;
        overflow: hidden;
        border: 1px solid #e2e8f0;
    }

    .link-header {
        background: #f1f5f9;
        padding: 12px 16px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .server-name {
        font-weight: 600;
        color: #334155;
    }

    .server-type {
        font-size: 12px;
        background: #e2e8f0;
        padding: 3px 8px;
        border-radius: 4px;
        color: #475569;
        font-weight: 500;
    }

    .link-content {
        padding: 16px;
    }

    .btn-copy {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        color: #64748b;
        transition: all 0.2s ease;
    }

    .btn-copy:hover {
        background: #f1f5f9;
        color: #334155;
    }

    .btn-copy.copied {
        background: #10b981;
        color: white;
        border-color: #10b981;
    }

    /* Toast Notification */
    .toast-notification {
        position: fixed;
        bottom: 30px;
        right: 30px;
        background: #10b981;
        color: white;
        padding: 12px 20px;
        border-radius: 8px;
        box-shadow: 0 10px 15px rgba(0, 0, 0, 0.1);
        display: flex;
        align-items: center;
        z-index: 1060;
        transform: translateY(30px);
        opacity: 0;
        transition: all 0.3s ease;
    }

    .toast-notification.show {
        transform: translateY(0);
        opacity: 1;
    }

    .toast-notification i {
        margin-right: 8px;
        font-size: 18px;
    }

    /* Animations */
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

    /* Pagination Styles */
    .pagination-container {
        margin-top: 30px;
        text-align: center;
    }

    .pagination {
        display: inline-flex;
        padding: 0;
        margin: 0;
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
    }

    .pagination .page-item .page-link {
        border: none;
        margin: 0;
        color: #475569;
        font-weight: 500;
        padding: 10px 16px;
        background-color: white;
        transition: all 0.3s ease;
    }

    .pagination .page-item.active .page-link {
        background: linear-gradient(120deg, #2563eb, #4f46e5);
        color: white;
        font-weight: 600;
    }

    .pagination .page-item .page-link:hover:not(.disabled .page-link) {
        background-color: #f1f5f9;
        color: #1e293b;
        z-index: 3;
    }

    .pagination .page-item.disabled .page-link {
        color: #cbd5e1;
        pointer-events: none;
    }

    /* Responsive Adjustments */
    @media (max-width: 768px) {
        .glass-card {
            padding: 16px;
        }

        .page-title {
            font-size: 24px;
        }

        .search-box {
            width: 100%;
        }

        .filter-section {
            flex-direction: column;
            align-items: stretch;
        }

        .custom-tabs .nav-link {
            padding: 6px 12px;
            font-size: 14px;
        }

        .modal-dialog {
            margin: 0.5rem;
        }

        .pagination .page-item .page-link {
            padding: 8px 12px;
            font-size: 14px;
        }
    }
</style>
@endsection