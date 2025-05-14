@extends('layout')

@section('content')
<link rel="stylesheet" href="{{ asset('css/advanced-filter.css') }}">
<div class="advanced-filter-container">
    <h1 class="filter-main-title">Duyệt tìm</h1>

    <button class="filter-toggle-btn" id="toggleFilters">
        <i class="fas fa-filter"></i> Bộ lọc
    </button>

    <form action="{{ route('advancedfilter') }}" method="get" id="filterForm">
        <!-- Container cho hidden inputs -->
        <div id="hiddenInputsContainer"></div>

        <div class="filter-sections" id="filterSections">
            <!-- Quốc gia -->
            <div class="filter-section">
                <div class="section-header" data-toggle="section" data-target="countrySection">
                    <h3 class="filter-section-title">Quốc gia</h3>
                    <i class="fas fa-chevron-down"></i>
                </div>
                <div class="section-content" id="countrySection">
                    <div class="filter-options" data-filter-group="country">
                        <div class="filter-option {{ !request()->has('country') || in_array('all', (array)request()->country) ? 'selected' : '' }}"
                            data-value="all">Tất cả</div>
                        @foreach($country as $count)
                        <div class="filter-option {{ in_array($count->id, (array)request()->country) ? 'selected' : '' }}"
                            data-value="{{ $count->id }}">{{ $count->title }}</div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Loại phim -->
            <div class="filter-section">
                <div class="section-header" data-toggle="section" data-target="categorySection">
                    <h3 class="filter-section-title">Danh mục phim</h3>
                    <i class="fas fa-chevron-down"></i>
                </div>
                <div class="section-content" id="categorySection">
                    <div class="filter-options" data-filter-group="category">
                        <div class="filter-option {{ !request()->has('category') || in_array('all', (array)request()->category) ? 'selected' : '' }}"
                            data-value="all">Tất cả</div>
                        @foreach($category as $cate)
                        <div class="filter-option {{ in_array($cate->id, (array)request()->category) ? 'selected' : '' }}"
                            data-value="{{ $cate->id }}">{{ $cate->title }}</div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Đánh giá sao -->
            <div class="filter-section">
                <div class="section-header" data-toggle="section" data-target="ratingSection">
                    <h3 class="filter-section-title">Đánh giá</h3>
                    <i class="fas fa-chevron-down"></i>
                </div>
                <div class="section-content" id="ratingSection">
                    <div class="filter-options" data-filter-group="rating">
                        <div class="filter-option {{ !request()->has('rating') || in_array('all', (array)request()->rating) ? 'selected' : '' }}"
                            data-value="all">Tất cả</div>
                        <div class="filter-option {{ in_array('5', (array)request()->rating) ? 'selected' : '' }}"
                            data-value="5">
                            <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i
                                class="fas fa-star"></i><i class="fas fa-star"></i>
                        </div>
                        <div class="filter-option {{ in_array('4', (array)request()->rating) ? 'selected' : '' }}"
                            data-value="4">
                            <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i
                                class="fas fa-star"></i>
                            <i class="far fa-star"></i>
                        </div>
                        <div class="filter-option {{ in_array('3', (array)request()->rating) ? 'selected' : '' }}"
                            data-value="3">
                            <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i>
                            <i class="far fa-star"></i><i class="far fa-star"></i>
                        </div>
                        <div class="filter-option {{ in_array('2', (array)request()->rating) ? 'selected' : '' }}"
                            data-value="2">
                            <i class="fas fa-star"></i><i class="fas fa-star"></i>
                            <i class="far fa-star"></i><i class="far fa-star"></i><i class="far fa-star"></i>
                        </div>
                        <div class="filter-option {{ in_array('1', (array)request()->rating) ? 'selected' : '' }}"
                            data-value="1">
                            <i class="fas fa-star"></i>
                            <i class="far fa-star"></i><i class="far fa-star"></i><i class="far fa-star"></i><i
                                class="far fa-star"></i>
                        </div>
                    </div>
                </div>
            </div>


            <!-- Thể loại -->
            <div class="filter-section">
                <div class="section-header" data-toggle="section" data-target="genreSection">
                    <h3 class="filter-section-title">Thể loại</h3>
                    <i class="fas fa-chevron-down"></i>
                </div>
                <div class="section-content" id="genreSection">
                    <div class="filter-options" data-filter-group="genre">
                        <div class="filter-option {{ !request()->has('genre') || in_array('all', (array)request()->genre) ? 'selected' : '' }}"
                            data-value="all">Tất cả</div>
                        @foreach($genre as $gen)
                        <div class="filter-option {{ in_array($gen->id, (array)request()->genre) ? 'selected' : '' }}"
                            data-value="{{ $gen->id }}">{{ $gen->title }}</div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Năm phát hành -->
            <div class="filter-section">
                <div class="section-header" data-toggle="section" data-target="yearSection">
                    <h3 class="filter-section-title">Năm phát hành</h3>
                    <i class="fas fa-chevron-down"></i>
                </div>
                <div class="section-content" id="yearSection">
                    <div class="filter-options" data-filter-group="year">
                        <div class="filter-option {{ !request()->has('year') || in_array('all', (array)request()->year) ? 'selected' : '' }}"
                            data-value="all">Tất cả</div>
                        @for($year = 2025; $year >= 2010; $year--)
                        <div class="filter-option {{ in_array($year, (array)request()->year) ? 'selected' : '' }}"
                            data-value="{{ $year }}">{{ $year }}</div>
                        @endfor
                    </div>
                </div>
            </div>


            <!-- Sắp xếp theo -->
            <div class="filter-section">
                <div class="section-header" data-toggle="section" data-target="sortSection">
                    <h3 class="filter-section-title">Sắp xếp theo</h3>
                    <i class="fas fa-chevron-down"></i>
                </div>
                <div class="section-content" id="sortSection">
                    <div class="filter-options" data-filter-group="sort" data-single-select="true">
                        <div class="filter-option {{ !request()->has('sort') || request()->sort == 'latest' ? 'selected' : '' }}"
                            data-value="latest">Mới nhất</div>
                        <div class="filter-option {{ request()->sort == 'oldest' ? 'selected' : '' }}"
                            data-value="oldest">Cũ nhất</div>
                        <div class="filter-option {{ request()->sort == 'name_asc' ? 'selected' : '' }}"
                            data-value="name_asc">Tên A-Z</div>
                        <div class="filter-option {{ request()->sort == 'name_desc' ? 'selected' : '' }}"
                            data-value="name_desc">Tên Z-A</div>
                        <div class="filter-option {{ request()->sort == 'views' ? 'selected' : '' }}"
                            data-value="views">Lượt xem</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="filter-actions">
            <!-- Đã chuyển nút Lọc kết quả ra đây -->
            <button type="submit" class="apply-filter-btn">
                Lọc kết quả <span class="arrow">→</span>
            </button>

            <div class="filter-buttons">
                <div class="filter-search-form">
                    <input type="text" name="keyword" placeholder="Tìm kiếm phim..."
                        value="{{ request()->keyword ?? '' }}">
                    <button type="button"><i class="fas fa-search"></i></button>
                </div>

                <!-- Đã chuyển nút Đặt lại vào đây -->
                <a href="{{ route('advancedfilter') }}" class="btn-reset-filter">
                    <i class="fas fa-sync-alt"></i> Đặt lại bộ lọc
                </a>

                <!-- Thêm nút Đóng bộ lọc mới -->
                <button type="button" class="btn-close-filter" id="closeFilters">
                    <i class="fas fa-times"></i> Đóng
                </button>
            </div>
        </div>


    </form>
    <!-- Hiển thị kết quả phim (phần này giữ nguyên) -->
    <div class="filter-results">
        <div class="filter-results-header">
            <h2 style="font-size: 22px; font-weight: 700; color: #fff; margin-bottom: 20px;">
                <i class="fas fa-film" style="color: #04c1e7; margin-right: 10px;"></i> Danh sách phim
            </h2>
            <p class="results-count">Tìm thấy {{ $movie->total() }} phim</p>
        </div>

        <div class="movie-grid" style="gap: 20px;">
            @foreach($movie as $key => $mov)
            <div class="movie-item"
                style="box-shadow: 0 10px 20px rgba(0,0,0,0.3); border-radius: 12px; overflow: hidden; transition: all 0.3s ease;">
                <div class="halim-item">
                    <a class="halim-thumb" href="{{route('movie',$mov->slug)}}" style="border-radius: 12px;">
                        <figure>
                            @php
                            $image_path = substr($mov->image, 0, 5) === 'https' ? $mov->image :
                            asset('uploads/movie/'.$mov->image);
                            @endphp
                            <img class="lazy img-responsive" src="{{$image_path}}" alt="{{$mov->title}}"
                                style="transition: transform 0.5s ease;">
                        </figure>
                        <!-- Thêm hiển thị đánh giá -->
                        <div
                            style="position: absolute; top: 10px; right: 10px; background: rgba(0,0,0,0.7); padding: 3px 8px; border-radius: 4px; z-index: 5;">
                            <i class="fas fa-star" style="color: #ffcc00;"></i>
                            <span style="font-size: 12px; color: #fff; font-weight: 600;">
                                @php $rating = App\Models\Rating::where('movie_id', $mov->id)->avg('rating'); $rating =
                                round($rating); $count = App\Models\Rating::where('movie_id', $mov->id)->count();
                                @endphp
                                {{ $rating > 0 ? $rating : 'N/A' }}
                                <small>({{ $count }})</small>
                            </span>
                        </div>


                        <!-- Thẻ phân loại phim -->
                        <span class="status" style="position: absolute; top: 10px; left: 10px; z-index: 10;">
                            @if($mov->resolution==0)
                            <span class="tag-base hd-tag"
                                style="box-shadow: 0 2px 8px rgba(3,245,253,0.3); padding: 5px 10px; font-weight: 700;">HD</span>
                            @elseif($mov->resolution==1)
                            <span class="tag-base sd-tag"
                                style="box-shadow: 0 2px 8px rgba(15,52,96,0.3); padding: 5px 10px; font-weight: 700;">SD</span>
                            @elseif($mov->resolution==2)
                            <span class="tag-base hdcam-tag"
                                style="box-shadow: 0 2px 8px rgba(83,52,131,0.3); padding: 5px 10px; font-weight: 700;">HDCam</span>
                            @elseif($mov->resolution==3)
                            <span class="tag-base cam-tag"
                                style="box-shadow: 0 2px 8px rgba(152,150,241,0.3); padding: 5px 10px; font-weight: 700;">Cam</span>
                            @elseif($mov->resolution==4)
                            <span class="tag-base fullhd-tag"
                                style="box-shadow: 0 2px 8px rgba(227,4,175,0.3); padding: 5px 10px; font-weight: 700;">FullHD</span>
                            @else
                            <span class="tag-base trailer-tag"
                                style="box-shadow: 0 2px 8px rgba(231,76,60,0.3); padding: 5px 10px; font-weight: 700;">Trailer</span>
                            @endif
                        </span>

                        <!-- Nút play -->
                        <div class="play-button"
                            style="background: rgba(4,193,231,0.2); backdrop-filter: blur(5px); border: 2px solid white;">
                            <i class="fas fa-play"></i>
                        </div>

                        <div class="halim-post-title-box"
                            style="background: linear-gradient(0deg, rgba(0,0,0,0.9) 0%, rgba(0,0,0,0.6) 50%, rgba(0,0,0,0) 100%);">
                            <div class="halim-post-title">
                                <h2 class="entry-title" style="font-size: 16px; font-weight: 700; margin-bottom: 5px;">
                                    {{$mov->title}}</h2>
                                <p class="original-title" style="font-size: 13px; margin-bottom: 8px;">
                                    {{$mov->name_eng}}</p>

                                <!-- Thêm năm-->
                                <div style="display: flex; justify-content: space-between; font-size: 14px;">
                                    @if(isset($mov->year))
                                    <span style="color: #ffffff;"><i class="far fa-calendar-alt"
                                            style="margin-right: 4px;"></i> {{$mov->year}}</span>
                                    @endif

                                    @if(isset($mov->imdb))
                                    <span style="color: #fff;"><i class="fas fa-star"
                                            style="color: #f3ce13; margin-right: 4px;"></i> {{$mov->imdb}}</span>
                                    @endif
                                </div>
                                <!-- Thêm hiển thị lượt xem -->
                                <div
                                    style="display: flex; align-items: center; justify-content: flex-start; margin-top: 5px; font-size: 13px;">
                                    <span class="view-count-display"
                                        style="color: #04c1e7; display: flex; align-items: center;">
                                        <i class="fas fa-eye" style="margin-right: 5px;"></i>
                                        <span class="view-counter" data-movie-id="{{$mov->id}}"
                                            data-movie-slug="{{$mov->slug}}">
                                            @php
                                            $views = $mov->count_views;
                                            if ($views > 1000000) {
                                            echo round($views/1000000, 1) . 'M';
                                            } elseif ($views > 1000) {
                                            echo round($views/1000, 1) . 'K';
                                            } else {
                                            echo $views;
                                            }
                                            @endphp
                                            lượt xem
                                        </span>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
            @endforeach
        </div>

        <div class="pagination-wrapper">
            {!! $movie->appends(request()->query())->links("pagination::bootstrap-4") !!}
        </div>
    </div>


</div>
<script>
    /**
 * BỘ LỌC PHIM NÂNG CAO VỚI AJAX
 * --------------------------------------------
 * Script điều khiển tính năng lọc phim không tải lại trang
 * Các chức năng chính:
 * - Lọc phim theo nhiều tiêu chí (quốc gia, thể loại, năm...)
 * - Chuyển trang bằng AJAX không tải lại trang
 * - Hiệu ứng loading và animation khi hiển thị kết quả
 */
document.addEventListener('DOMContentLoaded', function() {
    
    //===== PHẦN 1: KHỞI TẠO =====//
    
    // Lấy tham chiếu đến các phần tử HTML quan trọng
    const elements = {
        form: document.getElementById('filterForm'),
        toggleBtn: document.getElementById('toggleFilters'),
        filterSections: document.getElementById('filterSections'),
        sectionHeaders: document.querySelectorAll('.section-header'),
        searchInput: document.querySelector('.filter-search-form input'),
        searchButton: document.querySelector('.filter-search-form button'),
        closeBtn: document.getElementById('closeFilters'),
        hiddenContainer: document.getElementById('hiddenInputsContainer'),
        applyBtn: document.querySelector('.apply-filter-btn'),
        resultsSection: document.querySelector('.filter-results')
    };
    
    // Lưu trữ trạng thái các bộ lọc đã chọn
    const selectedFilters = {
        country: getInitialValuesFromUrl('country'),
        category: getInitialValuesFromUrl('category'),
        genre: getInitialValuesFromUrl('genre'),
        year: getInitialValuesFromUrl('year'),
        rating: getInitialValuesFromUrl('rating'),
        sort: getInitialValuesFromUrl('sort')
    };
    
    // Tạo các input ẩn ban đầu
    createHiddenInputs();
    
    //===== PHẦN 2: ĐĂNG KÝ CÁC SỰ KIỆN =====//
    
    // Xử lý form submit
    if (elements.form) {
        elements.form.addEventListener('submit', function(e) {
            e.preventDefault();
            loadFilteredResults();
        });
    }
    
    // Xử lý nút "Lọc kết quả"
    if (elements.applyBtn) {
        elements.applyBtn.addEventListener('click', function(e) {
            e.preventDefault();
            loadFilteredResults();
        });
    }
    
    // Xử lý nút mở/đóng bộ lọc
    if (elements.toggleBtn) {
        elements.toggleBtn.addEventListener('click', function(e) {
            e.preventDefault();
            toggleFilters();
        });
    }
    
    // Xử lý nút đóng bộ lọc
    if (elements.closeBtn) {
        elements.closeBtn.addEventListener('click', function(e) {
            e.preventDefault();
            collapseFilters();
            window.scrollTo({ top: 0, behavior: 'smooth' });
        });
    }
    
    // Xử lý tìm kiếm bằng Enter
    if (elements.searchInput) {
        elements.searchInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                loadFilteredResults();
            }
        });
    }
    
    // Xử lý nút tìm kiếm
    if (elements.searchButton) {
        elements.searchButton.addEventListener('click', function(e) {
            e.preventDefault();
            loadFilteredResults();
        });
    }
    
    // Xử lý thu gọn/mở rộng các mục bộ lọc
    elements.sectionHeaders.forEach(header => {
        header.addEventListener('click', function(e) {
            e.stopPropagation();
            toggleFilterSection(this);
        });
    });
    
    // Xử lý click vào các tùy chọn bộ lọc
    const filterGroups = document.querySelectorAll('.filter-options');
    filterGroups.forEach(group => {
        setupFilterOptions(group);
    });
    
    // Lắng nghe sự kiện Back/Forward của trình duyệt
    window.addEventListener('popstate', handlePopState);
    
    // Khởi tạo trang ban đầu
    initializeFilterSections();
    
    // Chờ trang tải xong rồi áp dụng hiệu ứng và khởi tạo phân trang
    setTimeout(() => {
        applyItemAnimations();
        setupPaginationLinks();
        document.body.classList.add('filter-loaded');
    }, 500);
    
    //===== PHẦN 3: CÁC HÀM XỬ LÝ BỘ LỌC =====//
    
    /**
     * Toggle mở/đóng một mục bộ lọc
     */
    function toggleFilterSection(header) {
        const targetId = header.getAttribute('data-target');
        if (!targetId) return;
        
        const targetContent = document.getElementById(targetId);
        if (!targetContent) return;
        
        // Toggle trạng thái
        header.classList.toggle('collapsed');
        targetContent.classList.toggle('collapsed');
        
        // Cập nhật icon
        const icon = header.querySelector('i');
        if (icon) {
            if (header.classList.contains('collapsed')) {
                icon.classList.remove('fa-chevron-down');
                icon.classList.add('fa-chevron-right');
            } else {
                icon.classList.remove('fa-chevron-right');
                icon.classList.add('fa-chevron-down');
            }
        }
    }
    
    /**
     * Thiết lập xử lý cho các tùy chọn trong một nhóm bộ lọc
     */
    function setupFilterOptions(group) {
        const filterType = group.dataset.filterGroup;
        if (!filterType) return;
        
        // Kiểm tra xem bộ lọc này có phải chỉ chọn một không
        const isSingleSelect = group.dataset.singleSelect === "true";
        
        const options = group.querySelectorAll('.filter-option');
        options.forEach(option => {
            option.addEventListener('click', function(e) {
                e.stopPropagation();
                const value = this.dataset.value;
                if (!value) return;
                
                // Xử lý tùy chọn lọc được click
                if (isSingleSelect || filterType === 'sort') {
                    handleSingleSelectOption(options, this, value, filterType);
                } else {
                    handleMultiSelectOption(options, this, value, filterType, group);
                }
                
                // Cập nhật input ẩn
                createHiddenInputs();
            });
        });
    }
    
    /**
     * Xử lý tùy chọn cho bộ lọc chỉ chọn một (như Sắp xếp)
     */
    function handleSingleSelectOption(allOptions, selectedOption, value, filterType) {
        // Bỏ chọn tất cả các tùy chọn
        allOptions.forEach(opt => opt.classList.remove('selected'));
        
        // Chọn tùy chọn hiện tại
        selectedOption.classList.add('selected');
        
        // Cập nhật danh sách chọn
        selectedFilters[filterType] = [value];
    }
    
    /**
     * Xử lý tùy chọn cho bộ lọc chọn nhiều (như Thể loại, Quốc gia)
     */
    function handleMultiSelectOption(allOptions, selectedOption, value, filterType, group) {
        if (value === 'all') {
            // Bỏ chọn tất cả, chỉ chọn "Tất cả"
            allOptions.forEach(opt => opt.classList.remove('selected'));
            selectedOption.classList.add('selected');
            selectedFilters[filterType] = ['all'];
        } else {
            // Bỏ chọn "Tất cả"
            const allOption = group.querySelector('.filter-option[data-value="all"]');
            if (allOption) {
                allOption.classList.remove('selected');
                selectedFilters[filterType] = selectedFilters[filterType].filter(val => val !== 'all');
            }
            
            // Toggle lựa chọn hiện tại
            selectedOption.classList.toggle('selected');
            
            // Cập nhật danh sách đã chọn
            if (selectedOption.classList.contains('selected')) {
                if (!selectedFilters[filterType].includes(value)) {
                    selectedFilters[filterType].push(value);
                }
            } else {
                selectedFilters[filterType] = selectedFilters[filterType].filter(v => v !== value);
            }
            
            // Nếu không còn mục nào được chọn, chọn lại "Tất cả"
            if (selectedFilters[filterType].length === 0 && allOption) {
                allOption.classList.add('selected');
                selectedFilters[filterType] = ['all'];
            }
        }
    }
    
    //===== PHẦN 4: AJAX LOAD RESULTS =====//
    
    /**
     * Tải kết quả lọc bằng AJAX
     */
    function loadFilteredResults() {
        if (!elements.form || !elements.resultsSection) return;
        
        // Hiển thị hiệu ứng loading
        showLoading();
        
        // Cập nhật input ẩn và lấy dữ liệu form
        createHiddenInputs();
        const formData = new FormData(elements.form);
        
        // Tạo query string và cập nhật URL
        const queryString = new URLSearchParams(formData).toString();
        const newUrl = `${window.location.pathname}?${queryString}`;
        history.pushState({}, '', newUrl);
        
        // Gửi request AJAX
        fetchAndUpdateResults(newUrl);
    }
    
    /**
     * Hiển thị hiệu ứng loading
     */
    function showLoading() {
        if (!elements.resultsSection) return;
        
        // Xóa overlay cũ nếu có
        const oldOverlay = elements.resultsSection.querySelector('.results-loading-overlay');
        if (oldOverlay) oldOverlay.remove();
        
        // Thêm overlay loading mới
        const loadingHTML = '<div class="results-loading-overlay"><div class="loading-spinner"></div></div>';
        elements.resultsSection.insertAdjacentHTML('beforeend', loadingHTML);
        
        // Skeleton loading cho grid phim
        const movieGrid = elements.resultsSection.querySelector('.movie-grid');
        if (movieGrid) {
            // Lưu chiều cao và số lượng phim hiện tại
            const gridHeight = movieGrid.offsetHeight || 500;
            const currentMovieCount = movieGrid.querySelectorAll('.movie-item').length || 12;
            
            // Thiết lập chiều cao tối thiểu để tránh nhảy layout
            movieGrid.style.minHeight = `${gridHeight}px`;
            
            // Xóa nội dung hiện tại và thêm các skeleton
            movieGrid.innerHTML = '';
            
            // Thêm các skeleton item
            let skeletonHTML = '';
            for (let i = 0; i < currentMovieCount; i++) {
                skeletonHTML += '<div class="skeleton-movie"></div>';
            }
            movieGrid.innerHTML = skeletonHTML;
        }
    }
    
    /**
     * Lấy và cập nhật kết quả từ URL bằng AJAX
     */
    function fetchAndUpdateResults(url) {
        fetch(url)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Có lỗi khi tải dữ liệu');
                }
                return response.text();
            })
            .then(html => {
                updateResultsContent(html);
            })
            .catch(error => {
                console.error('Lỗi khi tải kết quả:', error);
                showErrorMessage();
                
                // Fallback: Tải lại trang sau 1.5 giây
                setTimeout(() => {
                    elements.form.submit();
                }, 1500);
            });
    }
    
    /**
     * Cập nhật nội dung kết quả từ HTML nhận được
     */
    function updateResultsContent(html) {
        if (!elements.resultsSection) return;
        
        // Parse HTML
        const parser = new DOMParser();
        const doc = parser.parseFromString(html, 'text/html');
        const newContent = doc.querySelector('.filter-results');
        
        if (newContent) {
            // Xóa overlay loading
            const overlay = elements.resultsSection.querySelector('.results-loading-overlay');
            if (overlay) overlay.remove();
            
            // Kiểm tra số lượng kết quả
            const resultsCount = newContent.querySelector('.results-count');
            const totalCount = resultsCount ? 
                parseInt(resultsCount.textContent.match(/\d+/)[0] || '0') : 0;
            
            if (totalCount === 0) {
                showNoResultsMessage();
            } else {
                // Hiệu ứng fade out/fade in
                elements.resultsSection.style.opacity = '0';
                
                setTimeout(() => {
                    // Cập nhật nội dung
                    elements.resultsSection.innerHTML = newContent.innerHTML;
                    elements.resultsSection.style.opacity = '1';
                    
                    // Áp dụng hiệu ứng 
                    applyItemAnimations();
                    
                    // Cuộn đến khu vực kết quả
                    elements.resultsSection.scrollIntoView({ 
                        behavior: 'smooth',
                        block: 'start' 
                    });
                    
                    // Khởi tạo lại các link phân trang
                    setupPaginationLinks();
                }, 150);
            }
        }
    }
    
    /**
     * Hiển thị thông báo khi không có kết quả
     */
    function showNoResultsMessage() {
        if (!elements.resultsSection) return;
        
        // Cập nhật tiêu đề
        const header = elements.resultsSection.querySelector('.filter-results-header');
        if (header) {
            const countEl = header.querySelector('.results-count');
            if (countEl) countEl.textContent = 'Tìm thấy 0 phim';
        }
        
        // Hiển thị thông báo
        const movieGrid = elements.resultsSection.querySelector('.movie-grid');
        if (movieGrid) {
            movieGrid.innerHTML = `
                <div class="no-results-message">
                    <i class="fas fa-search"></i>
                    <h3>Không tìm thấy phim nào</h3>
                    <p>Thử thay đổi các bộ lọc hoặc tìm kiếm với từ khóa khác.</p>
                </div>
            `;
        }
        
        // Xóa phân trang
        const pagination = elements.resultsSection.querySelector('.pagination-wrapper');
        if (pagination) pagination.innerHTML = '';
    }
    
    /**
     * Hiển thị thông báo lỗi
     */
    function showErrorMessage() {
        if (!elements.resultsSection) return;
        
        // Xóa overlay loading
        const overlay = elements.resultsSection.querySelector('.results-loading-overlay');
        if (overlay) overlay.remove();
        
        // Hiển thị thông báo lỗi
        const movieGrid = elements.resultsSection.querySelector('.movie-grid');
        if (movieGrid) {
            movieGrid.innerHTML = `
                <div class="no-results-message">
                    <i class="fas fa-exclamation-triangle" style="color: #ff5252;"></i>
                    <h3>Đã xảy ra lỗi</h3>
                    <p>Không thể tải dữ liệu phim. Trang sẽ tải lại sau vài giây...</p>
                </div>
            `;
        }
    }
    
    //===== PHẦN 5: CÁC HÀM HIỆU ỨNG VÀ ANIMATION =====//
    
    /**
     * Áp dụng hiệu ứng animation cho các phim
     */
    function applyItemAnimations() {
        const movieItems = document.querySelectorAll('.movie-item');
        movieItems.forEach((item, index) => {
            // Reset animation
            item.style.animation = 'none';
            item.offsetHeight; // Force reflow
            
            // Áp dụng animation mới với độ trễ
            const delay = Math.min(0.05 * (index + 1), 0.5);
            item.style.animation = `fadeIn 0.5s ease ${delay}s forwards`;
            item.style.opacity = '0';
            item.style.transform = 'translateY(20px)';
        });
    }
    
    /**
     * Thiết lập AJAX cho các link phân trang
     */
    function setupPaginationLinks() {
        // Các bộ chọn phân trang có thể có
        const selectors = [
            '.pagination-wrapper a',
            '.pagination a',
            '.filter-results nav a',
            '.filter-results [role="navigation"] a'
        ].join(', ');
        
        // Lấy tất cả link phân trang
        const paginationLinks = document.querySelectorAll(selectors);
        
        // Thiết lập sự kiện cho mỗi link
        paginationLinks.forEach(link => {
            // Clone node để xóa event listener cũ
            const clonedLink = link.cloneNode(true);
            if (link.parentNode) {
                link.parentNode.replaceChild(clonedLink, link);
            }
            
            // Thêm event listener mới
            clonedLink.addEventListener('click', function(e) {
                e.preventDefault();
                
                const url = this.getAttribute('href');
                if (!url) return;
                
                // Cập nhật URL và tải trang bằng AJAX
                history.pushState({}, '', url);
                showLoading();
                fetchAndUpdateResults(url);
            });
        });
    }
    
    //===== PHẦN 6: CÁC HÀM TIỆN ÍCH =====//
    
    /**
     * Xử lý Back/Forward của trình duyệt
     */
    function handlePopState() {
        const currentUrl = window.location.href;
        showLoading();
        fetchAndUpdateResults(currentUrl);
    }
    
    /**
     * Lấy giá trị ban đầu từ URL cho mỗi loại bộ lọc
     */
    function getInitialValuesFromUrl(filterType) {
        const urlParams = new URLSearchParams(window.location.search);
        
        // Xử lý đặc biệt cho sort
        if (filterType === 'sort') {
            return urlParams.has(filterType) ? [urlParams.get(filterType)] : ['latest'];
        }
        
        // Xử lý các loại bộ lọc mảng
        if (urlParams.has(`${filterType}[]`)) {
            const values = urlParams.getAll(`${filterType}[]`);
            return values.length > 0 ? values : ['all'];
        }
        
        // Mặc định là 'all'
        return ['all'];
    }
    
    /**
     * Tạo các input ẩn từ các bộ lọc đã chọn
     */
    function createHiddenInputs() {
        if (!elements.hiddenContainer) return;
        
        elements.hiddenContainer.innerHTML = '';
        
        // Tạo input cho mỗi giá trị được chọn
        for (const [type, values] of Object.entries(selectedFilters)) {
            values.forEach(value => {
                if (value) {
                    const input = document.createElement('input');
                    input.type = 'hidden';
                    
                    // Xử lý tên khác nhau cho sort và các bộ lọc khác
                    input.name = type === 'sort' ? type : `${type}[]`;
                    input.value = value;
                    
                    elements.hiddenContainer.appendChild(input);
                }
            });
        }
        
        // Thêm input từ khóa tìm kiếm nếu có
        if (elements.searchInput && elements.searchInput.value) {
            const keywordInput = document.createElement('input');
            keywordInput.type = 'hidden';
            keywordInput.name = 'keyword';
            keywordInput.value = elements.searchInput.value;
            elements.hiddenContainer.appendChild(keywordInput);
        }
    }
    
    /**
     * Mở/đóng toàn bộ bộ lọc
     */
    function toggleFilters() {
        if (!elements.toggleBtn || !elements.filterSections) return;
        
        elements.toggleBtn.classList.toggle('collapsed');
        elements.filterSections.classList.toggle('collapsed');
        
        // Đổi icon và text
        const icon = elements.toggleBtn.querySelector('i');
        if (icon) {
            if (elements.toggleBtn.classList.contains('collapsed')) {
                icon.classList.remove('fa-filter');
                icon.classList.add('fa-plus');
                elements.toggleBtn.textContent = ' Mở bộ lọc';
                elements.toggleBtn.prepend(icon);
            } else {
                icon.classList.remove('fa-plus');
                icon.classList.add('fa-filter');
                elements.toggleBtn.textContent = ' Bộ lọc';
                elements.toggleBtn.prepend(icon);
            }
        }
    }
    
    /**
     * Thu gọn bộ lọc
     */
    function collapseFilters() {
        if (!elements.toggleBtn || !elements.filterSections) return;
        
        elements.filterSections.classList.add('collapsed');
        elements.toggleBtn.classList.add('collapsed');
        
        // Cập nhật icon và text
        const icon = elements.toggleBtn.querySelector('i');
        if (icon) {
            icon.classList.remove('fa-filter');
            icon.classList.add('fa-plus');
            elements.toggleBtn.textContent = ' Mở bộ lọc';
            elements.toggleBtn.prepend(icon);
        }
    }
    
    /**
     * Khởi tạo trạng thái mở/đóng các section bộ lọc
     */
    function initializeFilterSections() {
        let openSectionCount = 0;
        const maxOpenSections = 2; // Số lượng section mở tối đa
        
        elements.sectionHeaders.forEach(header => {
            const targetId = header.getAttribute('data-target');
            if (!targetId) return;
            
            const targetContent = document.getElementById(targetId);
            if (!targetContent) return;
            
            // Kiểm tra xem section này có tùy chọn nào được chọn không
            const hasSelectedOption = targetContent.querySelector('.filter-option.selected');
            
            if (hasSelectedOption) {
                // Giữ mở section này
                header.classList.remove('collapsed');
                targetContent.classList.remove('collapsed');
                openSectionCount++;
            } else if (openSectionCount >= maxOpenSections) {
                // Thu gọn nếu đã đạt số lượng mở tối đa
                header.classList.add('collapsed');
                targetContent.classList.add('collapsed');
            } else {
                // Mở thêm section nếu chưa đạt giới hạn
                openSectionCount++;
            }
        });
    }
});
// Đồng bộ lượt xem từ sessionStorage
$(document).ready(function() {
    // Lấy dữ liệu cập nhật lượt xem từ sessionStorage
    const viewUpdates = JSON.parse(sessionStorage.getItem('viewUpdates') || '{}');
    
    // Cập nhật hiển thị lượt xem nếu có dữ liệu mới
    if (Object.keys(viewUpdates).length > 0) {
        $('.view-counter').each(function() {
            const movieSlug = $(this).data('movie-slug');
            if (viewUpdates[movieSlug]) {
                $(this).text(viewUpdates[movieSlug] + ' lượt xem');
            }
        });
    }
});
</script>


@endsection