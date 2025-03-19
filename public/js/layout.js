
        $(document).ready(function() {
    // Variables
    let searchTimeout = null;
    let isSearching = false;
    let currentFocus = -1;
    
    // Khởi tạo các chức năng
    initializeSearch();
    initializeSearchFeatures();
    initBackToTop();
    
    /**
     * Hàm tạo HTML đánh giá sao
     */
    function generateStarRating(rating) {
        // Đảm bảo rating trong phạm vi 0-5
        let validRating = parseFloat(rating);
        validRating = isNaN(validRating) ? 0 : validRating;
        validRating = Math.min(5, Math.max(0, validRating));
        
        const ratingPercentage = validRating * 22; // Chuyển đổi thành phần trăm
        
        return `
            <div class="stars-rating">
                <div class="stars-container">
                    <div class="stars-empty">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                    </div>
                    <div class="stars-filled" style="width: ${ratingPercentage}%">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                    </div>
                </div>
                <span class="rating-number">${validRating.toFixed(1)}</span>
            </div>
        `;
    }
    
    /**
     * Khởi tạo chức năng tìm kiếm cơ bản
     */
    function initializeSearch() {
        // Handle search input
        $("#timkiem").on('input focus', function(e) {
            const searchTerm = $(this).val().trim();
            
            // Clear previous timeout
            clearTimeout(searchTimeout);
            
            // Toggle clear button visibility
            toggleClearButton(searchTerm);
            
            if (searchTerm.length > 0) {
                if (e.type === 'input') {
                    // Add ripple effect
                    createRippleEffect();
                    
                    // Show loading state
                    showLoading();
                    
                    // Set timeout for search
                    searchTimeout = setTimeout(function() {
                        performSearch(searchTerm);
                    }, 400);
                } else if (e.type === 'focus' && $("#result li").length > 0) {
                    $(".search-results").addClass("show");
                }
            } else if (e.type === 'focus') {
                // Nếu không có nội dung, hiển thị lịch sử hoặc phim hot
                showSearchHistory();
            } else {
                resetSearchState();
            }
        });
        
        // Keyboard navigation
        $("#timkiem").on('keydown', handleKeyboardNavigation);
        
        // Click on search result 
        $("#result").on('click', 'li.search-result-item, li.hot-movie-item', handleResultClick);
        
        // Clear search button
        $(".clear-search").on('click', clearSearch);
        
        // Click search icon để submit form
        $(".search-icon").on('click', function() {
            // Focus vào ô tìm kiếm
            $("#timkiem").focus();
            
            // Nếu ô tìm kiếm đã có nội dung, submit form
            const searchTerm = $("#timkiem").val().trim();
            if (searchTerm.length > 0) {
                // Lưu tìm kiếm vào lịch sử
                saveSearchHistory(searchTerm);
                
                // Tạo hiệu ứng ripple khi click
                createRippleEffect();
                
                // Submit form để chuyển trang
                $(this).closest("form").submit();
            }
        });
        $("#timkiem").on('keydown', function(e) {
            if (e.keyCode === 13) { // Enter key
                const searchTerm = $(this).val().trim();
                if (searchTerm.length > 0) {
                    saveSearchHistory(searchTerm);
                }
            }
        });
        
        // Close search results when clicking outside
        $(document).on('click', function(e) {
            if (!$(e.target).closest('.search-box, .search-results').length) {
                resetSearchState();
            }
        });
    }
    
    /**
     * Toggle clear button visibility
     */
    function toggleClearButton(searchTerm) {
        if (searchTerm.length > 0) {
            $(".clear-search").addClass("visible");
        } else {
            $(".clear-search").removeClass("visible");
        }
    }
    
    /**
     * Create ripple effect when clicking
     */
    function createRippleEffect() {
        const ripple = $('<span class="search-ripple"></span>');
        $(".search-icon").append(ripple);
        setTimeout(() => {
            ripple.remove();
        }, 600);
    }
    
    /**
     * Show loading state
     */
    function showLoading() {
        $("#result, #no-results").hide();
        isSearching = true;
        $("#search-loading").show();
        $(".search-results").addClass("show");
    }
    
    /**
     * Handle keyboard navigation
     */
    function handleKeyboardNavigation(e) {
        const resultItems = $("#result li");
        
        // Down arrow
        if (e.keyCode === 40 && resultItems.length > 0) {
            currentFocus++;
            if (currentFocus >= resultItems.length) currentFocus = 0;
            setActiveSuggestion(resultItems);
            e.preventDefault();
        } 
        // Up arrow
        else if (e.keyCode === 38 && resultItems.length > 0) {
            currentFocus--;
            if (currentFocus < 0) currentFocus = resultItems.length - 1;
            setActiveSuggestion(resultItems);
            e.preventDefault();
        } 
        // Enter key
        else if (e.keyCode === 13 && currentFocus > -1 && resultItems.length > 0) {
            $(resultItems[currentFocus]).trigger('click');
            e.preventDefault();
        } 
        // Escape key
        else if (e.keyCode === 27) {
            resetSearchState();
        }
    }
    
    /**
     * Set active suggestion for keyboard navigation
     */
    function setActiveSuggestion(items) {
        items.removeClass("active");
        
        if (currentFocus >= 0) {
            $(items[currentFocus]).addClass("active");
            
            // Scroll to active item
            const container = $("#result");
            const item = $(items[currentFocus]);
            
            container.scrollTop(
                item.offset().top - 
                container.offset().top + 
                container.scrollTop() - 
                (container.height() / 2 - 
                item.height() / 2)
            );
        }
    }
    
    /**
     * Perform search
     */
    function performSearch(query) {
        
        
        // Log search for analytics
        logSearch(query);
        
        $.getJSON("/json/movies.json", function(data) {
            $("#result").empty();
            let resultCount = 0;
            const searchRegex = new RegExp(query, "i");
            
            // Sort results to prioritize title matches
            data.sort((a, b) => {
                const aTitle = a.title.search(searchRegex) !== -1;
                const bTitle = b.title.search(searchRegex) !== -1;
                
                if (aTitle && !bTitle) return -1;
                if (!aTitle && bTitle) return 1;
                return 0;
            });
            
            // Process results
            $.each(data, function(key, movie) {
                // Limit to 8 results for better UX
                if (resultCount >= 8) return false;
                
                if (movie.title.search(searchRegex) !== -1 || 
                    (movie.description && movie.description.search(searchRegex) !== -1)) {
                    resultCount++;
                    addSearchResult(movie, key, query);
                }
            });
            
            // Reset focus
            currentFocus = -1;
            
            // Display results or no results message
            $("#search-loading").hide();
            
            if (resultCount > 0) {
                $("#result").show();
                $("#no-results").hide();
                
                // Add animation delay to results
                $("#result li").each(function(index) {
                    $(this).css({ 'animation-delay': (index * 0.05) + 's' });
                });
            } else {
                $("#result").hide();
                $("#no-results").html(`
                    <i class="fas fa-search"></i>
                    <p>Không tìm thấy kết quả cho <strong>"${query}"</strong></p>
                    <small>Hãy thử tìm kiếm với từ khóa khác</small>
                `).show();
            }
            
            isSearching = false;
        }).fail(function() {
            handleSearchError();
        });
    }
    
    /**
     * Add a search result to the list
     */
    function addSearchResult(movie, key, query) {
        const highlightedTitle = highlightText(movie.title, query);
        const genres = movie.genres ? movie.genres.join(", ") : "";
        const rating = movie.rating ? movie.rating : "5.0";
        const starRating = generateStarRating(rating);
        const year = movie.year || 'N/A';
        
        let description = '';
        if (movie.description) {
            description = highlightText(
                movie.description.length > 80 ? movie.description.substring(0, 80) + '...' : movie.description,
                query
            );
        }
        
        const resultItem = $(`
            <li class="search-result-item" data-id="${movie.id || key}" data-slug="${movie.slug}">
                <div class="search-movie-poster">
                    <img src="/uploads/movie/${movie.image}" alt="${movie.title}">
                    <span class="movie-badge badge-year">${year}</span>
                </div>
                <div class="movie-info">
                    <div class="movie-title">${highlightedTitle}</div>
                    <div class="movie-meta">
                        ${starRating}
                        ${genres ? `<span class="movie-genre">${genres}</span>` : ''}
                    </div>
                    ${description ? `<div class="movie-desc">${description}</div>` : ''}
                </div>
                <div class="movie-action">
                    <i class="fas fa-play-circle"></i>
                </div>
            </li>
        `);
        
        $("#result").append(resultItem);
    }
    
    /**
     * Highlight search terms in text
     */
    function highlightText(text, query) {
        const regex = new RegExp(`(${query})`, 'gi');
        return text.replace(regex, '<span class="highlight">$1</span>');
    }
    
    /**
     * Handle search error
     */
    function handleSearchError() {
        isSearching = false;
        $("#search-loading").hide();
        $("#result").hide();
        $("#no-results").html(`
            <i class="fas fa-exclamation-triangle"></i>
            <p>Đã xảy ra lỗi khi tìm kiếm</p>
            <small>Vui lòng thử lại sau</small>
        `).show();
    }
    
    /**
     * Reset search state
     */
    function resetSearchState() {
        $(".search-results").removeClass("show");
        isSearching = false;
        currentFocus = -1;
    }
    
    /**
     * Handle click on search result
     */
    function handleResultClick() {
        const movieSlug = $(this).data('slug');
        const movieTitle = $(this).hasClass('hot-movie-item') 
            ? $(this).find('.hot-title').text() 
            : $(this).find('.movie-title').text();
        // Lưu từ khóa tìm kiếm vào lịch sử nếu có
        const searchTerm = $("#timkiem").val().trim();
        if (searchTerm.length > 0) {
            saveSearchHistory(searchTerm);
        }
        // Hiển thị hiệu ứng đã chọn
        $(this).addClass('selected');
        
        // Hiển thị toast thông báo
        showToast(`Đang chuyển đến phim "${movieTitle}"`);
        
        // Chuyển hướng đến trang chi tiết phim
        setTimeout(() => {
            window.location.href = `/phim/${movieSlug}`;
        }, 800);
    }
    
    /**
     * Clear search
     */
    function clearSearch() {
        $("#timkiem").val('').focus();
        $(this).removeClass("visible");
        resetSearchState();
    }
    
    /**
     * Show toast notification
     */
    function showToast(message) {
        const toast = $(`
            <div class="toast">
                <div class="toast-icon">
                    <i class="fas fa-film"></i>
                </div>
                <div class="toast-content">${message}</div>
            </div>
        `);
        
        $(".toast-container").append(toast);
        
        // Remove toast after animation
        setTimeout(() => {
            toast.remove();
        }, 3500);
    }
    
    /**
     * Log search for analytics
     */
    function logSearch(term) {
        if (term.trim().length > 1) {
            console.log("Search logged:", term);
            // Here you would typically send this data to your analytics service
        }
    }
    
    /**
     * Initialize back to top button
     */
    function initBackToTop() {
        const backToTopBtn = $("#back-to-top");
        
        $(window).scroll(function() {
            if ($(this).scrollTop() > 300) {
                backToTopBtn.addClass("visible");
            } else {
                backToTopBtn.removeClass("visible");
            }
        });
        
        backToTopBtn.click(function() {
            $("html, body").animate({scrollTop: 0}, 500);
            return false;
        });
    }
    
    // =================================
    // TÍNH NĂNG TÌM KIẾM NÂNG CAO
    // =================================
    
    /**
     * Lưu trữ lịch sử tìm kiếm
     * @param {string} term - Từ khóa tìm kiếm
     */
     function saveSearchHistory(term) {
        if (!term || term.trim().length === 0) return; // Chỉ kiểm tra xem có rỗng không
        
        // Lấy lịch sử tìm kiếm từ localStorage
        let history = JSON.parse(localStorage.getItem('searchHistory') || '[]');
        
        // Loại bỏ term nếu đã tồn tại (để tránh trùng lặp)
        history = history.filter(item => item.toLowerCase() !== term.toLowerCase());
        
        // Thêm term mới vào đầu mảng
        history.unshift(term);
        
        // Giới hạn số lượng term lưu trữ
        if (history.length > 8) {
            history = history.slice(0, 8);
        }
        
        // Lưu lại vào localStorage
        localStorage.setItem('searchHistory', JSON.stringify(history));
    }

    /**
     * Hiển thị lịch sử tìm kiếm
     */
    function showSearchHistory() {
        const history = JSON.parse(localStorage.getItem('searchHistory') || '[]');
        
        if (history.length === 0) {
            // Nếu không có lịch sử, hiển thị phim hot
            showHotSuggestions();
            return;
        }
        
        // Xóa kết quả hiện tại
        $("#result").empty();
        
        // Thêm tiêu đề
        $("#result").append('<li class="search-history-title"><i class="fas fa-history"></i> Lịch sử tìm kiếm<span class="clear-history">Xóa tất cả</span></li>');
        
        // Thêm các mục lịch sử
        history.forEach(term => {
            const historyItem = $(`
                <li class="search-history-item" data-term="${term}">
                    <i class="fas fa-history"></i>
                    <span class="history-term">${term}</span>
                    <i class="fas fa-times remove-history"></i>
                </li>
            `);
            
            $("#result").append(historyItem);
        });
        
        // Hiển thị kết quả
        $(".search-results").addClass("show");
        
        // Xử lý sự kiện click vào nút xóa lịch sử
        $(".clear-history").on('click', function(e) {
            e.stopPropagation();
            localStorage.removeItem('searchHistory');
            showHotSuggestions(); // Chuyển sang hiển thị phim hot sau khi xóa lịch sử
        });
        
        // Xử lý sự kiện click vào nút xóa một mục
        $(".remove-history").on('click', function(e) {
            e.stopPropagation();
            const term = $(this).closest('.search-history-item').data('term');
            removeFromHistory(term);
            $(this).closest('.search-history-item').fadeOut(300, function() {
                $(this).remove();
                if ($('.search-history-item').length === 0) {
                    showHotSuggestions(); // Chuyển sang hiển thị phim hot nếu đã xóa hết lịch sử
                }
            });
        });
        
        // Xử lý sự kiện click vào một mục lịch sử
        $(".search-history-item").on('click', function() {
            const term = $(this).data('term');
            $("#timkiem").val(term);
            performSearch(term);
        });
    }

    /**
     * Xóa một mục khỏi lịch sử tìm kiếm
     * @param {string} term - Từ khóa cần xóa
     */
    function removeFromHistory(term) {
        let history = JSON.parse(localStorage.getItem('searchHistory') || '[]');
        history = history.filter(item => item !== term);
        localStorage.setItem('searchHistory', JSON.stringify(history));
    }

    /**
     * Hiển thị gợi ý phim hot
     */
    function showHotSuggestions() {
        // Xóa kết quả hiện tại
        $("#result").empty();
        
        // Thêm tiêu đề
        $("#result").append('<li class="hot-movies-title"><i class="fas fa-fire"></i> Phim Hot</li>');
        
        // Lấy dữ liệu phim hot từ sidebar
        const hotMovies = [];
        
        // Lấy 5 phim đầu tiên từ phần "Phim Hot" trong sidebar
        $("#sidebar .popular-post .item").each(function(index) {
            if (index >= 5) return false; // Chỉ lấy 5 phim
            
            const title = $(this).find("p.title").text();
            const posterUrl = $(this).find("img.post-thumb").attr("src");
            const movieUrl = $(this).find("a").attr("href");
            const quality = $(this).find("span.is_trailer").text().trim();
            
            // Lấy slug từ URL (chỉ lấy phần cuối cùng của URL)
            const urlParts = movieUrl.split('/');
            const slug = urlParts[urlParts.length - 1];
            
            hotMovies.push({
                title: title,
                poster: posterUrl,
                url: movieUrl,
                quality: quality,
                slug: slug
            });
        });
        
        // Thêm các phim hot vào danh sách gợi ý
        hotMovies.forEach((movie, index) => {
            // Xác định class cho chất lượng phim
            let qualityClass = "hot-hd";
            if (movie.quality === "Trailer") {
                qualityClass = "hot-trailer";
            }
            
            const hotItem = $(`
                <li class="hot-movie-item" data-slug="${movie.slug}">
                    <div class="hot-poster">
                        <img src="${movie.poster}" alt="${movie.title}">
                    </div>
                    <div class="hot-info">
                        <div class="hot-title">${movie.title}</div>
                        <div class="hot-meta">
                            <span class="hot-quality ${qualityClass}">${movie.quality}</span>
                            <span class="hot-views"><i class="fas fa-eye"></i> 3.2K</span>
                        </div>
                    </div>
                </li>
            `);
            
            // Animation delay
            hotItem.css('animation-delay', (index * 0.08) + 's');
            
            $("#result").append(hotItem);
        });
        
        // Hiển thị kết quả
        $(".search-results").addClass("show");
        
       
    }

    /**
     * Khởi tạo chức năng tìm kiếm nâng cao
     */
    function initializeSearchFeatures() {
        // Handle search input focus khi không có nội dung
        $("#timkiem").on('focus', function() {
            const searchTerm = $(this).val().trim();
            
            if (searchTerm.length === 0) {
                // Hiển thị lịch sử tìm kiếm khi focus mà chưa nhập gì
                showSearchHistory();
            }
        });
    }
    
    // Chức năng lọc phim
    window.locphim = function() {
        showToast("Tính năng lọc phim đang được cập nhật");
    };
    
    // Load top view movies by default
    $.ajax({
        url: "{{url('/filter-topview-default')}}",
        method: "GET",
        success: function(data) {
            $('#show_data_default').html(data);
        }
    });
    
    // Filter sidebar click handler
    $('.filter-sidebar').click(function() {
        var href = $(this).attr('href');
        var value;
        
        // Determine value based on href
        if (href === '#ngay') {
            value = 0;
        } else if (href === '#tuan') {
            value = 1;
        } else {
            value = 2;
        }
        
        // AJAX request
        $.ajax({
            url: "{{url('/filter-topview-phim')}}",
            method: "POST",
            data: {value: value},
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            success: function(data) {
                $('#halim-ajax-popular-post-default').css("display", "none");
                $('#show_data').html(data);
            }
        });
    });
});

// Fix cho dropdown menu - hỗ trợ cả hover và click
$(document).ready(function() {
    $('.nav-item.dropdown').each(function() {
        // Xử lý cho thiết bị desktop (hover)
        $(this).on('mouseenter', function() {
            $(this).find('.dropdown-menu').addClass('show');
        }).on('mouseleave', function() {
            $(this).find('.dropdown-menu').removeClass('show');
        });
        
        // Xử lý cho thiết bị di động (click)
        $(this).find('.nav-link').on('click', function(e) {
            // Chỉ ngăn mặc định nếu dropdown chưa mở
            if (!$(this).siblings('.dropdown-menu').hasClass('show')) {
                e.preventDefault();
                
                // Đóng tất cả các dropdown khác
                $('.dropdown-menu.show').removeClass('show');
                
                // Mở dropdown này
                $(this).siblings('.dropdown-menu').addClass('show');
            }
        });
    });
    
    // Đóng dropdown khi click bên ngoài
    $(document).on('click', function(e) {
        if (!$(e.target).closest('.nav-item.dropdown').length) {
            $('.dropdown-menu.show').removeClass('show');
        }
    });
});


