document.addEventListener('DOMContentLoaded', function () {
    // Chức năng phân trang AJAX
    setupAjaxPagination();

    // Chức năng tìm kiếm với AJAX
    setupAjaxSearch();
});

function setupAjaxPagination() {
    // Bắt sự kiện click trên các link phân trang
    document.addEventListener('click', function (e) {
        // Kiểm tra xem click có phải vào link phân trang không
        if (e.target.closest('.pagination a')) {
            e.preventDefault();

            const paginationLink = e.target.closest('.pagination a');
            const url = paginationLink.getAttribute('href');

            // Tải nội dung mới bằng AJAX
            loadPageContent(url);
        }
    });
}

function setupAjaxSearch() {
    const searchInput = document.getElementById('movie-search');
    if (searchInput) {
        let timer;

        searchInput.addEventListener('input', function () {
            clearTimeout(timer);

            timer = setTimeout(function () {
                const searchTerm = searchInput.value.trim();

                // Lấy URL hiện tại và cập nhật tham số tìm kiếm
                const url = new URL(window.location.href);

                if (searchTerm) {
                    url.searchParams.set('search', searchTerm);
                } else {
                    url.searchParams.delete('search');
                }

                // Reset về trang 1 khi tìm kiếm
                url.searchParams.set('page', 1);

                // Tải nội dung mới bằng AJAX
                loadPageContent(url.toString());

                // Cập nhật URL trình duyệt nhưng không tải lại trang
                window.history.pushState({}, '', url.toString());
            }, 500); // Debounce 500ms
        });

        // Đặt giá trị input tìm kiếm từ URL nếu có
        const urlParams = new URLSearchParams(window.location.search);
        if (urlParams.has('search')) {
            searchInput.value = urlParams.get('search');
        }
    }
}

function loadPageContent(url) {
    // Thêm tham số AJAX để controller biết đây là request AJAX
    const ajaxUrl = new URL(url);
    ajaxUrl.searchParams.set('ajax', 'true');

    // Hiển thị chỉ báo đang tải
    const gridContainer = document.getElementById('movie-grid-container');
    gridContainer.innerHTML = '<div class="loading-spinner"><i class="fas fa-spinner fa-spin"></i> Đang tải...</div>';

    // Gửi yêu cầu AJAX
    fetch(ajaxUrl.toString())
        .then(response => response.text())
        .then(html => {
            // Tạo một DOM ảo để trích xuất dữ liệu
            const parser = new DOMParser();
            const doc = parser.parseFromString(html, 'text/html');

            // Cập nhật grid phim
            const newGrid = doc.getElementById('movie-grid-container').innerHTML;
            gridContainer.innerHTML = newGrid;

            // Cập nhật phân trang
            const paginationContainer = document.querySelector('.pagination-container');
            const newPagination = doc.querySelector('.pagination-container').innerHTML;
            paginationContainer.innerHTML = newPagination;

            // Cập nhật URL trình duyệt nhưng không tải lại trang
            window.history.pushState({}, '', ajaxUrl.toString().replace('&ajax=true', '').replace('?ajax=true&', '?').replace('?ajax=true', ''));
        })
        .catch(error => {
            console.error('Lỗi khi tải trang:', error);
            gridContainer.innerHTML = '<div class="error-message">Có lỗi xảy ra khi tải dữ liệu. Vui lòng thử lại.</div>';
        });
}

// Xử lý khi người dùng nhấn nút Back/Forward trình duyệt
window.addEventListener('popstate', function (e) {
    // Tải nội dung của URL mới
    loadPageContent(window.location.href);
});
