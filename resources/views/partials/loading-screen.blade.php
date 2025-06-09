<style>
    /* Modern Loader Base */
    .page-loader {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        z-index: 9999;
        display: flex;
        justify-content: center;
        align-items: center;
        background: radial-gradient(ellipse at center, #111827 0%, #0a0f18 100%);
        opacity: 0;
        visibility: hidden;
        transition: opacity 0.3s ease, visibility 0.3s ease;
    }

    .page-loader.active {
        opacity: 1;
        visibility: visible;
    }

    .loader-wrapper {
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        width: 90%;
        max-width: 700px;
    }

    /* Anniversary Banner */
    .anniversary-banner {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        margin-bottom: 50px;
        position: relative;
        padding: 25px;
        background-color: rgba(10, 15, 24, 0.7);
        border-radius: 12px;
        box-shadow: 0 15px 35px rgba(0, 0, 0, 0.3);
        border: 1px solid rgba(255, 255, 255, 0.05);
        backdrop-filter: blur(10px);
    }

    /* Flag Animation */
    .flag-animation {
        position: relative;
        width: 200px;
        height: 180px;
        margin-bottom: 30px;
    }

    .flag-wrapper {
        position: absolute;
        top: 0;
        left: 40px;
        width: 120px;
        height: 80px;
        perspective: 600px;
        transform-style: preserve-3d;
    }

    .flag {
        width: 100%;
        height: 100%;
        background-color: #da251d;
        position: relative;
        transform-origin: left center;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
        animation: flagWave 4s ease-in-out infinite;
        border-radius: 2px;
    }

    .flag:after {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: linear-gradient(-45deg,
                rgba(255, 255, 255, 0.15) 0%,
                rgba(255, 255, 255, 0) 40%,
                rgba(255, 255, 255, 0) 60%,
                rgba(255, 255, 255, 0.15) 100%);
    }

    .flag-star {
        position: absolute;
        width: 40px;
        height: 40px;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        clip-path: polygon(50% 0%,
                61% 35%,
                98% 35%,
                68% 57%,
                79% 91%,
                50% 70%,
                21% 91%,
                32% 57%,
                2% 35%,
                39% 35%);
        background-color: #ffff00;
    }

    .flag-pole {
        position: absolute;
        width: 4px;
        height: 140px;
        left: 40px;
        top: 0;
        background: linear-gradient(to right, #888, #ddd, #888);
        border-radius: 2px;
        z-index: -1;
    }

    .flag-base {
        position: absolute;
        width: 60px;
        height: 8px;
        bottom: 32px;
        left: 12px;
        background: linear-gradient(to bottom, #555, #333);
        border-radius: 4px;
        z-index: -1;
    }

    /* Text Styling */
    .anniversary-content {
        text-align: center;
        width: 100%;
    }

    .top-line,
    .bottom-line {
        height: 2px;
        width: 80%;
        margin: 15px auto;
        background: linear-gradient(to right, transparent, #da251d, transparent);
    }

    .celebration-title {
        font-size: 18px;
        color: #da251d;
        font-weight: 700;
        letter-spacing: 2px;
        margin-bottom: 10px;
        text-transform: uppercase;
        font-family: 'Be Vietnam Pro', sans-serif;
    }

    .main-title {
        font-size: 32px;
        background: linear-gradient(45deg, yellow 50%, red 50%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        font-weight: 800;
        letter-spacing: 1px;
        line-height: 1.2;
        margin: 0 0 15px;
        text-transform: uppercase;
        font-family: 'Be Vietnam Pro', sans-serif;
        text-shadow: none;
    }

    .date-container {
        position: relative;
        display: inline-block;
        margin: 10px 0;
    }

    .anniversary-date {
        font-size: 20px;
        color: #b3b0ad;
        font-weight: 600;
        letter-spacing: 1px;
        padding: 0 15px;
        position: relative;
        font-family: 'Roboto', sans-serif;
    }

    .anniversary-date:before,
    .anniversary-date:after {
        content: '';
        position: absolute;
        width: 30px;
        height: 1px;
        background-color: rgba(255, 255, 0, 0.5);
        top: 50%;
        transform: translateY(-50%);
    }

    .anniversary-date:before {
        left: -40px;
    }

    .anniversary-date:after {
        right: -40px;
    }

    /* Site Branding */
    .site-branding {
        margin: 10px 0 25px;
    }

    .site-name {
        font-size: 24px;
        font-weight: 700;
        color: #fff;
        letter-spacing: 1px;
        font-family: 'Roboto', sans-serif;
        text-transform: uppercase;
        position: relative;
    }

    .site-name:after {
        content: "Phim";
        color: #da251d;
    }

    /* Loader Animation */
    .loader-animation {
        width: 50px;
        height: 50px;
        position: relative;
        margin: 0 auto;
    }

    .circular {
        animation: rotate 2s linear infinite;
        height: 100%;
        width: 100%;
        transform-origin: center center;
    }

    .path {
        stroke: #da251d;
        stroke-dasharray: 89, 200;
        stroke-dashoffset: 0;
        stroke-linecap: round;
        animation: dash 1.5s ease-in-out infinite;
    }

    /* Keyframes for Animations */
    @keyframes flagWave {

        0%,
        100% {
            transform: rotateY(-8deg) rotateZ(2deg);
        }

        50% {
            transform: rotateY(8deg) rotateZ(-2deg);
        }
    }

    @keyframes rotate {
        100% {
            transform: rotate(360deg);
        }
    }

    @keyframes dash {
        0% {
            stroke-dasharray: 1, 200;
            stroke-dashoffset: 0;
        }

        50% {
            stroke-dasharray: 89, 200;
            stroke-dashoffset: -35;
        }

        100% {
            stroke-dasharray: 89, 200;
            stroke-dashoffset: -124;
        }
    }

    /* Media Queries for Responsiveness */
    @media (max-width: 768px) {
        .main-title {
            font-size: 24px;
        }

        .celebration-title {
            font-size: 16px;
        }

        .anniversary-date {
            font-size: 18px;
        }

        .flag-animation {
            width: 160px;
            height: 150px;
        }

        .flag-wrapper {
            width: 100px;
            height: 67px;
        }
    }

    @media (max-width: 480px) {
        .main-title {
            font-size: 20px;
        }

        .anniversary-date {
            font-size: 16px;
        }

        .flag-animation {
            width: 140px;
            height: 130px;
        }

        .flag-wrapper {
            width: 90px;
            height: 60px;
        }

        .anniversary-banner {
            padding: 20px 15px;
        }
    }
</style>
<!-- Màn hình loading -->
<div id="page-loader" class="page-loader">
    <div class="loader-wrapper">
        <div class="anniversary-banner">
            <div class="flag-animation">
                <div class="flag-wrapper">
                    <div class="flag">
                        <div class="flag-star"></div>
                    </div>
                </div>
                <div class="flag-pole"></div>
                <div class="flag-base"></div>
            </div>

            <div class="anniversary-content">
                <div class="top-line"></div>
                <h2 class="celebration-title">CHÀO MỪNG</h2>
                <h1 class="main-title">80 NĂM CÁCH MẠNG THÁNG 8 VÀ QUỐC KHÁNH 2/9</h1>
                <div class="date-container">
                    <span class="anniversary-date">02/09/1945 - 02/09/2025</span>
                </div>
                <div class="bottom-line"></div>
            </div>
        </div>

        <div class="site-branding">
            <span class="site-name">Ndn</span>
        </div>

        <div class="loader-animation">
            <svg class="circular" viewBox="25 25 50 50">
                <circle class="path" cx="50" cy="50" r="20" fill="none" stroke-width="3" stroke-miterlimit="10" />
            </svg>
        </div>
    </div>
</div>
<!-- Script điều khiển màn hình loading -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
    const pageLoader = document.getElementById('page-loader');
    let isNavigatingAway = false;

    // Track navigation state to prevent showing loader on back/forward
    window.addEventListener('popstate', function() {
        // Don't show loader for browser history navigation
        isNavigatingAway = false;
    });

    // Show loader for all navigation EXCEPT movie player links
    document.querySelectorAll('a:not([target="_blank"]):not([href^="#"]):not([href^="javascript:"])').forEach(link => {
        link.addEventListener('click', function(e) {
            const href = this.getAttribute('href');
            
            // Kiểm tra nếu đây là liên kết xem phim hoặc chuyển tập
            const isMoviePlayerLink = href && (href.includes('/xem-phim/'));
            
            if (href && href !== '#' && !href.startsWith('#') && !href.startsWith('javascript:') && !isMoviePlayerLink) {
                e.preventDefault();
                isNavigatingAway = true;
                
                // Fade in loader
                pageLoader.style.display = 'flex';
                setTimeout(() => {
                    pageLoader.classList.add('active');
                }, 10);

                // Navigate after showing animation
                setTimeout(function() {
                    window.location.href = href;
                }, 2000);
            }
        });
    });

    // Form submissions
    document.querySelectorAll('form:not(.ajax-form):not(#loginForm):not(#registerForm):not(#account-form)').forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            isNavigatingAway = true;
            
            pageLoader.style.display = 'flex';
            setTimeout(() => {
                pageLoader.classList.add('active');
            }, 10);

            setTimeout(() => {
                this.submit();
            }, 2000);
        });
    });

    // Hide loader on page load
    window.addEventListener('load', function() {
        // Only hide if we're not actively navigating away
        if (!isNavigatingAway) {
            setTimeout(() => {
                pageLoader.classList.remove('active');
                setTimeout(() => {
                    pageLoader.style.display = 'none';
                }, 300);
            }, 300);
        }
    });
    
    // Additional fix for handling browser back/forward navigation
    window.addEventListener('pageshow', function(event) {
        // Check if the page is being loaded from cache (back/forward navigation)
        if (event.persisted) {
            pageLoader.classList.remove('active');
            setTimeout(() => {
                pageLoader.style.display = 'none';
            }, 300);
        }
    });
});
</script>