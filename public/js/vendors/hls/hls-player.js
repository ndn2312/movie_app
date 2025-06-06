/**
 * HLS Player với Plyr và hỗ trợ nhiều độ phân giải
 * Tích hợp HLS.js và Plyr để xử lý video m3u8 với nhiều chất lượng
 */

class HLSPlayer {
    constructor(videoElement, options = {}) {
        this.videoElement = videoElement;
        this.options = options;
        this.hls = null;
        this.player = null;
        this.currentQuality = 'auto';
        this.qualityLevels = [];
        this.autoQuality = true;
        
        // Cấu hình mặc định
        this.defaultOptions = {
            controls: [
                'play-large', 'play', 'rewind', 'fast-forward', 'progress', 
                'current-time', 'duration', 'mute', 'volume', 'settings', 
                'pip', 'airplay', 'fullscreen'
            ],
            settings: ['captions', 'quality', 'speed'],
            seekTime: 10,
            keyboard: { focused: false, global: false },
            displayDuration: true,
            invertTime: false,
            toggleInvert: false,
            tooltips: { controls: true, seek: true },
            i18n: {
                qualityLabel: 'Chất lượng',
                quality: {
                    auto: 'Tự động'
                }
            }
        };

        // Ghép các tùy chọn với mặc định
        this.mergedOptions = {...this.defaultOptions, ...this.options};

        // Khởi tạo
        this.init();
    }

    init() {
        // Kiểm tra hỗ trợ
        if (!Hls.isSupported()) {
            console.error('Trình duyệt không hỗ trợ HLS.js');
            this.initFallback();
            return;
        }

        // Tạo đối tượng HLS
        this.hls = new Hls();
        
        // Gắn video với HLS
        this.hls.attachMedia(this.videoElement);
        
        // Bắt sự kiện HLS
        this.setupHLSEvents();
    }

    // Thiết lập các sự kiện HLS
    setupHLSEvents() {
        // Sự kiện khi manifest được phân tích
        this.hls.on(Hls.Events.MANIFEST_PARSED, (event, data) => {
            this.onManifestParsed(data);
        });

        // Sự kiện khi chuyển đổi cấp độ (chất lượng)
        this.hls.on(Hls.Events.LEVEL_SWITCHED, (event, data) => {
            this.onLevelSwitched(data);
        });

        // Sự kiện khi cấp độ đã được tải
        this.hls.on(Hls.Events.LEVEL_LOADED, (event, data) => {
            this.onLevelLoaded(data);
        });

        // Xử lý lỗi
        this.hls.on(Hls.Events.ERROR, (event, data) => {
            this.onError(data);
        });
    }

    // Xử lý khi manifest được phân tích
    onManifestParsed(data) {
        console.log('HLS Manifest đã được phân tích', data);
        
        // Lấy danh sách các cấp độ chất lượng
        const levels = data.levels;
        this.qualityLevels = levels.map((level, index) => {
            const height = level.height || 0;
            let label = height ? height + 'p' : 'Không xác định';
            
            // Thêm bitrate nếu có
            if (level.bitrate) {
                const bitrate = Math.round(level.bitrate / 1000);
                label += ` (${bitrate}kbps)`;
            }
            
            return {
                id: index,
                height: height,
                bitrate: level.bitrate,
                label: label,
                selected: index === this.hls.currentLevel
            };
        });
        
        // Thêm tùy chọn Auto
        this.qualityLevels.unshift({
            id: -1,
            height: 0,
            bitrate: 0,
            label: 'Tự động',
            selected: this.hls.currentLevel === -1 || this.hls.currentLevel === null
        });
        
        // Khởi tạo Plyr sau khi phân tích được manifest
        this.initPlyr();
    }

    // Khởi tạo Plyr
    initPlyr() {
        // Tạo danh sách chất lượng cho Plyr
        const qualityOptions = this.qualityLevels.map(level => ({
            value: level.id,
            label: level.label
        }));
        
        // Tùy chỉnh cấu hình Plyr
        this.mergedOptions.quality = {
            default: -1,
            options: qualityOptions.map(option => option.value),
            forced: true,
            onChange: (quality) => this.onQualityChange(quality)
        };
        
        // Khởi tạo Plyr
        this.player = new Plyr(this.videoElement, this.mergedOptions);
        
        // Gắn sự kiện cho Plyr
        this.setupPlyrEvents();
    }

    // Thiết lập sự kiện cho Plyr
    setupPlyrEvents() {
        // Khi Plyr sẵn sàng
        this.player.on('ready', () => {
            console.log('Plyr sẵn sàng với HLS');
            
            // Thêm labels chất lượng vào Plyr
            this.updateQualityLabels();
            
            // Cập nhật UI chất lượng
            this.updateQualityUI();
            
            // Trigger sự kiện ready
            if (this.options.onReady) {
                this.options.onReady(this.player, this.hls);
            }
        });
        
        // Các sự kiện khác của Plyr có thể thêm ở đây
    }

    // Cập nhật nhãn chất lượng
    updateQualityLabels() {
        const plyrQualityMenu = this.player.elements.settings.panels.quality;
        if (!plyrQualityMenu) return;
        
        // Tìm các radio button chất lượng
        const radios = plyrQualityMenu.querySelectorAll('input[type="radio"]');
        
        radios.forEach(radio => {
            const value = Number(radio.value);
            const label = this.qualityLevels.find(level => level.id === value)?.label || 'Unknown';
            
            // Tìm label tương ứng với radio
            const labelElement = radio.parentElement.querySelector('span');
            if (labelElement) {
                labelElement.textContent = label;
            }
        });
    }

    // Xử lý khi thay đổi chất lượng
    onQualityChange(quality) {
        console.log('Đã thay đổi chất lượng', quality);
        
        // Chuyển đổi sang số nguyên
        const qualityLevel = parseInt(quality, 10);
        
        // Đặt cấp độ chất lượng HLS
        this.hls.currentLevel = qualityLevel;
        
        // Cập nhật UI
        this.updateQualityUI();
        
        // Lưu trạng thái tự động
        this.autoQuality = qualityLevel === -1;
        
        // Lưu chất lượng hiện tại
        this.currentQuality = qualityLevel === -1 ? 'auto' : this.qualityLevels.find(l => l.id === qualityLevel)?.label || 'auto';
        
        // Hiển thị thông báo
        this.showQualityNotification();
    }

    // Cập nhật UI chất lượng
    updateQualityUI() {
        const currentLevel = this.hls.currentLevel;
        const levelInfo = currentLevel === -1 
            ? 'Tự động' 
            : this.qualityLevels.find(l => l.id === currentLevel)?.label || 'Không xác định';
            
        console.log('Chất lượng hiện tại:', levelInfo);
    }

    // Hiển thị thông báo khi thay đổi chất lượng
    showQualityNotification() {
        if (this.player && this.player.elements && this.player.elements.container) {
            // Xóa thông báo cũ nếu có
            const oldNotification = this.player.elements.container.querySelector('.quality-change-notification');
            if (oldNotification) {
                oldNotification.remove();
            }
            
            // Tạo thông báo mới
            const notification = document.createElement('div');
            notification.className = 'quality-change-notification';
            notification.textContent = `Chất lượng: ${this.currentQuality}`;
            notification.style.cssText = `
                position: absolute;
                top: 20px;
                left: 50%;
                transform: translateX(-50%);
                background-color: rgba(0, 0, 0, 0.7);
                color: white;
                padding: 8px 15px;
                border-radius: 4px;
                font-size: 14px;
                font-weight: bold;
                z-index: 10;
                transition: opacity 0.5s;
                opacity: 0;
            `;
            
            // Thêm vào container
            this.player.elements.container.appendChild(notification);
            
            // Hiển thị
            setTimeout(() => {
                notification.style.opacity = '1';
            }, 10);
            
            // Tự động ẩn sau 2 giây
            setTimeout(() => {
                notification.style.opacity = '0';
                setTimeout(() => {
                    notification.remove();
                }, 500);
            }, 2000);
        }
    }

    // Xử lý khi cấp độ chất lượng được chuyển đổi
    onLevelSwitched(data) {
        const newLevel = data.level;
        console.log('Đã chuyển sang cấp độ chất lượng:', newLevel);
        
        // Cập nhật UI nếu đang ở chế độ tự động
        if (this.autoQuality && newLevel !== -1) {
            const levelInfo = this.qualityLevels.find(l => l.id === newLevel)?.label || 'Không xác định';
            this.currentQuality = `Tự động (${levelInfo})`;
            this.updateQualityUI();
        }
    }

    // Xử lý khi cấp độ được tải
    onLevelLoaded(data) {
        console.log('Cấp độ đã được tải:', data.level);
    }

    // Xử lý lỗi
    onError(data) {
        console.error('Lỗi HLS:', data);
        
        // Thử khôi phục từ lỗi nếu có thể
        if (data.fatal) {
            switch(data.type) {
                case Hls.ErrorTypes.NETWORK_ERROR:
                    console.log('Lỗi mạng, đang thử kết nối lại...');
                    this.hls.startLoad();
                    break;
                case Hls.ErrorTypes.MEDIA_ERROR:
                    console.log('Lỗi media, đang thử khôi phục...');
                    this.hls.recoverMediaError();
                    break;
                default:
                    // Lỗi không thể khôi phục
                    this.destroy();
                    this.initFallback();
                    break;
            }
        }
    }

    // Phương thức tải nguồn video
    loadSource(videoUrl) {
        if (this.hls) {
            console.log('Đang tải nguồn HLS:', videoUrl);
            this.hls.loadSource(videoUrl);
        }
    }

    // Phương thức hủy player
    destroy() {
        if (this.player) {
            this.player.destroy();
        }
        
        if (this.hls) {
            this.hls.destroy();
        }
    }

    // Phương thức fallback khi HLS không được hỗ trợ
    initFallback() {
        console.log('Sử dụng phương thức dự phòng cho HLS');
        if (this.videoElement.canPlayType('application/vnd.apple.mpegurl')) {
            // Safari hỗ trợ HLS natively
            this.player = new Plyr(this.videoElement, this.mergedOptions);
        } else {
            console.error('Trình duyệt không hỗ trợ phát HLS');
        }
    }
}

// Hàm tiện ích để khởi tạo HLS Player
function initializeHLSPlayer(videoElement, videoSource, options = {}) {
    const hlsPlayer = new HLSPlayer(videoElement, options);
    hlsPlayer.loadSource(videoSource);
    return hlsPlayer;
}

// Export ra window object để có thể sử dụng từ bên ngoài
window.HLSPlayer = HLSPlayer;
window.initializeHLSPlayer = initializeHLSPlayer;
