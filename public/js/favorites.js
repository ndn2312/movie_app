// Script xử lý phim yêu thích với database
console.log('Loading favorites.js');

$(document).ready(function () {
    console.log('Document ready in favorites.js');

    // Kiểm tra biến toàn cục
    console.log('Global vars check:');
    console.log('- isLoggedIn:', typeof isLoggedIn !== 'undefined' ? isLoggedIn : 'undefined');
    console.log('- csrfToken:', typeof csrfToken !== 'undefined' ? (csrfToken ? 'set' : 'empty') : 'undefined');
    console.log('- checkFavoriteUrl:', typeof checkFavoriteUrl !== 'undefined' ? checkFavoriteUrl : 'undefined');
    console.log('- toggleFavoriteUrl:', typeof toggleFavoriteUrl !== 'undefined' ? toggleFavoriteUrl : 'undefined');

    // Xử lý nút favorite
    var favoriteBtn = $('#favoriteBtn');
    console.log('FavoriteBtn exists:', favoriteBtn.length > 0);

    if (favoriteBtn.length === 0) {
        console.log('FavoriteBtn not found, exiting script');
        return;
    }

    var movieId = favoriteBtn.data('movie-id');
    console.log('Movie ID from button:', movieId);

    if (!movieId) {
        console.log('No movie ID found, exiting script');
        return;
    }

    // Đăng ký sự kiện click trực tiếp
    favoriteBtn.on('click', function (e) {
        console.log('Favorite button clicked');

        if (typeof isLoggedIn !== 'undefined' && isLoggedIn) {
            console.log('User is logged in, toggling favorite');
            toggleFavorite();
        } else {
            console.log('User is not logged in, showing login modal');
            $('#loginModal').modal('show');
            if (typeof showToast === 'function') {
                showToast('Vui lòng đăng nhập để sử dụng tính năng này');
            } else {
                console.error('showToast function not found');
                alert('Vui lòng đăng nhập để sử dụng tính năng này');
            }
        }
    });

    // Gọi kiểm tra trạng thái ban đầu
    if (typeof isLoggedIn !== 'undefined' && isLoggedIn) {
        console.log('Checking initial favorite status');
        checkFavoriteStatus();
    }

    // Hàm kiểm tra trạng thái yêu thích
    function checkFavoriteStatus() {
        if (typeof checkFavoriteUrl === 'undefined') {
            console.error('checkFavoriteUrl not defined');
            return;
        }

        console.log('Sending AJAX request to check favorite status:', checkFavoriteUrl);

        $.ajax({
            url: checkFavoriteUrl,
            type: 'GET',
            data: {
                movie_id: movieId
            },
            beforeSend: function () {
                console.log('Sending check favorite request');
            },
            success: function (response) {
                console.log('Check favorite status response:', response);
                if (response.is_favorite) {
                    favoriteBtn.addClass('active');
                    favoriteBtn.find('i').removeClass('far').addClass('fas');
                    console.log('Movie is favorite, updated UI');
                } else {
                    favoriteBtn.removeClass('active');
                    favoriteBtn.find('i').removeClass('fas').addClass('far');
                    console.log('Movie is not favorite, updated UI');
                }
            },
            error: function (xhr, status, error) {
                console.error('Check favorite error:', error);
                console.error('Response:', xhr.responseText);
            }
        });
    }

    // Hàm toggle yêu thích
    function toggleFavorite() {
        if (typeof toggleFavoriteUrl === 'undefined' || typeof csrfToken === 'undefined') {
            console.error('toggleFavoriteUrl or csrfToken not defined');
            return;
        }

        console.log('Toggling favorite for movie ID:', movieId);
        console.log('Request URL:', toggleFavoriteUrl);

        $.ajax({
            url: toggleFavoriteUrl,
            type: 'POST',
            data: {
                movie_id: movieId,
                _token: csrfToken
            },
            beforeSend: function () {
                console.log('Sending toggle favorite request');
            },
            success: function (response) {
                console.log('Toggle favorite response:', response);

                if (response.status === 'added') {
                    favoriteBtn.addClass('active');
                    favoriteBtn.find('i').removeClass('far').addClass('fas');
                    console.log('Added to favorites, updated UI');
                    if (typeof showToast === 'function') {
                        showToast(response.message);
                    }
                } else {
                    favoriteBtn.removeClass('active');
                    favoriteBtn.find('i').removeClass('fas').addClass('far');
                    console.log('Removed from favorites, updated UI');
                    if (typeof showToast === 'function') {
                        showToast(response.message);
                    }
                }
            },
            error: function (xhr, status, error) {
                console.error('Toggle favorite error:', error);
                console.error('Response status:', xhr.status);
                console.error('Response text:', xhr.responseText);

                if (typeof showToast === 'function') {
                    showToast('Có lỗi xảy ra, vui lòng thử lại sau');
                }
            }
        });
    }
});
