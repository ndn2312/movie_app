    document.addEventListener('DOMContentLoaded', function () {
        // Xử lý phân trang
        setupPagination();

        // Xử lý tìm kiếm và lọc
        setupSearchAndFilter();

        // Xử lý xác nhận xóa
        setupDeleteConfirmation();

        // Xử lý tất cả các sự kiện AJAX (giữ nguyên code xử lý AJAX hiện tại với selector mới)
        setupAjaxHandlers();
    });

    // Các hàm xử lý được định nghĩa ở đây
    function setupPagination() {
        const moviesPerPage = 6;
        const movieCards = document.querySelectorAll('.movie-card');
        const totalPages = Math.ceil(movieCards.length / moviesPerPage);

        // Tạo phân trang
        const paginationContainer = document.querySelector('.pagination-container');

        // Kiểm tra xem paginationContainer có tồn tại không
        if (!paginationContainer) return;

        // Tạo phân trang ban đầu với trang 1 là active
        createPaginationControls(1, totalPages);

        // Hiển thị trang đầu tiên
        showPage(1);

        // Đăng ký sự kiện cho phân trang - được gọi trong createPaginationControls()
    }

    // Hàm tạo điều khiển phân trang với currentPage là trang hiện tại
    function createPaginationControls(currentPage, totalPages) {
        const paginationContainer = document.querySelector('.pagination-container');

        // Xác định số lượng trang hiển thị ở mỗi bên của trang hiện tại
        const visiblePages = 2;

        let paginationHTML = '<div class="pagination">';

        // Nút Previous
        paginationHTML += `<button class="page-nav prev-page" ${currentPage === 1 ? 'disabled' : ''}><i class="fas fa-angle-left"></i></button>`;

        // Nút trang đầu tiên
        if (currentPage > visiblePages + 1) {
            paginationHTML += `<button class="page-number" data-page="1">1</button>`;

            // Dấu chấm lửng nếu cần
            if (currentPage > visiblePages + 2) {
                paginationHTML += `<span class="page-ellipsis">...</span>`;
            }
        }

        // Các nút trang ở giữa
        for (let i = Math.max(1, currentPage - visiblePages); i <= Math.min(totalPages, currentPage + visiblePages); i++) {
            paginationHTML += `<button class="page-number ${i === currentPage ? 'active' : ''}" data-page="${i}">${i}</button>`;
        }

        // Dấu chấm lửng và nút trang cuối cùng
        if (currentPage < totalPages - visiblePages) {
            // Dấu chấm lửng nếu cần
            if (currentPage < totalPages - visiblePages - 1) {
                paginationHTML += `<span class="page-ellipsis">...</span>`;
            }

            paginationHTML += `<button class="page-number" data-page="${totalPages}">${totalPages}</button>`;
        }

        // Nút Next
        paginationHTML += `<button class="page-nav next-page" ${currentPage === totalPages ? 'disabled' : ''}><i class="fas fa-angle-right"></i></button>`;

        paginationHTML += '</div>';

        paginationContainer.innerHTML = paginationHTML;

        // Đăng ký sự kiện click cho các nút phân trang
        document.querySelectorAll('.page-number').forEach(button => {
            button.addEventListener('click', function () {
                const page = parseInt(this.dataset.page);
                showPage(page);
                createPaginationControls(page, totalPages); // Tạo lại điều khiển phân trang
            });
        });

        // Đăng ký sự kiện cho nút Prev
        document.querySelector('.prev-page').addEventListener('click', function () {
            if (currentPage > 1) {
                showPage(currentPage - 1);
                createPaginationControls(currentPage - 1, totalPages);
            }
        });

        // Đăng ký sự kiện cho nút Next
        document.querySelector('.next-page').addEventListener('click', function () {
            if (currentPage < totalPages) {
                showPage(currentPage + 1);
                createPaginationControls(currentPage + 1, totalPages);
            }
        });
    }

    function showPage(page) {
        const moviesPerPage = 6;
        const movieCards = document.querySelectorAll('.movie-card');
        const startIndex = (page - 1) * moviesPerPage;
        const endIndex = startIndex + moviesPerPage - 1;

        // Ẩn tất cả các card
        movieCards.forEach((card, index) => {
            if (index >= startIndex && index <= endIndex) {
                card.style.display = 'flex';
            } else {
                card.style.display = 'none';
            }
        });
    }

    function setupSearchAndFilter() {
        const searchInput = document.getElementById('movie-search');
        const filterButtons = document.querySelectorAll('.filter-btn');

        // Tìm kiếm
        searchInput.addEventListener('input', function () {
            const searchTerm = this.value.toLowerCase();
            filterMovies(searchTerm);
        });

        // Lọc theo nút
        filterButtons.forEach(button => {
            button.addEventListener('click', function () {
                // Xóa lớp active khỏi tất cả các nút
                filterButtons.forEach(btn => btn.classList.remove('active'));

                // Thêm lớp active vào nút được nhấp
                this.classList.add('active');

                const filterType = this.getAttribute('data-filter');
                filterMoviesByType(filterType);
            });
        });
    }

    function filterMovies(searchTerm) {
        const movieCards = document.querySelectorAll('.movie-card');

        movieCards.forEach(card => {
            const title = card.querySelector('.movie-title').textContent.toLowerCase();
            const originalTitle = card.querySelector('.movie-original-title') ?
                card.querySelector('.movie-original-title').textContent.toLowerCase() : '';

            if (title.includes(searchTerm) || originalTitle.includes(searchTerm)) {
                card.classList.remove('hidden');
            } else {
                card.classList.add('hidden');
            }
        });
    }

    function filterMoviesByType(filterType) {
        const movieCards = document.querySelectorAll('.movie-card');

        movieCards.forEach(card => {
            // Reset tìm kiếm
            card.classList.remove('hidden');

            if (filterType === 'all') return;

            if (filterType === 'hot') {
                const isHot = card.querySelector('.status-badge.hot');
                if (!isHot) card.classList.add('hidden');
            } else if (filterType === 'series') {
                const isSeries = card.hasAttribute('data-series') ? card.getAttribute('data-series') === 'true' : false;
                if (!isSeries) card.classList.add('hidden');
            } else if (filterType === 'single') {
                const isSeries = card.hasAttribute('data-series') ? card.getAttribute('data-series') === 'true' : false;
                if (isSeries) card.classList.add('hidden');
            } else if (filterType === 'newest') {
                const isNew = card.hasAttribute('data-new') ? card.getAttribute('data-new') === 'true' : false;
                if (!isNew) card.classList.add('hidden');
            }
        });
    }

    function updatePaginationAfterFilter() {
        // Đếm số lượng card đang hiển thị
        const visibleCards = document.querySelectorAll('.movie-card:not(.hidden)');
        const moviesPerPage = 6;
        const totalPages = Math.ceil(visibleCards.length / moviesPerPage);

        // Tạo lại điều khiển phân trang với trang 1 là active
        createPaginationControls(1, totalPages);

        // Hiển thị trang đầu tiên của kết quả lọc
        showFilteredPage(1);
    }

    function showFilteredPage(page) {
        const moviesPerPage = 6;
        const visibleCards = document.querySelectorAll('.movie-card:not(.hidden)');
        const startIndex = (page - 1) * moviesPerPage;
        const endIndex = startIndex + moviesPerPage - 1;

        // Ẩn/hiện card theo phân trang
        visibleCards.forEach((card, index) => {
            if (index >= startIndex && index <= endIndex) {
                card.style.display = 'flex';
            } else {
                card.style.display = 'none';
            }
        });
    }

    function setupDeleteConfirmation() {
        document.querySelectorAll('.delete-form').forEach(form => {
            form.addEventListener('submit', function (event) {
                event.preventDefault();
                const movieTitle = this.dataset.movieTitle;

                Swal.fire({
                    title: 'Xác nhận xóa?',
                    html: `Bạn có chắc chắn muốn xóa phim <strong>${movieTitle}</strong>?<br>
                           Hành động này không thể hoàn tác!`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Xóa',
                    cancelButtonText: 'Hủy',
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                }).then((result) => {
                    if (result.isConfirmed) {
                        this.submit();
                    }
                });
            });
        });
    }

    function setupAjaxHandlers() {
        // Các xử lý AJAX sẽ được giữ nguyên, chỉ cần cập nhật các selector nếu cần
    }
    // Đặt trong file riêng hoặc tích hợp vào trang

    // Cấu hình CSRF Token cho tất cả các request AJAX
    // $.ajaxSetup({
    //     headers: {
    //         'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    //     }
    // });

    // Khởi tạo tất cả chức năng khi trang được tải
    document.addEventListener('DOMContentLoaded', function () {
        // Xử lý tải ảnh phim
        setupImageUpload();

        // Xử lý các dropdown (chọn danh mục, quốc gia, v.v.)
        setupDropdownHandlers();

        // Xử lý xác nhận xóa phim
        setupDeleteConfirmation();

        // Xử lý hiệu ứng hình ảnh
        setupImageEffects();

        // Khởi tạo tooltips
        initializeTooltips();
    });

    // Hàm xử lý tải lên hình ảnh phim
    function setupImageUpload() {
        $(document).on('change', '.file_image', function () {
            const movieId = $(this).data('movie_id');
            const movieTitle = $(this).data('movie_title');
            const files = $("#file-" + movieId)[0].files;

            if (files.length > 0) {
                // Tạo FormData cho upload
                const formData = new FormData();
                formData.append('file', files[0]);
                formData.append('movie_id', movieId);

                // Hiển thị trạng thái đang tải
                const movieCard = $(this).closest('.movie-card');
                const loadingOverlay = $('<div class="loading-overlay"><div class="spinner"></div></div>');
                movieCard.append(loadingOverlay);

                // Gửi request AJAX
                $.ajax({
                    url: "/update-image-movie-ajax",
                    method: "POST",
                    data: formData,
                    contentType: false,
                    cache: false,
                    processData: false,
                    success: function (response) {
                        // Xóa overlay loading
                        loadingOverlay.remove();

                        // Hiển thị thông báo thành công
                        showNotification('success', `Hình ảnh phim "${movieTitle}" đã được cập nhật thành công!`);

                        // Cập nhật hình ảnh trên card (nếu cần)
                        setTimeout(() => {
                            location.reload(); // Hoặc cập nhật ảnh không cần reload
                        }, 1500);
                    },
                    error: function (xhr, status, error) {
                        // Xóa overlay loading
                        loadingOverlay.remove();

                        // Hiển thị thông báo lỗi
                        showNotification('error', `Lỗi khi cập nhật hình ảnh: ${error}`);
                    }
                });
            }
        });
    }

    // Hàm xử lý các dropdown (danh mục, quốc gia, vv)
    function setupDropdownHandlers() {
        // Xử lý cập nhật danh mục phim
        $(document).on('change', '.category_choose', function () {
            const categoryId = $(this).val();
            const movieId = $(this).attr('id');
            const movieTitle = $(this).attr('title');
            const categoryText = $(this).find(':selected').text();

            // updateMovieProperty('category-choose', {
            //     category_id: categoryId,
            //     movie_id: movieId
            // }, `Phim "${movieTitle}" đã được cập nhật thành danh mục ${categoryText} thành công!`);
        });

        // Xử lý cập nhật quốc gia phim
        $(document).on('change', '.country_choose', function () {
            const countryId = $(this).val();
            const movieId = $(this).attr('id');
            const movieTitle = $(this).attr('title');
            const countryText = $(this).find(':selected').text();

            // updateMovieProperty('country-choose', {
            //     country_id: countryId,
            //     movie_id: movieId
            // }, `Phim "${movieTitle}" đã được cập nhật thành quốc gia ${countryText} thành công!`);
        });

        // Xử lý cập nhật phim hot
        $(document).on('change', '.phimhot_choose', function () {
            const phimhotVal = $(this).val();
            const movieId = $(this).attr('id');
            const movieTitle = $(this).attr('title');
            const phimhotText = (phimhotVal == 1) ? 'Hot' : 'Không Hot';

            // updateMovieProperty('phimhot-choose', {
            //     phimhot_val: phimhotVal,
            //     movie_id: movieId
            // }, `Phim "${movieTitle}" đã được cập nhật thành trạng thái ${phimhotText} thành công!`);

            // Cập nhật hiển thị trạng thái hot trên card
            const movieCard = $(this).closest('.movie-card');
            const hotBadge = movieCard.find('.status-badge:nth-child(2)');

            if (phimhotVal == 1) {
                hotBadge.removeClass('regular').addClass('hot');
                hotBadge.html('<i class="fas fa-fire"></i> <span>Hot</span>');
            } else {
                hotBadge.removeClass('hot').addClass('regular');
                hotBadge.html('<i class="fas fa-snowflake"></i> <span>Thường</span>');
            }
        });

        // Xử lý cập nhật phụ đề/thuyết minh
        $(document).on('change', '.phude_choose', function () {
            const phudeVal = $(this).val();
            const movieId = $(this).attr('id');
            const movieTitle = $(this).attr('title');
            const phudeText = (phudeVal == 1) ? 'Thuyết minh' : 'Phụ đề';

            // updateMovieProperty('phude-choose', {
            //     phude_val: phudeVal,
            //     movie_id: movieId
            // }, `Phim "${movieTitle}" đã được cập nhật thành phiên bản ${phudeText} thành công!`);
        });

        // Xử lý cập nhật trạng thái hiển thị
        $(document).on('change', '.status_choose', function () {
            const statusVal = $(this).val();
            const movieId = $(this).attr('id');
            const movieTitle = $(this).attr('title');
            const statusText = (statusVal == 1) ? 'Hiển thị' : 'Ẩn';

            // updateMovieProperty('status-choose', {
            //     status_val: statusVal,
            //     movie_id: movieId
            // }, `Phim "${movieTitle}" đã được cập nhật thành trạng thái ${statusText} thành công!`);

            // Cập nhật hiển thị trạng thái trên card
            const movieCard = $(this).closest('.movie-card');
            const statusBadge = movieCard.find('.status-badge:first-child');

            if (statusVal == 1) {
                statusBadge.removeClass('inactive').addClass('active');
                statusBadge.html('<i class="fas fa-eye"></i> <span>Hiển thị</span>');
            } else {
                statusBadge.removeClass('active').addClass('inactive');
                statusBadge.html('<i class="fas fa-eye-slash"></i> <span>Ẩn</span>');
            }
        });

        // Xử lý cập nhật độ phân giải
        $(document).on('change', '.resolution_choose', function () {
            const resolutionVal = $(this).val();
            const movieId = $(this).attr('id');
            const movieTitle = $(this).attr('title');

            let resolutionText;
            switch (parseInt(resolutionVal)) {
                case 0:
                    resolutionText = 'HD';
                    break;
                case 1:
                    resolutionText = 'SD';
                    break;
                case 2:
                    resolutionText = 'HDCam';
                    break;
                case 3:
                    resolutionText = 'Cam';
                    break;
                case 4:
                    resolutionText = 'FullHD';
                    break;
                default:
                    resolutionText = 'Trailer';
                    break;
            }

            // updateMovieProperty('resolution-choose', {
            //     resolution_val: resolutionVal,
            //     movie_id: movieId
            // }, `Phim "${movieTitle}" đã được cập nhật thành độ phân giải ${resolutionText} thành công!`);
        });

        // Xử lý cập nhật loại phim (phim lẻ/bộ)
        $(document).on('change', '.thuocphim_choose', function () {
            const thuocphimVal = $(this).val();
            const movieId = $(this).attr('id');
            const movieTitle = $(this).attr('title');
            const thuocphimText = (thuocphimVal === 'phimle') ? 'Phim lẻ' : 'Phim bộ';

            updateMovieProperty('thuocphim-choose', {
                thuocphim_val: thuocphimVal,
                movie_id: movieId
            }, `Phim "${movieTitle}" đã được cập nhật thành ${thuocphimText} thành công!`);
        });

        // Xử lý cập nhật năm phim
        $(document).on('change', '.select-year', function () {
            const year = $(this).val();
            const movieId = $(this).attr('id');
            const movieTitle = $(this).attr('title');

            // updateMovieProperty('/update-year-phim', {
            //     year: year,
            //     id_phim: movieId,
            //     ten_phim: movieTitle
            // }, `Phim "${movieTitle}" đã được cập nhật thành năm ${year} thành công!`);
        });

        // Xử lý cập nhật topview
        $(document).on('change', '.select-topview', function () {
            const topview = $(this).val();
            const movieId = $(this).attr('id');
            const movieTitle = $(this).attr('title');

            let textTopview;
            switch (parseInt(topview)) {
                case 0:
                    textTopview = 'Ngày';
                    break;
                case 1:
                    textTopview = 'Tuần';
                    break;
                case 2:
                    textTopview = 'Tháng';
                    break;
            }

            // updateMovieProperty('/update-topview-phim', {
            //     topview: topview,
            //     id_phim: movieId,
            //     ten_phim: movieTitle
            // }, `Phim "${movieTitle}" đã được cập nhật theo topview ${textTopview} thành công!`);
        });

        // Xử lý cập nhật season phim
        $(document).on('change', '.select-season', function () {
            var season = $(this).val();
            var id_phim = $(this).attr('id');
            var ten_phim = $(this).attr('title');

            $.ajax({
                url: "/update-season-phim",
                method: "POST",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    season: season,
                    id_phim: id_phim,
                    ten_phim: ten_phim
                },
                success: function (data) {
                    const message = 'Phim "<span class="highlight-name">' + ten_phim +
                        '</span>" đã được <span class="highlight">CẬP NHẬT</span> thành season ' +
                        '<span class="highlight-year">' + season + '</span> thành công!';
                    showCustomModal(message);
                },
                error: function (xhr) {
                    console.error("Lỗi khi cập nhật season:", xhr.responseText);
                    showErrorAlert("Có lỗi xảy ra khi cập nhật season phim!");
                }
            });
        });
    }

    // Hàm chung để cập nhật thuộc tính phim
    function updateMovieProperty(endpoint, data, successMessage) {
        $.ajax({
            url: endpoint,
            method: "GET",
            data: data,
            success: function (response) {
                showNotification('success', successMessage);
            },
            error: function (xhr) {
                showNotification('error', `Lỗi khi cập nhật: ${xhr.responseText}`);
            }
        });
    }

    // Hàm xử lý xác nhận xóa phim
    function setupDeleteConfirmation() {
        $('.delete-form').on('submit', function (e) {
            e.preventDefault();

            const form = $(this);
            const movieTitle = form.data('movie-title');

            Swal.fire({
                title: 'Xác nhận xóa?',
                html: `Bạn có chắc muốn xóa phim <strong>${movieTitle}</strong>?<br>Tất cả thông tin liên quan đến phim này cũng sẽ bị xóa.`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Xóa',
                cancelButtonText: 'Hủy',
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
            }).then((result) => {
                if (result.isConfirmed) {
                    form.off('submit').submit();
                }
            });
        });
    }

    // Hàm xử lý hiệu ứng hình ảnh
    function setupImageEffects() {
        // Hiệu ứng hover cho hình ảnh
        $('.movie-image').hover(
            function () {
                $(this).find('img').css('transform', 'scale(1.05)');
            },
            function () {
                $(this).find('img').css('transform', 'scale(1)');
            }
        );
    }

    // Hàm khởi tạo tooltips
    function initializeTooltips() {
        $('[data-bs-toggle="tooltip"]').tooltip();
    }

    // Hàm hiển thị thông báo với SweetAlert2
    function showNotification(type, message) {
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer);
                toast.addEventListener('mouseleave', Swal.resumeTimer);
            }
        });

        Toast.fire({
            icon: type,
            title: message
        });
    }


    // Toast.fire({
    //     icon: type,
    //     title: message
    // });
