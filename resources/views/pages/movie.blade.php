@extends('layout')
@section('content')
<style>
   .tag-base {
      display: inline-block;
      padding: 4px 10px;
      font-size: 11px;
      font-weight: 600;
      border-radius: 20px;
      color: #FFFFFF;
      box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
      text-transform: uppercase;
      letter-spacing: 0.5px;
      transition: all 0.3s ease;
   }

   /* ==================== POSITION STYLES ==================== */
   .status {
      position: absolute;
      top: 10px;
      left: 10px;
      z-index: 10;
   }



   /* ==================== TAG STYLES ==================== */
   .trailer-tag {
      background: linear-gradient(135deg, #e304af 0%, #dc07d5 100%);
   }

   .fullhd-tag {
      background: linear-gradient(135deg, #ff0000 0%, #aa0000 100%);
   }

   .hd-tag {
      background: linear-gradient(135deg, #03f5fd 0%, #0f6060 100%);
   }

   .sd-tag {
      background: linear-gradient(135deg, #0f3460 0%, #533483 100%);
   }

   .hdcam-tag {
      background: linear-gradient(135deg, #533483 0%, #9896f1 100%);
   }

   .cam-tag {
      background: linear-gradient(135deg, #9896f1 0%, #7579e7 100%);
   }

   .thuyetminh-tag {
      background: linear-gradient(135deg, #008000 0%, #005000 100%);
   }

   .phude-tag {
      background: linear-gradient(135deg, #666666 0%, #333333 100%);
   }

   .ss-tag {
      background: linear-gradient(135deg, #034ba8 30%, #04c1e7 70%);
   }

   .sotap-tag {
      background: linear-gradient(135deg, #ea8d40 30%, #e70404 70%);
   }

   /* ==================== TITLE HOVER EFFECTS ==================== */
   .halim-post-title .entry-title {
      position: relative;
      transition: all 0.3s ease;
      font-weight: 700;
      font-size: 15px;
      margin-bottom: 3px;
      line-height: 1.3;
   }

   .halim-thumb:hover .entry-title {
      color: #06f2e6 !important;
      transform: translateY(-2px);
      font-weight: 700 !important;
   }

   .halim-post-title .original_title {
      position: relative;
      transition: all 0.3s ease;
      opacity: 0.7;
      font-weight: 600;
      font-size: 13px;
      line-height: 1.2;
   }

   .halim-thumb:hover .original_title {
      color: #ffcf4a !important;
      opacity: 1;
   }

   /* ==================== PLAY BUTTON - UPDATED ==================== */
   .play-button {
      position: absolute;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%) scale(0.8);
      width: 60px;
      height: 60px;
      background: rgba(4, 193, 231, 0.3);
      backdrop-filter: blur(10px);
      border-radius: 50%;
      display: flex;
      justify-content: center;
      align-items: center;
      color: white;
      opacity: 0;
      transition: all 0.4s ease;
      z-index: 3;
      border: 2px solid rgba(255, 255, 255, 0.8);
   }

   .halim-thumb:hover .play-button {
      opacity: 1;
      transform: translate(-50%, -50%) scale(1);
      background: rgba(4, 193, 231, 0.7);
   }

   .play-button i {
      color: white;
      font-size: 22px;
      margin-left: 3px;
   }

   .play-button::after {
      content: '';
      position: absolute;
      width: 100%;
      height: 100%;
      border-radius: 50%;
      border: 2px solid rgba(255, 255, 255, 0.8);
      animation: pulse-button 2s infinite;
   }

   @keyframes pulse-button {
      0% {
         transform: scale(1);
         opacity: 0.8;
      }

      70% {
         transform: scale(1.4);
         opacity: 0;
      }

      100% {
         transform: scale(1.4);
         opacity: 0;
      }
   }

   /* ==================== MOVIE THUMBNAIL EFFECTS ==================== */
   .halim-thumb figure {
      border-radius: 8px;
      overflow: hidden;
      transition: all 0.3s ease;
      box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
   }

   .halim-thumb figure img {
      transition: transform 0.4s ease;
   }

   .halim-thumb:hover figure {
      box-shadow: 0 5px 15px rgba(0, 0, 0, 0.15);
   }

   .halim-thumb:hover figure img {
      transform: scale(1.05);
   }

   /* ==================== MOVIE CONTAINER ==================== */
   .halim-item {
      margin-bottom: 20px;
      transition: all 0.3s ease;
      border-radius: 8px;
      overflow: hidden;
      background: #fff;
      box-shadow: 0 3px 10px rgba(0, 0, 0, 0.08);
   }

   .halim-item:hover {
      transform: translateY(-5px);
      box-shadow: 0 8px 15px rgba(0, 0, 0, 0.15);
   }

   /* ==================== SECTION HEADINGS ==================== */
   /* Common style for all section headings */
   .section-heading,
   .section-bar {
      position: relative;
      margin-bottom: 20px;
      border-bottom: 2px solid #f0f0f0;
      padding: 10px 0;
   }

   .section-heading span.h-text,
   .section-title span {
      display: inline-block;
      padding: 8px 16px;
      color: white;
      font-weight: 700;
      font-size: 16px;
      position: relative;
      background: linear-gradient(135deg, #03f5fd 0%, #0f6060 100%);
      border-radius: 4px 4px 0 0;
   }

   /* PHIM HOT specific styles */
   #halim_related_movies-2xx .section-bar {
      border-bottom: 2px solid #e304af;
   }

   #halim_related_movies-2xx .section-title {
      margin: 0;
   }

   #halim_related_movies-2xx .section-title span {
      background: linear-gradient(135deg, #e304af 0%, #dc07d5 100%);
      font-size: 16px;
      padding: 8px 16px;
   }

   /* Container for PHIM HOT carousel */
   #halim_related_movies-2 {
      padding: 15px 0;
   }

   /* Navigation buttons for carousel */
   #halim_related_movies-2 .owl-nav button {
      background: linear-gradient(135deg, #e304af 0%, #dc07d5 100%) !important;
      width: 36px;
      height: 36px;
      border-radius: 50% !important;
      line-height: 36px;
      color: white !important;
      font-size: 18px !important;
      opacity: 0.8;
      transition: all 0.3s ease;
   }

   #halim_related_movies-2 .owl-nav button:hover {
      opacity: 1;
   }

   /* ==================== CATEGORY SECTION STYLES ==================== */
   /* Container for movie grid */
   #halim-advanced-widget-2-ajax-box {
      display: flex;
      flex-wrap: wrap;
      padding: 15px 0;
   }

   /* Customize category section headings */
   #halim-advanced-widget-2 .section-heading span.h-text {
      background: linear-gradient(135deg, #0f3460 0%, #533483 100%);
   }

   /* Alternate colors for different category sections */
   #halim-advanced-widget-2:nth-of-type(odd) .section-heading span.h-text {
      background: linear-gradient(135deg, #0f3460 0%, #533483 100%);
   }

   #halim-advanced-widget-2:nth-of-type(even) .section-heading span.h-text {
      background: linear-gradient(135deg, #034ba8 0%, #04c1e7 100%);
   }

   /* Styles for movie action buttons */
   .movie-action-button {
      display: block;
      width: 100%;
      padding: 12px 15px;
      margin-bottom: 10px;
      border: none;
      border-radius: 30px;
      font-size: 14px;
      font-weight: 700;
      text-transform: uppercase;
      letter-spacing: 1px;
      text-align: center;
      transition: all 0.3s ease;
      position: relative;
      overflow: hidden;
      z-index: 1;
      box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
   }

   .movie-action-button:before {
      content: '';
      position: absolute;
      top: 0;
      left: -100%;
      width: 100%;
      height: 100%;
      background: rgba(255, 255, 255, 0.1);
      transition: all 0.4s ease;
      z-index: -1;
   }

   .movie-action-button:hover:before {
      left: 0;
   }

   .movie-action-button i {
      margin-right: 8px;
      font-size: 16px;
   }

   .movie-action-button:active {
      transform: scale(0.97);
   }

   /* Watch Movie Button */
   .watch-movie-btn {
      background: linear-gradient(135deg, #ff6b6b 0%, #ee0979 100%);
      color: #ffffff;
   }

   .watch-movie-btn:hover {
      background: linear-gradient(135deg, #ee0979 0%, #ff6b6b 100%);
      box-shadow: 0 5px 20px rgba(238, 9, 121, 0.4);
      color: #ffffff;
      text-decoration: none;
   }

   /* Watch Trailer Button */
   .watch-trailer-btn {
      background: linear-gradient(135deg, #2b5876 0%, #4e4376 100%);
      color: #ffffff;
   }

   .watch-trailer-btn:hover {
      background: linear-gradient(135deg, #4e4376 0%, #2b5876 100%);
      box-shadow: 0 5px 20px rgba(78, 67, 118, 0.4);
      color: #ffffff;
      text-decoration: none;
   }

   /* Button container */
   .movie-action-buttons {
      margin-top: 15px;
   }

   @keyframes pulse {
      0% {
         box-shadow: 0 0 0 0 rgba(238, 9, 121, 0.7);
      }

      70% {
         box-shadow: 0 0 0 10px rgba(238, 9, 121, 0);
      }

      100% {
         box-shadow: 0 0 0 0 rgba(238, 9, 121, 0);
      }
   }

   .watch-movie-btn {
      animation: pulse 2s infinite;
   }

   /* CSS đánh giá */
   /* Rating Button Styles */
   .rating-button-container {
      display: flex;
      align-items: center;
      top: 30px;
      margin-top: 25px;
      gap: 15px;
   }

   .current-rating {
      background: rgba(0, 0, 0, 0.2);
      padding: 8px 12px;
      border-radius: 20px;
      color: #ffed4d;
      font-weight: 700;
      display: flex;
      align-items: center;
      gap: 5px;
   }

   .current-rating i {
      color: #ffcc00;
   }

   .current-rating small {
      color: #aaa;
      font-weight: normal;
      margin-left: 5px;
   }

   .rate-movie-btn {
      background: linear-gradient(135deg, #00ffdda8 0%, #00e5ffc6 100%);
      color: #fff !important;
      border: none !important;
      padding: 10px 18px !important;
      border-radius: 30px !important;
      font-weight: 600 !important;
      box-shadow: 0 4px 15px rgba(255, 204, 0, 0.3) !important;
      transition: all 0.3s ease !important;
      margin: 0 !important;
   }

   .rate-movie-btn:hover {
      background: linear-gradient(135deg, #00ffd5a8 0%, #00ffdda6 100%);
      transform: translateY(-2px);
      box-shadow: 0 6px 20px rgba(0, 255, 251, 0.4) !important;
   }

   /* Modal Styling */
   .modal-content {
      border-radius: 10px;
      border: none;
      box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
      background: #111824;
   }

   .modal-header {
      border-bottom: 1px solid rgba(255, 255, 255, 0.1);
      padding: 15px 20px;
   }

   .modal-title {
      color: #ffed4d;
      font-weight: 600;
      font-size: 16px;
   }

   .modal-title i {
      margin-right: 8px;
   }

   .modal-body {
      padding: 25px;
   }

   .modal-footer {
      border-top: 1px solid rgba(255, 255, 255, 0.1);
      padding: 15px 20px;
   }

   .rating-system-popup {
      background: rgba(0, 0, 0, 0.2);
      border-radius: 12px;
      padding: 20px;
      text-align: center;
   }

   .rating-system-popup .rating-header {
      display: flex;
      flex-direction: column;
      align-items: center;
      margin-bottom: 20px;
   }

   .movie-poster-small {
      width: 120px;
      height: 175px;
      margin-bottom: 15px;
      border-radius: 8px;
      overflow: hidden;
      box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
   }

   .movie-poster-small img {
      width: 100%;
      height: 100%;
      object-fit: cover;
   }

   .rating-system-popup .rating-stats {
      margin: 10px 0;
   }

   .rating-system-popup .average {
      font-size: 24px;
      font-weight: 700;
      color: #ffed4d;
   }

   .rating-system-popup .stars-container {
      margin: 25px 0;
      display: flex;
      justify-content: center;
   }

   .rating-system-popup .star {
      font-size: 35px;
      margin: 0 10px;
      cursor: pointer;
      transition: all 0.2s ease;
      position: relative;
   }

   .rating-system-popup .star:hover {
      transform: scale(1.2);
   }

   .rating-system-popup .star i {
      color: #ccc;
   }

   .rating-system-popup .star.active i,
   .rating-system-popup .star.hover i {
      color: #ffcc00;
   }

   .rating-system-popup .rating-feedback {
      padding: 12px;
      margin-top: 15px;
      border-radius: 8px;
      font-size: 14px;
      display: none;
   }

   .rating-system-popup .rating-feedback.success {
      background: rgba(76, 175, 80, 0.1);
      color: #4CAF50;
      border: 1px solid rgba(76, 175, 80, 0.3);
   }

   .rating-system-popup .rating-feedback.error {
      background: rgba(244, 67, 54, 0.1);
      color: #F44336;
      border: 1px solid rgba(244, 67, 54, 0.3);
   }

   .rating-tooltip {
      position: absolute;
      top: -25px;
      left: 50%;
      transform: translateX(-50%);
      background: rgba(0, 0, 0, 0.8);
      color: white;
      padding: 2px 6px;
      border-radius: 3px;
      font-size: 11px;
      opacity: 0;
      visibility: hidden;
      transition: all 0.2s;
   }

   .star:hover .rating-tooltip {
      opacity: 1;
      visibility: visible;
   }

   .close {
      color: #ffffff;
      opacity: 0.8;
   }

   .close:hover {
      color: #ffffff;
      opacity: 1;
   }

   /* Định dạng hiển thị lượt xem */
   .current-views {
      background: rgba(0, 0, 0, 0.2);
      padding: 8px 12px;
      border-radius: 20px;
      color: #ffffff;
      font-weight: 700;
      display: flex;
      align-items: center;
      gap: 5px;
      margin: 0 10px;
   }

   .current-views i {
      color: #4aacff;
   }

   .current-views small {
      color: #aaa;
      font-weight: normal;
      margin-left: 5px;
   }

   .view-counter {
      transition: all 0.3s ease;
   }

   .view-counter.updated {
      animation: pulse-count 1s;
      color: #4aacff;
   }

   /* Hiệu ứng nổi bật cho số lượt xem */
   .view-counter {
      font-weight: 800;
      color: #06f2e6;
      font-size: 1.1em;
      transition: all 0.3s ease;
   }

   .current-views:hover .view-counter {
      text-shadow: 0 0 10px rgba(6, 242, 230, 0.7);
      transform: scale(1.05);
   }

   .current-views:hover i {
      animation: eye-pulse 1s infinite;
   }

   /* Social Action Bar Styles */
   .social-action-bar-container {
      margin-top: 45px;
      width: 100%;
      position: relative;
      z-index: 5;
   }

   .social-action-bar {
      display: flex;
      justify-content: space-between;
      background: rgba(20, 30, 48, 0.65);
      backdrop-filter: blur(10px);
      margin: 0;
      border-radius: 12px;
      overflow: hidden;
      box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
      position: relative;
   }

   .social-action-button {
      flex: 1;
      padding: 15px 10px;
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      color: #fff;
      transition: all 0.3s ease;
      border: none;
      background: transparent;
      cursor: pointer;
      position: relative;
      overflow: hidden;
   }

   .social-action-button::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      height: 0;
      background: rgba(255, 255, 255, 0.1);
      transition: height 0.3s ease;
   }

   .social-action-button:hover::before {
      height: 100%;
   }

   .social-action-button i {
      font-size: 22px;
      margin-bottom: 8px;
      transition: transform 0.3s ease;
   }

   .social-action-button span {
      font-size: 13px;
      font-weight: 500;
   }

   .social-action-button:hover i {
      transform: scale(1.2);
   }

   .social-action-button.favorite i {
      color: #ff5e94;
   }



   .social-action-button.share i {
      color: #04c1e7;
   }

   .social-action-button.comment i {
      color: #9d71fe;
   }

   /* Active state */
   .social-action-button.active {
      background: rgba(255, 255, 255, 0.08);
   }

   .social-action-button.active i {
      transform: scale(1.2);
   }

   /* Pulse animation for favorite */
   @keyframes pulse-heart {
      0% {
         transform: scale(1);
      }

      50% {
         transform: scale(1.2);
      }

      100% {
         transform: scale(1);
      }
   }

   .social-action-button.favorite.active i {
      animation: pulse-heart 1s infinite;
   }

   /* Styles for share modal options */
   .share-options-grid {
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(120px, 1fr));
      gap: 20px;
      padding: 20px;
   }

   .share-option-card {
      display: flex;
      flex-direction: column;
      align-items: center;
      text-align: center;
      transition: all 0.3s ease;
      padding: 15px;
      border-radius: 8px;
      text-decoration: none;
      color: #fff;
   }

   .share-option-card:hover {
      background: rgba(255, 255, 255, 0.1);
      transform: translateY(-5px);
   }

   .share-option-icon {
      width: 60px;
      height: 60px;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      margin-bottom: 10px;
      font-size: 24px;
      transition: all 0.3s ease;
   }

   .share-option-card:hover .share-option-icon {
      transform: scale(1.1);
      box-shadow: 0 0 15px rgba(255, 255, 255, 0.2);
   }

   .share-option-name {
      font-size: 14px;
      color: #fff;
      margin: 0;
      font-weight: 600;
   }

   /* Social Icons Colors */
   .share-option-icon.facebook {
      background: #1877F2;
   }

   .share-option-icon.twitter {
      background: #1DA1F2;
   }

   .share-option-icon.telegram {
      background: #0088cc;
   }

   .share-option-icon.whatsapp {
      background: #25D366;
   }

   .share-option-icon.copy {
      background: #ff9800;
   }

   /* Animation for each share option */
   @keyframes fadeInUp {
      from {
         opacity: 0;
         transform: translateY(20px);
      }

      to {
         opacity: 1;
         transform: translateY(0);
      }
   }

   .share-option-card {
      animation: fadeInUp 0.5s ease forwards;
      opacity: 0;
   }

   /* Add delay for each option */
   .share-options-grid .share-option-card:nth-child(1) {
      animation-delay: 0.1s;
   }

   .share-options-grid .share-option-card:nth-child(2) {
      animation-delay: 0.15s;
   }

   .share-options-grid .share-option-card:nth-child(3) {
      animation-delay: 0.2s;
   }

   .share-options-grid .share-option-card:nth-child(4) {
      animation-delay: 0.25s;
   }

   .share-options-grid .share-option-card:nth-child(5) {
      animation-delay: 0.3s;
   }

   /* Màu cho nút diễn viên */
   .social-action-button.actors i {
      color: #00e5ff;
   }

   /* Modal Danh sách diễn viên */
   .actors-modal {
      position: fixed;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
      background: rgba(0, 0, 0, 0.85);
      backdrop-filter: blur(10px);
      z-index: 9999;
      display: flex;
      align-items: center;
      justify-content: center;
      opacity: 0;
      visibility: hidden;
      transition: all 0.3s ease;
   }

   .actors-modal.active {
      opacity: 1;
      visibility: visible;
   }

   /* Thêm hiệu ứng xuất hiện cho từng thẻ diễn viên */
   @keyframes fadeInUp {
      from {
         opacity: 0;
         transform: translateY(20px);
      }

      to {
         opacity: 1;
         transform: translateY(0);
      }
   }

   .actor-card-modal {
      animation: fadeInUp 0.5s ease forwards;
      opacity: 0;
   }

   /* Thêm delay cho từng diễn viên */
   .actors-grid .actor-card-modal:nth-child(1) {
      animation-delay: 0.1s;
   }

   .actors-grid .actor-card-modal:nth-child(2) {
      animation-delay: 0.15s;
   }

   .actors-grid .actor-card-modal:nth-child(3) {
      animation-delay: 0.2s;
   }

   .actors-grid .actor-card-modal:nth-child(4) {
      animation-delay: 0.25s;
   }

   .actors-grid .actor-card-modal:nth-child(5) {
      animation-delay: 0.3s;
   }

   .actors-grid .actor-card-modal:nth-child(6) {
      animation-delay: 0.35s;
   }

   .actors-grid .actor-card-modal:nth-child(7) {
      animation-delay: 0.4s;
   }

   .actors-grid .actor-card-modal:nth-child(8) {
      animation-delay: 0.45s;
   }

   .actors-grid .actor-card-modal:nth-child(9) {
      animation-delay: 0.5s;
   }

   .actors-grid .actor-card-modal:nth-child(10) {
      animation-delay: 0.55s;
   }

   .actors-grid .actor-card-modal:nth-child(11) {
      animation-delay: 0.6s;
   }

   .actors-grid .actor-card-modal:nth-child(12) {
      animation-delay: 0.65s;
   }

   .actors-grid .actor-card-modal:nth-child(n+13) {
      animation-delay: 0.7s;
   }

   .actors-modal-content {
      width: 90%;
      max-width: 800px;
      max-height: 80vh;
      background: #171f30;
      border-radius: 12px;
      overflow: hidden;
      box-shadow: 0 10px 40px rgba(0, 0, 0, 0.5);
      transform: translateY(30px);
      transition: transform 0.4s ease;
      display: flex;
      flex-direction: column;
   }

   .actors-modal.active .actors-modal-content {
      transform: translateY(0);
   }

   .actors-modal-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 15px 20px;
      border-bottom: 1px solid rgba(255, 255, 255, 0.1);
   }

   .actors-modal-title {
      color: #ffed4d;
      font-size: 18px;
      font-weight: 700;
      display: flex;
      align-items: center;
   }

   .actors-modal-title i {
      margin-right: 10px;
      color: #00e5ff;
   }

   .actors-modal-close {
      width: 32px;
      height: 32px;
      border-radius: 50%;
      background: rgba(255, 255, 255, 0.1);
      border: none;
      color: #fff;
      display: flex;
      align-items: center;
      justify-content: center;
      cursor: pointer;
      transition: all 0.3s ease;
   }

   .actors-modal-close:hover {
      background: rgba(255, 255, 255, 0.2);
      transform: rotate(90deg);
   }

   .actors-modal-body {
      padding: 20px;
      overflow-y: auto;
   }

   .actors-grid {
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(120px, 1fr));
      gap: 20px;
   }

   .actor-card-modal {
      display: flex;
      flex-direction: column;
      align-items: center;
      text-align: center;
      transition: all 0.3s ease;
   }

   .actor-card-modal:hover {
      transform: translateY(-5px);
   }

   .actor-avatar-modal {
      width: 90px;
      height: 90px;
      border-radius: 50%;
      overflow: hidden;
      border: 2px solid rgba(0, 229, 255, 0.3);
      margin-bottom: 10px;
      transition: all 0.3s ease;
      position: relative;
   }

   .actor-avatar-modal img {
      width: 100%;
      height: 100%;
      object-fit: cover;
      transition: transform 0.4s ease;
   }

   .actor-card-modal:hover .actor-avatar-modal {
      border-color: #00e5ff;
      box-shadow: 0 0 15px rgba(0, 229, 255, 0.5);
   }

   .actor-card-modal:hover .actor-avatar-modal img {
      transform: scale(1.1);
   }

   .actor-name-modal {
      font-size: 14px;
      color: #fff;
      margin: 0 0 5px;
      font-weight: 600;
      transition: color 0.3s ease;
   }

   .actor-role-modal {
      font-size: 12px;
      color: #aaa;
      margin: 0;
   }

   .actor-card-modal:hover .actor-name-modal {
      color: #00e5ff;
   }

   .no-actors-message-modal {
      text-align: center;
      padding: 40px;
      color: #aaa;
      font-style: italic;
      grid-column: 1 / -1;
   }

   /* Modal with scrollbar styling */
   .actors-modal-body::-webkit-scrollbar {
      width: 6px;
   }

   .actors-modal-body::-webkit-scrollbar-track {
      background: rgba(255, 255, 255, 0.05);
      border-radius: 3px;
   }

   .actors-modal-body::-webkit-scrollbar-thumb {
      background: rgba(0, 229, 255, 0.3);
      border-radius: 3px;
   }

   .actors-modal-body::-webkit-scrollbar-thumb:hover {
      background: rgba(0, 229, 255, 0.5);
   }

   */ @keyframes eye-pulse {
      0% {
         transform: scale(1);
      }

      50% {
         transform: scale(1.2);
      }

      100% {
         transform: scale(1);
      }
   }


   @keyframes pulse-star {
      0% {
         transform: scale(1);
      }

      50% {
         transform: scale(1.3);
      }

      100% {
         transform: scale(1);
      }
   }

   .star.pulse i {
      animation: pulse-star 0.6s ease;
   }

   /* CSS mới cho giao diện thông tin phim */
   .film-poster-img {
      position: relative;
      border-radius: 8px;
      overflow: hidden;
      box-shadow: 0 5px 25px rgba(0, 0, 0, 0.3);
      margin-bottom: 20px;
      transition: all 0.3s ease;
   }

   .film-poster-img:hover {
      transform: translateY(-5px);
      box-shadow: 0 10px 30px rgba(0, 0, 0, 0.4);
   }

   .movie-thumb {
      width: 100%;
      height: auto;
      transition: transform 0.5s ease;
   }

   .film-poster-img:hover .movie-thumb {
      transform: scale(1.05);
   }

   .status-label {
      position: absolute;
      top: 10px;
      left: 10px;
      z-index: 10;
      display: flex;
      flex-wrap: wrap;
      gap: 5px;
   }

   .film-poster-header {
      margin-bottom: 20px;
      border-bottom: 1px solid rgba(255, 255, 255, 0.1);
      padding-bottom: 15px;
   }

   .movie-title.title-1 {
      color: #ffed4d;
      text-transform: uppercase;
      font-size: 26px;
      font-weight: 700;
      /* margin-bottom: 5px; */
      line-height: 1.3;
      text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
   }

   .movie-title.title-2 {
      color: #ccc;
      font-size: 16px;
      margin-bottom: 15px;
      font-weight: 400;
      font-style: italic;
   }

   .film-info-details {
      background: rgba(20, 30, 48, 0.5);
      border-radius: 10px;
      padding: 20px;
      box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
   }

   .info-details-group {
      margin-bottom: 20px;
   }

   .movie-info-box {
      margin-bottom: 20px;
   }

   .info-box-header {
      font-size: 18px;
      font-weight: 600;
      color: #04c1e7;
      margin-bottom: 15px;
      display: flex;
      align-items: center;
   }

   .info-box-header i {
      margin-right: 8px;
   }

   .info-box-content {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 12px;
   }

   .info-item {
      display: flex;
      flex-wrap: wrap;
      align-items: center;
      margin-bottom: 8px;
   }

   .info-label {
      color: #aaa;
      margin-right: 5px;
      font-weight: 400;
   }

   .info-value {
      color: #fff;
      font-weight: 600;
   }

   .genre-list {
      display: flex;
      flex-wrap: wrap;
      gap: 5px;
   }

   .genre-tag,
   .episode-tag {
      color: #06f2e6;
      transition: all 0.3s ease;
      text-decoration: none;
   }

   .genre-tag:hover,
   .episode-tag:hover,
   .category-link:hover,
   .country-link:hover {
      color: #ffed4d;
      text-decoration: none;
   }

   .category-link,
   .country-link {
      color: #04c1e7;
      transition: all 0.3s ease;
   }

   .status-complete {
      color: #4CAF50;
      font-weight: 700;
      margin-left: 5px;
   }

   .status-ongoing,
   .status-updating {
      color: #FFC107;
      font-weight: 700;
      margin-left: 5px;
   }

   .year-value {
      color: #ff9d00;
      font-weight: 700;
   }

   .film-description {
      background: rgba(0, 0, 0, 0.2);
      border-radius: 8px;
      padding: 15px;
      margin-top: 20px;
   }

   .description-header {
      font-size: 16px;
      font-weight: 600;
      color: #ffed4d;
      margin-bottom: 10px;
      display: flex;
      align-items: center;
   }

   .description-header i {
      margin-right: 8px;
   }

   .description-content {
      color: #ddd;
      line-height: 1.6;
      text-align: justify;
   }

   /* Style cho movie rating views row */
   .movie-rating-views-row {
      display: flex;
      align-items: center;
      gap: 15px;
      margin-top: 15px;
      padding-left: 10px;
      flex-wrap: wrap;
   }

   @media (max-width: 767px) {
      .info-box-content {
         grid-template-columns: 1fr;
      }

      .movie-rating-views-row {
         flex-direction: column;
         align-items: flex-start;
      }

      .film-poster-header {
         text-align: center;
      }

      .movie-title.title-1 {
         font-size: 20px;
      }
   }

   /* Style cho tab navigation */
   .movie-details-tabs {
      margin: 20px 0;
      background: rgba(15, 23, 42, 0.5);
      border-radius: 8px;
      overflow: hidden;
      box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
   }

   .nav-tabs {
      border-bottom: none;
      background: rgba(15, 23, 42, 0.8);
      display: flex;
      overflow-x: auto;
      white-space: nowrap;
      scrollbar-width: none;
      /* Firefox */
   }

   .nav-tabs::-webkit-scrollbar {
      display: none;
      /* Chrome, Safari, Opera */
   }

   .nav-tabs>li {
      float: none;
      display: inline-block;
      margin-bottom: 0;
   }

   .nav-tabs>li>a {
      border: none;
      background: transparent;
      color: #aaa;
      padding: 15px 20px;
      font-weight: 600;
      border-radius: 0;
      transition: all 0.3s ease;
      margin-right: 0;
      position: relative;
   }

   .nav-tabs>li>a:hover {
      background: rgba(255, 255, 255, 0.05);
      color: #fff;
      border: none;
   }

   .nav-tabs>li.active>a,
   .nav-tabs>li.active>a:focus,
   .nav-tabs>li.active>a:hover {
      color: #ffed4d;
      background: transparent;
      border: none;
   }

   .nav-tabs>li.active>a::after {
      content: '';
      position: absolute;
      bottom: 0;
      left: 0;
      width: 100%;
      height: 3px;
      background: linear-gradient(to right, #04c1e7, #0f6060);
   }

   .nav-tabs>li>a i {
      margin-right: 8px;
   }

   .tab-content {
      padding: 0;
   }

   .tab-pane {
      padding: 25px;
   }

   /* Style cho movie info */
   .movie-info-panel {
      padding: 0;
   }

   .movie-info-content {
      margin-bottom: 15px;
   }

   .movie-info-group {
      margin-bottom: 15px;
   }

   .info-item {
      margin-bottom: 15px;
      display: flex;
      flex-wrap: wrap;
      align-items: center;
   }

   .info-label {
      color: #aaa;
      font-weight: 500;
      margin-right: 10px;
      min-width: 100px;
   }

   .info-label i {
      color: #04c1e7;
      margin-right: 5px;
   }

   .info-value {
      color: #fff;
      font-weight: 600;
   }

   /* Badges styling */
   .quality-badge,
   .language-badge,
   .status-badge {
      display: inline-block;
      padding: 4px 8px;
      border-radius: 4px;
      font-size: 12px;
      font-weight: 600;
      margin-right: 5px;
      text-transform: uppercase;
   }

   .quality-badge {
      background: linear-gradient(135deg, #ff0000 0%, #aa0000 100%);
      color: white;
   }

   .language-badge {
      background: linear-gradient(135deg, #666666 0%, #333333 100%);
      color: white;
   }

   .status-badge.complete {
      background: linear-gradient(135deg, #008000 0%, #005000 100%);
      color: white;
   }

   .status-badge.ongoing,
   .status-badge.updating {
      background: linear-gradient(135deg, #ea8d40 30%, #e70404 70%);
      color: white;
   }

   /* Genre list styling */
   .movie-info-genres {
      display: flex;
      align-items: center;
      flex-wrap: wrap;
      margin-top: 15px;
      padding-top: 15px;
      border-top: 1px solid rgba(255, 255, 255, 0.1);
   }

   .genre-label {
      margin-bottom: 10px;
   }

   .genre-list {
      display: flex;
      flex-wrap: wrap;
      gap: 10px;
   }

   .genre-item {
      display: inline-block;
      padding: 6px 12px;
      background: rgba(4, 193, 231, 0.1);
      border: 1px solid rgba(4, 193, 231, 0.3);
      border-radius: 20px;
      color: #04c1e7;
      transition: all 0.3s ease;
   }

   .genre-item:hover {
      background: rgba(4, 193, 231, 0.2);
      border-color: #04c1e7;
      color: #fff;
      text-decoration: none;
   }

   /* Tags panel */
   .movie-tags-panel {
      min-height: 100px;
   }

   .tags-content {
      display: flex;
      flex-wrap: wrap;
      gap: 10px;
   }

   .tag-item {
      display: inline-block;
      padding: 8px 16px;
      background: rgba(255, 237, 77, 0.1);
      border: 1px solid rgba(255, 237, 77, 0.3);
      border-radius: 20px;
      color: #ffed4d;
      transition: all 0.3s ease;
      font-weight: 500;
   }

   .tag-item:hover {
      background: rgba(255, 237, 77, 0.2);
      border-color: #ffed4d;
      color: #fff;
      text-decoration: none;
   }

   /* Description panel */
   .movie-desc-panel {
      min-height: 100px;
   }

   .desc-content {
      color: #ddd;
      line-height: 1.8;
      text-align: justify;
   }

   /* Comments panel */
   .movie-comments-panel {
      min-height: 300px;
   }

   /* Trailer panel */
   .movie-trailer-panel {
      min-height: 400px;
   }

   /* Links styling */
   .category-link,
   .country-link,
   .episode-tag {
      color: #04c1e7;
      transition: all 0.3s ease;
      text-decoration: none;
   }

   .category-link:hover,
   .country-link:hover,
   .episode-tag:hover {
      color: #ffed4d;
      text-decoration: none;
   }

   .year-value {
      color: #ff9d00;
      font-weight: 700;
   }

   /* Mobile responsive */
   @media (max-width: 767px) {
      .tab-pane {
         padding: 15px;
      }

      .info-item {
         margin-bottom: 10px;
      }

      .info-label {
         min-width: 100%;
         margin-bottom: 5px;
      }

      .movie-info-genres {
         flex-direction: column;
         align-items: flex-start;
      }

      .genre-list {
         margin-top: 10px;
      }
   }

   /* Style mới cho thông tin phim */
   .movie-meta-info {
      margin: 15px 0;
      display: flex;
      flex-wrap: wrap;
      gap: 10px;
      padding-left: 15px;
      /* Thêm padding bên trái */
   }

   .movie-meta-info .info-item {
      display: flex;
      flex-wrap: wrap;
      gap: 8px;
   }

   .quality-badge,
   .language-badge,
   .year-badge,
   .episode-badge,
   .duration-badge {
      display: inline-block;
      padding: 5px 10px;
      border-radius: 20px;
      font-size: 12px;
      font-weight: 600;
      color: white;
   }

   .quality-badge {
      background: linear-gradient(135deg, #ff0000 0%, #aa0000 100%);
   }

   .language-badge {
      background: linear-gradient(135deg, #666666 0%, #333333 100%);
   }

   .year-badge {
      background: linear-gradient(135deg, #ff9d00 0%, #bb7400 100%);
   }

   .episode-badge {
      background: linear-gradient(135deg, #04c1e7 0%, #0f6060 100%);
   }

   .duration-badge {
      background: linear-gradient(135deg, #9896f1 0%, #7579e7 100%);
   }

   .episode-badge i.fas {
      margin-left: 5px;
   }

   .fas.fa-check-circle {
      color: #4CAF50;
   }

   .film-description {
      margin: 15px 0;
      background: rgba(0, 0, 0, 0.2);
      border-radius: 8px;
      padding: 15px;
      margin-left: 15px;
      /* Thêm margin bên trái */
      margin-right: 15px;
      /* Thêm margin bên phải để cân đối */
   }

   .desc-text {
      color: #ddd;
      margin-bottom: 10px;
      line-height: 1.6;
   }

   .read-more {
      color: #04c1e7;
      font-weight: 500;
      transition: all 0.3s ease;
      display: inline-block;
   }

   .read-more:hover {
      color: #ffed4d;
      text-decoration: none;
   }

   .read-more i {
      font-size: 12px;
      margin-left: 5px;
      transition: transform 0.3s ease;
   }

   .read-more:hover i {
      transform: translateX(3px);
   }

   .film-categories {
      background: rgba(20, 30, 48, 0.5);
      border-radius: 8px;
      padding: 15px;
      margin-left: 15px;
      /* Thêm margin bên trái */
      margin-right: 15px;
      /* Thêm margin bên phải để cân đối */
   }

   .category-row {
      display: flex;
      margin-bottom: 10px;
      align-items: flex-start;
   }

   .category-row:last-child {
      margin-bottom: 0;
   }

   .category-label {
      color: #aaa;
      font-weight: 500;
      margin-right: 10px;
      min-width: 100px;
   }

   .category-label i {
      color: #04c1e7;
      margin-right: 5px;
   }

   .category-value {
      color: #fff;
      font-weight: 600;
      flex: 1;
   }

   .genre-list {
      display: flex;
      flex-wrap: wrap;
      gap: 5px;
   }

   .genre-tag {
      color: #06f2e6;
      transition: all 0.3s ease;
      font-weight: 500;
   }

   .genre-tag:hover {
      color: #ffed4d;
      text-decoration: none;
   }

   /* Tab chi tiết bổ sung */
   .info-section {
      margin-bottom: 25px;
   }

   .section-title {
      font-size: 18px;
      font-weight: 700;
      color: #ffed4d;
      margin-bottom: 15px;
      display: flex;
      align-items: center;
   }

   .section-title i {
      margin-right: 10px;
   }

   .details-table {
      width: 100%;
      border-collapse: separate;
      border-spacing: 0 8px;
   }

   .detail-row {
      display: flex;
      margin-bottom: 10px;
      border-bottom: 1px dashed rgba(255, 255, 255, 0.1);
      padding-bottom: 10px;
   }

   .detail-row:last-child {
      border-bottom: none;
   }

   .detail-label {
      color: #aaa;
      font-weight: 500;
      width: 120px;
      min-width: 120px;
   }

   .detail-value {
      color: #fff;
      flex: 1;
   }

   .status-complete {
      color: #4CAF50;
      font-weight: 700;
      margin-left: 5px;
   }

   .status-ongoing {
      color: #FFC107;
      font-weight: 700;
      margin-left: 5px;
   }

   /* Tab danh sách tập phim */
   .episode-grid {
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(80px, 1fr));
      gap: 10px;
   }

   .episode-item {
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 10px;
      background: rgba(4, 193, 231, 0.1);
      border: 1px solid rgba(4, 193, 231, 0.2);
      border-radius: 6px;
      color: #04c1e7;
      font-weight: 600;
      transition: all 0.3s ease;
      text-align: center;
   }

   .episode-item:hover {
      background: rgba(4, 193, 231, 0.2);
      border-color: #04c1e7;
      color: #fff;
      text-decoration: none;
   }

   .episode-item.active {
      background: #04c1e7;
      color: #fff;
      border-color: #04c1e7;
   }

   .no-episode {
      padding: 20px;
      text-align: center;
      color: #aaa;
      font-style: italic;
   }

   @media (max-width: 767px) {
      .category-row {
         flex-direction: column;
      }

      .category-label {
         margin-bottom: 5px;
      }

      .detail-row {
         flex-direction: column;
      }

      .detail-label {
         margin-bottom: 5px;
      }

      .episode-grid {
         grid-template-columns: repeat(auto-fill, minmax(60px, 1fr));
      }
   }

   /* ===== Styles cho phần bình luận ===== */
   .comment-section {
      background: rgba(20, 30, 48, 0.5);
      border-radius: 10px;
      padding: 20px;
      margin-bottom: 30px;
   }

   .comment-form textarea {
      background: rgba(20, 30, 48, 0.8);
      border: 1px solid rgba(255, 255, 255, 0.1);
      border-radius: 8px;
      color: #fff;
      resize: none;
   }

   .comment-form textarea:focus {
      background: rgba(20, 30, 48, 0.8);
      border-color: rgba(4, 193, 231, 0.5);
      color: #fff;
      box-shadow: 0 0 0 0.2rem rgba(4, 193, 231, 0.25);
   }

   .comment {
      background: rgba(15, 23, 42, 0.5);
      border-radius: 10px;
      padding: 15px;
      margin-bottom: 15px;
      transition: all 0.3s ease;
   }

   .comment:hover {
      background: rgba(15, 23, 42, 0.7);
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
   }

   .comment-avatar img {
      border: 2px solid rgba(4, 193, 231, 0.3);
      transition: all 0.3s ease;
   }

   .comment:hover .comment-avatar img {
      border-color: rgba(4, 193, 231, 0.7);
      box-shadow: 0 0 10px rgba(4, 193, 231, 0.5);
   }

   .comment-author {
      color: #ffed4d;
      font-weight: 600;
      margin-bottom: 0;
   }

   .comment-date {
      font-size: 12px;
      color: #aaa;
   }

   .comment-body {
      margin-top: 8px;
      color: #fff;
   }

   .comment-actions {
      margin-top: 8px;
   }

   .comment-actions .btn-link {
      color: #04c1e7;
      padding: 2px 5px;
      font-size: 12px;
      transition: all 0.3s ease;
   }

   .comment-actions .btn-link:hover {
      color: #ffed4d;
      text-decoration: none;
   }

   .comment-actions .text-danger:hover {
      color: #ff5252 !important;
   }

   .reply-comment {
      background: rgba(15, 23, 42, 0.3);
      border-left: 3px solid rgba(4, 193, 231, 0.5);
   }

   .reply-comment:hover {
      border-left-color: rgba(4, 193, 231, 1);
   }

   .comment-author.admin {
      color: #ff5e94;
   }

   .comment-author.admin:after {
      content: '(Admin)';
      font-size: 10px;
      background: rgba(255, 94, 148, 0.2);
      color: #ff5e94;
      padding: 2px 6px;
      border-radius: 10px;
      margin-left: 5px;
      vertical-align: middle;
   }

   .edit-form,
   .reply-form {
      background: rgba(10, 17, 30, 0.5);
      border-radius: 8px;
      padding: 15px;
   }

   .section-bar {
      margin-bottom: 15px;
   }

   /* CSS cho nút yêu thích */
   .social-action-button.favorite {
      position: relative;
      overflow: hidden;
      transition: all 0.3s ease;
   }

   .social-action-button.favorite.active {
      background-color: #f8d7da;
      color: #dc3545;
      border-color: #f5c6cb;
   }

   .social-action-button.favorite.active i {
      color: #dc3545;
   }

   .social-action-button.favorite:hover {
      background-color: #f8d7da;
      color: #dc3545;
   }

   /* Hiệu ứng khi click vào nút yêu thích */
   .social-action-button.favorite::after {
      content: '';
      position: absolute;
      width: 100%;
      height: 100%;
      top: 0;
      left: 0;
      background: radial-gradient(circle, rgba(255, 255, 255, 0.8) 0%, rgba(255, 255, 255, 0) 70%);
      transform: scale(0);
      opacity: 0;
      pointer-events: none;
      transition: all 0.5s ease;
   }

   .social-action-button.favorite:active::after {
      transform: scale(2);
      opacity: 1;
      transition: 0s;
   }

   /* Animation cho icon trái tim */
   @keyframes heartbeat {
      0% {
         transform: scale(1);
      }

      25% {
         transform: scale(1.2);
      }

      50% {
         transform: scale(1);
      }

      75% {
         transform: scale(1.2);
      }

      100% {
         transform: scale(1);
      }
   }

   .social-action-button.favorite.active i {
      animation: heartbeat 1s ease-in-out;
   }
</style>
<!-- <link rel="stylesheet" href="{{asset('css/movie.css')}}"> -->
<div class="row container" id="wrapper">
   <div class="halim-panel-filter">
      <div class="panel-heading">
         <div class="row">
            <div class="col-xs-6">
               <div class="yoast_breadcrumb hidden-xs"><span>
                     <span>
                        <a href="{{route('category',[$movie->category->slug])}}">{{$movie->category->title}}</a> »
                        <span>
                           <span class="breadcrumb_last"
                              aria-current="page">{{$movie->title}}</span></span></span></span></div>
            </div>
         </div>
      </div>
      <div id="ajax-filter" class="panel-collapse collapse" aria-expanded="true" role="menu">
         <div class="ajax"></div>
      </div>
   </div>
   <main id="main-contents" class="col-xs-12 col-sm-12 col-md-8">
      <section id="content" class="test">
         <div class="clearfix wrap-content">

            <div class="halim-movie-wrapper">
               <div class="movie_info col-xs-12">
                  <div class="movie-poster col-md-3">
                     <div class="film-poster-img">
                        <img class="movie-thumb"
                           src="@if(Str::startsWith($movie->image, ['http://', 'https://'])){{$movie->image}}@else{{asset('uploads/movie/'.$movie->image)}}@endif"
                           alt="{{$movie->title}}">
                        <!-- <span class="status-label">
                           @if($movie->resolution==0)
                           <span class="tag-base hd-tag">HD</span>
                           @elseif($movie->resolution==1)
                           <span class="tag-base sd-tag">SD</span>
                           @elseif($movie->resolution==2)
                           <span class="tag-base hdcam-tag">HDCam</span>
                           @elseif($movie->resolution==3)
                           <span class="tag-base cam-tag">Cam</span>
                           @elseif($movie->resolution==4)
                           <span class="tag-base fullhd-tag">FullHD</span>
                           @else
                           <span class="tag-base trailer-tag">Trailer</span>
                           @endif
                           
                           @if($movie->phude==0)
                           <span class="tag-base phude-tag">P.Đề</span>
                           @else
                           <span class="tag-base thuyetminh-tag">T.M</span>
                           @endif
                        </span> -->

                        @if($movie->resolution!==5)
                        @if($episode_current_list_count>0)
                        <div class="bwa-content">
                           <div class="loader"></div>
                           <a href="{{url('xem-phim/'.$movie->slug. '/tap-'.$episode_tapdau->episode)}}"
                              class="bwac-btn">
                              <i class="fa fa-play"></i>
                           </a>
                        </div>

                        <div class="movie-action-buttons">
                           <a href="{{url('xem-phim/'.$movie->slug. '/tap-'.$episode_tapdau->episode)}}"
                              class="movie-action-button watch-movie-btn">
                              <i class="fa fa-play-circle"></i>Xem phim
                           </a>
                        </div>
                        @endif
                        @else
                        <div class="movie-action-buttons">
                           <a href="#movie-trailer" class="movie-action-button watch-trailer-btn" data-toggle="tab"
                              role="tab" id="trailer-tab">
                              <i class="fa fa-film"></i>Xem trailer
                           </a>
                        </div>
                        @endif

                        <!-- Thanh tương tác -->
                        <div class="social-action-bar-container">
                           <div class="social-action-bar">
                              <button class="social-action-button favorite" id="favoriteBtn"
                                 data-movie-id="{{$movie->id}}">
                                 <i class="far fa-heart"></i>
                                 <span>Yêu thích</span>
                              </button>
                              <button class="social-action-button actors" id="actorsBtn">
                                 <i class="fas fa-users"></i>
                                 <span>Diễn viên</span>
                              </button>
                              <button class="social-action-button share" id="shareBtn">
                                 <i class="far fa-paper-plane"></i>
                                 <span>Chia sẻ</span>
                              </button>
                              <button class="social-action-button comment" id="commentBtn">
                                 <i class="far fa-comment-dots"></i>
                                 <span>Bình luận</span>
                              </button>
                           </div>
                        </div>
                     </div>
                  </div>
                  <div class="film-poster col-md-9">
                     <div class="film-poster-header">
                        <h1 class="movie-title title-1">{{$movie->title}}</h1>
                        <h2 class="movie-title title-2">{{$movie->name_eng}}</h2>

                        <div class="movie-meta-info">
                           <div class="info-item">
                              <span class="quality-badge">
                                 @if($movie->resolution==0)
                                 HD
                                 @elseif($movie->resolution==1)
                                 SD
                                 @elseif($movie->resolution==2)
                                 HDCam
                                 @elseif($movie->resolution==3)
                                 Cam
                                 @elseif($movie->resolution==4)
                                 FullHD
                                 @else
                                 Trailer
                                 @endif
                              </span>

                              <span class="language-badge">
                                 @if($movie->phude==0)
                                 Phụ đề
                                 @else
                                 Thuyết minh
                                 @endif
                              </span>

                              <span class="year-badge">{{$movie->year}}</span>

                              @if($movie->thuocphim=='phimbo')
                              <span class="episode-badge">
                                 {{$episode_current_list_count}}/{{$movie->sotap}}
                                 @if($episode_current_list_count==$movie->sotap)
                                 <i class="fas fa-check-circle"></i>
                                 @else
                                 <i class="fas fa-spinner fa-spin"></i>
                                 @endif
                              </span>
                              @endif

                              <span class="duration-badge">
                                 <i class="far fa-clock"></i> {{$movie->thoiluong}}
                              </span>
                           </div>
                        </div>

                        <div class="movie-rating-views-row">
                           <span class="current-rating">
                              <i class="fas fa-star"></i> {{$rating}}
                              <small>({{$count_total}} lượt đánh giá)</small>
                           </span>
                           <span class="current-views">
                              <i class="fas fa-eye"></i>
                              <span class="view-counter" data-movie-id="{{$movie->id}}"
                                 data-movie-slug="{{$movie->slug}}">
                                 @php
                                 $views = $count_views;
                                 if ($views >= 1000000) {
                                 echo number_format($views/1000000, 1) . 'M';
                                 } elseif ($views >= 1000) {
                                 echo number_format($views/1000, 1) . 'K';
                                 } else {
                                 echo number_format($views);
                                 }
                                 @endphp
                              </span>
                              <small>lượt xem</small>
                           </span>
                           @if(isset($movie->director) && !empty($movie->director))
                           <span class="current-views"
                              style="background: rgba(255, 237, 77, 0.1); border: 1px solid rgba(255, 237, 77, 0.3);">
                              <i class="fas fa-video" style="color: #ffed4d;"></i>
                              <span style="color: #ffed4d; font-weight: 600;"><small
                                    style="opacity: 0.8; margin-right: 5px;">Đạo diễn:</small>
                                 {{$movie->director}}</span>
                           </span>
                           @endif
                           <button type="button" class="movie-action-button rate-movie-btn" data-toggle="modal"
                              data-target="#ratingModal">
                              <i class="fas fa-star"></i> Đánh giá phim
                           </button>
                        </div>
                     </div>

                     <div class="film-description">
                        <p class="desc-text">{{Str::limit($movie->description, 200)}}</p>
                        <a href="#movie-desc" class="read-more" data-toggle="tab" role="tab" id="desc-tab">Xem thêm <i
                              class="fas fa-angle-right"></i></a>
                     </div>

                     <div class="film-categories">
                        <div class="category-row">
                           <div class="category-label"><i class="fas fa-list"></i> Danh mục:</div>
                           <div class="category-value">
                              <a href="{{route('category',[$movie->category->slug])}}"
                                 class="category-link">{{$movie->category->title}}</a>
                           </div>
                        </div>
                        <div class="category-row">
                           <div class="category-label"><i class="fas fa-globe-asia"></i> Quốc gia:</div>
                           <div class="category-value">
                              <a href="{{route('country',[$movie->country->slug])}}"
                                 class="country-link">{{$movie->country->title}}</a>
                           </div>
                        </div>
                        <div class="category-row">
                           <div class="category-label"><i class="fas fa-tags"></i> Thể loại:</div>
                           <div class="category-value genre-list">
                              @foreach($movie->movie_genre as $gen)
                              <a href="{{route('genre',$gen->slug)}}" class="genre-tag">{{$gen->title}}</a>
                              @if(!$loop->last),@endif
                              @endforeach
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
            <div class="clearfix"></div>
            <div id="halim_trailer"></div>
            <div class="clearfix"></div>

            <!-- Thay thế phần thông tin phim cũ bằng tab navigation -->
            <div class="movie-details-tabs">
               <ul class="nav nav-tabs" role="tablist">
                  <li role="presentation">
                     <a href="#movie-info" aria-controls="movie-info" role="tab" data-toggle="tab">
                        <i class="fas fa-info-circle"></i> Chi tiết phim
                     </a>
                  </li>
                  <li role="presentation" class="active">
                     <a href="#movie-desc" aria-controls="movie-desc" role="tab" data-toggle="tab">
                        <i class="fas fa-align-left"></i> Nội dung phim
                     </a>
                  </li>
                  <li role="presentation">
                     <a href="#movie-episode" aria-controls="movie-episode" role="tab" data-toggle="tab">
                        <i class="fas fa-play-circle"></i> Danh sách tập
                     </a>
                  </li>
                  <li role="presentation">
                     <a href="#movie-tags" aria-controls="movie-tags" role="tab" data-toggle="tab">
                        <i class="fas fa-tags"></i> Tags phim
                     </a>
                  </li>
                  <li role="presentation">
                     <a href="#movie-comments" aria-controls="movie-comments" role="tab" data-toggle="tab">
                        <i class="fas fa-comments"></i> Bình luận
                     </a>
                  </li>
                  <li role="presentation">
                     <a href="#movie-trailer" aria-controls="movie-trailer" role="tab" data-toggle="tab">
                        <i class="fas fa-film"></i> Trailer
                     </a>
                  </li>
               </ul>

               <div class="tab-content">
                  <!-- Tab chi tiết phim -->
                  <div role="tabpanel" class="tab-pane" id="movie-info">
                     <div class="movie-info-panel">
                        <div class="info-section">
                           <h3 class="section-title"><i class="fas fa-info-circle"></i> Thông tin chi tiết</h3>

                           <div class="details-table">
                              {{-- Phần thông tin đạo diễn, diễn viên, hãng sản xuất (sẽ được bổ sung sau) --}}
                              {{-- <div class="detail-row">
                                 <div class="detail-label">Đạo diễn:</div>
                                 <div class="detail-value">Chris Columbus</div>
                              </div>
                              <div class="detail-row">
                                 <div class="detail-label">Diễn viên:</div>
                                 <div class="detail-value">Daniel Radcliffe, Emma Watson, Rupert Grint</div>
                              </div>
                              <div class="detail-row">
                                 <div class="detail-label">Hãng sản xuất:</div>
                                 <div class="detail-value">Warner Bros.</div>
                              </div> --}}

                              <div class="detail-row">
                                 <div class="detail-label">Tên tiếng Anh:</div>
                                 <div class="detail-value">{{$movie->name_eng}}</div>
                              </div>
                              <div class="detail-row">
                                 <div class="detail-label">Quốc gia:</div>
                                 <div class="detail-value">{{$movie->country->title}}</div>
                              </div>
                              <div class="detail-row">
                                 <div class="detail-label">Năm sản xuất:</div>
                                 <div class="detail-value">{{$movie->year}}</div>
                              </div>
                              <div class="detail-row">
                                 <div class="detail-label">Thời lượng:</div>
                                 <div class="detail-value">{{$movie->thoiluong}}</div>
                              </div>
                              <div class="detail-row">
                                 <div class="detail-label">Chất lượng:</div>
                                 <div class="detail-value">
                                    @if($movie->resolution==0)
                                    HD
                                    @elseif($movie->resolution==1)
                                    SD
                                    @elseif($movie->resolution==2)
                                    HDCam
                                    @elseif($movie->resolution==3)
                                    Cam
                                    @elseif($movie->resolution==4)
                                    FullHD
                                    @else
                                    Trailer
                                    @endif
                                 </div>
                              </div>
                              <div class="detail-row">
                                 <div class="detail-label">Ngôn ngữ:</div>
                                 <div class="detail-value">
                                    @if($movie->phude==0)
                                    Phụ đề Việt
                                    @else
                                    Thuyết minh
                                    @endif
                                 </div>
                              </div>
                              @if($movie->thuocphim=='phimbo')
                              <div class="detail-row">
                                 <div class="detail-label">Số tập:</div>
                                 <div class="detail-value">
                                    {{$episode_current_list_count}}/{{$movie->sotap}}
                                    @if($episode_current_list_count==$movie->sotap)
                                    <span class="status-complete">Hoàn thành</span>
                                    @else
                                    <span class="status-ongoing">Đang cập nhật</span>
                                    @endif
                                 </div>
                              </div>
                              @endif
                              @if($movie->season!==0)
                              <div class="detail-row">
                                 <div class="detail-label">Số Season:</div>
                                 <div class="detail-value">{{$movie->season}}</div>
                              </div>
                              @endif
                              <div class="detail-row">
                                 <div class="detail-label">Lượt xem:</div>
                                 <div class="detail-value">
                                    @php
                                    $views = $count_views;
                                    if ($views >= 1000000) {
                                    echo number_format($views/1000000, 1) . ' triệu';
                                    } elseif ($views >= 1000) {
                                    echo number_format($views/1000, 1) . ' nghìn';
                                    } else {
                                    echo number_format($views);
                                    }
                                    @endphp
                                 </div>
                              </div>
                              <div class="detail-row">
                                 <div class="detail-label">Đánh giá:</div>
                                 <div class="detail-value"><i class="fas fa-star" style="color: #ffcc00;"></i>
                                    {{$rating}}/5 ({{$count_total}} lượt)</div>
                              </div>
                              <div class="detail-row">
                                 <div class="detail-label">Danh mục:</div>
                                 <div class="detail-value">{{$movie->category->title}}</div>
                              </div>
                              <div class="detail-row">
                                 <div class="detail-label">Thể loại:</div>
                                 <div class="detail-value">
                                    @foreach($movie->movie_genre as $gen)
                                    <a href="{{route('genre',$gen->slug)}}" class="genre-tag"
                                       style="display: inline-block; margin-right: 5px;">{{$gen->title}}</a>
                                    @endforeach
                                 </div>
                              </div>

                              @if(isset($movie->actors) && !empty($movie->actors))
                              <div class="detail-row">
                                 <div class="detail-label">Diễn viên:</div>
                                 <div class="detail-value">{{$movie->actors}}</div>
                              </div>
                              @endif

                              @if(isset($movie->director) && !empty($movie->director))
                              <div class="detail-row">
                                 <div class="detail-label">Đạo diễn:</div>
                                 <div class="detail-value">{{$movie->director}}</div>
                              </div>
                              @endif
                           </div>
                        </div>
                     </div>
                  </div>

                  <!-- Tab nội dung phim -->
                  <div role="tabpanel" class="tab-pane active" id="movie-desc">
                     <div class="movie-desc-panel">
                        <div class="desc-content">
                           {{$movie->description}}
                        </div>
                     </div>
                  </div>

                  <!-- Tab danh sách tập phim -->
                  <div role="tabpanel" class="tab-pane" id="movie-episode">
                     <div class="movie-episode-panel">
                        <div class="episode-list">
                           @if($movie->thuocphim=='phimbo' && $episode_current_list_count > 0)
                           <div class="episode-grid">
                              @foreach($episode as $key => $epi)
                              <a href="{{url('xem-phim/'.$movie->slug.'/tap-'.$epi->episode)}}"
                                 class="episode-item {{ $epi->episode == $episode_tapdau->episode ? 'active' : '' }}">
                                 Tập {{$epi->episode}}
                              </a>
                              @endforeach
                           </div>
                           @elseif($movie->thuocphim=='phimle' && $episode_current_list_count > 0)
                           <div class="episode-grid">
                              @foreach($episode as $key => $epi)
                              <a href="{{url('xem-phim/'.$movie->slug.'/tap-'.$epi->episode)}}"
                                 class="episode-item active">
                                 {{$epi->episode}}
                              </a>
                              @endforeach
                           </div>
                           @else
                           <div class="no-episode">
                              <p>Chưa có tập phim nào được cập nhật.</p>
                           </div>
                           @endif
                        </div>
                     </div>
                  </div>

                  <!-- Tab tags phim -->
                  <div role="tabpanel" class="tab-pane" id="movie-tags">
                     <div class="movie-tags-panel">
                        <div class="tags-content">
                           @if($movie->tags!=NULL)
                           @php
                           $tags = array();
                           $tags = explode(",",$movie->tags);
                           @endphp
                           @foreach($tags as $key => $tag)
                           <a href="{{url('tag/'.$tag)}}" class="tag-item">{{$tag}}</a>
                           @endforeach
                           @else
                           {{$movie->title}}
                           @endif
                        </div>
                     </div>
                  </div>

                  <!-- Tab bình luận -->
                  <div role="tabpanel" class="tab-pane" id="movie-comments">
                     <div class="movie-comments-panel">
                        <div class="comments-content">
                           <div class="comment-section">
                              <div class="section-bar clearfix">
                                 <h2 class="section-title"><span style="color:#ffed4d">Bình luận phim:
                                       {{$movie->title}}</span></h2>
                              </div>

                              @if(Auth::check())
                              <div class="comment-form mb-4">
                                 <form method="POST" action="{{ route('comment.store') }}">
                                    @csrf
                                    <input type="hidden" name="movie_id" value="{{ $movie->id }}">
                                    <div class="form-group">
                                       <textarea class="form-control" name="content" rows="3"
                                          placeholder="Viết bình luận của bạn về phim..."></textarea>
                                    </div>
                                    <button type="submit" class="btn btn-primary">Gửi bình luận</button>
                                 </form>
                              </div>
                              @else
                              <div class="alert alert-info">
                                 <p>Vui lòng <a href="#" data-toggle="modal" data-target="#loginModal">đăng nhập</a> để
                                    bình luận.</p>
                              </div>
                              @endif

                              <div class="comments-list mt-4">
                                 @if(isset($comments) && count($comments) > 0)
                                 @foreach($comments as $comment)
                                 <div class="comment mb-4" id="comment-{{ $comment->id }}">
                                    <div class="comment-avatar float-left mr-3">
                                       <img
                                          src="https://www.gravatar.com/avatar/{{ md5($comment->user->email) }}?d=mp&s=50"
                                          class="rounded-circle" width="50" height="50"
                                          alt="{{ $comment->user->name }}">
                                    </div>
                                    <div class="comment-content">
                                       <div class="comment-header">
                                          <h5 class="comment-author">{{ $comment->user->name }}</h5>
                                          <span class="comment-date text-muted">{{ $comment->created_at->diffForHumans()
                                             }}</span>
                                       </div>
                                       <div class="comment-body">
                                          <p>{{ $comment->content }}</p>
                                       </div>
                                       @if(Auth::check())
                                       <div class="comment-actions">
                                          <button class="btn btn-sm btn-link reply-btn"
                                             data-comment-id="{{ $comment->id }}">Trả lời</button>
                                          @if(Auth::id() == $comment->user_id || (Auth::check() && Auth::user()->role ==
                                          '1'))
                                          <button class="btn btn-sm btn-link edit-btn"
                                             data-comment-id="{{ $comment->id }}"
                                             data-content="{{ $comment->content }}">Sửa</button>
                                          <form method="POST" action="{{ route('comment.delete', $comment->id) }}"
                                             class="d-inline"
                                             onsubmit="return confirm('Bạn có chắc muốn xóa bình luận này?')">
                                             @csrf
                                             <button type="submit" class="btn btn-sm btn-link text-danger">Xóa</button>
                                          </form>
                                          @endif
                                       </div>
                                       @endif

                                       <!-- Form trả lời comment (ẩn ban đầu) -->
                                       <div class="reply-form mt-3" id="reply-form-{{ $comment->id }}"
                                          style="display:none;">
                                          <form method="POST" action="{{ route('comment.store') }}">
                                             @csrf
                                             <input type="hidden" name="movie_id" value="{{ $movie->id }}">
                                             <input type="hidden" name="parent_id" value="{{ $comment->id }}">
                                             <div class="form-group">
                                                <textarea class="form-control" name="content" rows="2"
                                                   placeholder="Trả lời..."></textarea>
                                             </div>
                                             <button type="submit" class="btn btn-sm btn-primary">Gửi</button>
                                             <button type="button" class="btn btn-sm btn-secondary cancel-reply"
                                                data-comment-id="{{ $comment->id }}">Hủy</button>
                                          </form>
                                       </div>

                                       <!-- Form sửa comment (ẩn ban đầu) -->
                                       <div class="edit-form mt-3" id="edit-form-{{ $comment->id }}"
                                          style="display:none;">
                                          <form method="POST" action="{{ route('comment.update', $comment->id) }}">
                                             @csrf
                                             <div class="form-group">
                                                <textarea class="form-control" name="content"
                                                   rows="2">{{ $comment->content }}</textarea>
                                             </div>
                                             <button type="submit" class="btn btn-sm btn-primary">Cập nhật</button>
                                             <button type="button" class="btn btn-sm btn-secondary cancel-edit"
                                                data-comment-id="{{ $comment->id }}">Hủy</button>
                                          </form>
                                       </div>

                                       <!-- Danh sách trả lời -->
                                       @if(count($comment->replies) > 0)
                                       <div class="replies ml-5 mt-3">
                                          @foreach($comment->replies as $reply)
                                          <div class="comment reply-comment mb-3" id="comment-{{ $reply->id }}">
                                             <div class="comment-avatar float-left mr-3">
                                                <img
                                                   src="https://www.gravatar.com/avatar/{{ md5($reply->user->email) }}?d=mp&s=40"
                                                   class="rounded-circle" width="40" height="40"
                                                   alt="{{ $reply->user->name }}">
                                             </div>
                                             <div class="comment-content">
                                                <div class="comment-header">
                                                   <h6 class="comment-author">{{ $reply->user->name }}</h6>
                                                   <span class="comment-date text-muted">{{
                                                      $reply->created_at->diffForHumans() }}</span>
                                                </div>
                                                <div class="comment-body">
                                                   <p>{{ $reply->content }}</p>
                                                </div>
                                                @if(Auth::check())
                                                <div class="comment-actions">
                                                   @if(Auth::id() == $reply->user_id || (Auth::check() &&
                                                   Auth::user()->role == '1'))
                                                   <button class="btn btn-sm btn-link edit-btn"
                                                      data-comment-id="{{ $reply->id }}"
                                                      data-content="{{ $reply->content }}">Sửa</button>
                                                   <form method="POST"
                                                      action="{{ route('comment.delete', $reply->id) }}"
                                                      class="d-inline"
                                                      onsubmit="return confirm('Bạn có chắc muốn xóa bình luận này?')">
                                                      @csrf
                                                      <button type="submit"
                                                         class="btn btn-sm btn-link text-danger">Xóa</button>
                                                   </form>
                                                   @endif
                                                </div>
                                                @endif

                                                <!-- Form sửa reply (ẩn ban đầu) -->
                                                <div class="edit-form mt-3" id="edit-form-{{ $reply->id }}"
                                                   style="display:none;">
                                                   <form method="POST"
                                                      action="{{ route('comment.update', $reply->id) }}">
                                                      @csrf
                                                      <div class="form-group">
                                                         <textarea class="form-control" name="content"
                                                            rows="2">{{ $reply->content }}</textarea>
                                                      </div>
                                                      <button type="submit" class="btn btn-sm btn-primary">Cập
                                                         nhật</button>
                                                      <button type="button" class="btn btn-sm btn-secondary cancel-edit"
                                                         data-comment-id="{{ $reply->id }}">Hủy</button>
                                                   </form>
                                                </div>
                                             </div>
                                             <div class="clearfix"></div>
                                          </div>
                                          @endforeach
                                       </div>
                                       @endif
                                    </div>
                                    <div class="clearfix"></div>
                                 </div>
                                 @endforeach
                                 @else
                                 <div class="text-center py-5">
                                    <i class="fas fa-comments fa-3x mb-3 text-muted"></i>
                                    <p class="text-muted">Chưa có bình luận nào cho phim này.<br>Hãy là người đầu tiên
                                       bình luận!</p>
                                 </div>
                                 @endif
                              </div>
                           </div>
                        </div>
                     </div>
                  </div>

                  <!-- Tab trailer -->
                  <div role="tabpanel" class="tab-pane" id="movie-trailer">
                     <div class="movie-trailer-panel">
                        <div class="trailer-content">
                           <iframe width="100%" height="500" src="https://www.youtube.com/embed/{{$movie->trailer}}"
                              title="YouTube video player" frameborder="0"
                              allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                              referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </section>
      <section class="related-movies">
         <div id="halim_related_movies-2xx" class="wrap-slider">
            <div class="section-bar clearfix">
               <h3 class="section-title"><span>CÓ THỂ BẠN MUỐN XEM</span></h3>
            </div>
            <div id="halim_related_movies-2" class="owl-carousel owl-theme related-film">

               @foreach($related as $key => $hot)
               <article class="thumb grid-item post-38498">
                  <div class="halim-item">
                     <a class="halim-thumb" href="{{route('movie',$hot->slug)}}" title="{{$hot->title}}">
                        <figure><img class="lazy img-responsive"
                              src="@if(Str::startsWith($hot->image, ['http://', 'https://'])){{$hot->image}}@else{{asset('uploads/movie/'.$hot->image)}}@endif"
                              alt="{{$hot->title}}" title="{{$hot->title}}"></figure>
                        <span class="status">
                           @if($hot->resolution==0)
                           <span class="tag-base hd-tag">HD</span>
                           @elseif($hot->resolution==1)
                           <span class="tag-base sd-tag">SD</span>
                           @elseif($hot->resolution==2)
                           <span class="tag-base hdcam-tag">HDCam</span>
                           @elseif($hot->resolution==3)
                           <span class="tag-base cam-tag">Cam</span>
                           @elseif($hot->resolution==4)
                           <span class="tag-base fullhd-tag">FullHD</span>
                           @else
                           <span class="tag-base trailer-tag">Trailer</span>
                           @endif
                        </span>

                        <span class="episode">
                           <span class="tag-base sotap-tag">{{$hot->episode_count}}/{{$hot->sotap}}</span>
                           @if($hot->phude==0)
                           <span class="tag-base phude-tag">P.Đề</span>
                           @else
                           <span class="tag-base thuyetminh-tag">T.M</span>
                           @endif

                           @if($hot->season!=0)
                           <span class="tag-base ss-tag">{{$hot->season}}.S</span>
                           @endif
                        </span>
                        <div class="play-button">
                           <i class="fas fa-play"></i>
                        </div>
                        <div class="icon_overlay"></div>
                        <div class="halim-post-title-box">
                           <div class="halim-post-title ">
                              <p class="entry-title">{{$hot->title}}</p>
                              <p class="original_title">{{$hot->name_eng}}</p>
                           </div>
                        </div>
                     </a>
                  </div>
               </article>
               @endforeach


            </div>
            <script>
               jQuery(document).ready(function($) {				
                var owl = $('#halim_related_movies-2');
                owl.owlCarousel({loop: true,margin: 4,autoplay: true,autoplayTimeout: 4000,autoplayHoverPause: true,nav: true,navText: [
         '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"></polyline></svg>',
         '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"></polyline></svg>'
         ]
         ,responsiveClass: true,responsive: {0: {items:2},480: {items:3}, 600: {items:4},1000: {items: 4}}})});
            </script>
         </div>
      </section>
   </main>
   @include('pages.include.sidebar')

</div>
<!-- Modal Rating Popup -->
<div class="modal fade" id="ratingModal" tabindex="-1" role="dialog" aria-labelledby="ratingModalLabel"
   aria-hidden="true">
   <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
         <div class="modal-header">
            <h5 class="modal-title" id="ratingModalLabel">
               <i class="fas fa-star"></i> Đánh giá phim: {{$movie->title}}
            </h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
               <span aria-hidden="true">&times;</span>
            </button>
         </div>
         <div class="modal-body">
            <div class="rating-system-popup" id="movie-rating-system-popup" data-movie-id="{{$movie->id}}">
               <div class="rating-header">
                  <div class="movie-poster-small">
                     <img
                        src="@if(Str::startsWith($movie->image, ['http://', 'https://'])){{$movie->image}}@else{{asset('uploads/movie/'.$movie->image)}}@endif"
                        alt="{{$movie->title}}">
                  </div>
                  <div class="rating-stats">
                     <span class="average">{{$rating}}</span>
                     <span class="count">({{$count_total}} lượt đánh giá)</span>
                  </div>
               </div>

               <div class="stars-container">
                  @for($i = 1; $i <= 5; $i++) <div class="star {{ $i <= $rating ? 'active' : '' }}" data-value="{{$i}}">
                     <i class="fas fa-star"></i>
                     <div class="rating-tooltip">{{$i}} sao</div>
               </div>
               @endfor
            </div>

            <div class="rating-feedback" id="rating-feedback-popup"></div>
         </div>
      </div>
      <div class="modal-footer">
         <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng</button>
      </div>
   </div>
</div>
</div>

<!-- Modal Danh sách diễn viên -->
<div class="actors-modal" id="actorsModal">
   <div class="actors-modal-content">
      <div class="actors-modal-header">
         <h3 class="actors-modal-title">
            <i class="fas fa-users"></i> Diễn viên phim: {{$movie->title}}
         </h3>
         <button class="actors-modal-close" id="closeActorsModal">
            <i class="fas fa-times"></i>
         </button>
      </div>
      <div class="actors-modal-body">
         <div class="actors-grid">
            @if(isset($movie->actors) && !empty($movie->actors))
            @php
            $actorsList = explode(', ', $movie->actors);
            @endphp

            @foreach($actorsList as $actor)
            <div class="actor-card-modal">
               <div class="actor-avatar-modal">
                  <img src="https://ui-avatars.com/api/?name={{urlencode($actor)}}&background=random" alt="{{$actor}}">
               </div>
               <p class="actor-name-modal">{{$actor}}</p>
               <p class="actor-role-modal">Diễn viên</p>
            </div>
            @endforeach
            @else
            <div class="no-actors-message-modal">
               Không có thông tin về diễn viên của phim này.
            </div>
            @endif
         </div>
      </div>
   </div>
</div>
<!-- Modal Chia sẻ -->
<div class="actors-modal" id="shareModal">
   <div class="actors-modal-content">
      <div class="actors-modal-header">
         <h3 class="actors-modal-title">
            <i class="fas fa-share-alt"></i> Chia sẻ phim: {{$movie->title}}
         </h3>
         <button class="actors-modal-close" id="closeShareModal">
            <i class="fas fa-times"></i>
         </button>
      </div>
      <div class="actors-modal-body">
         <div class="share-options-grid">
            <a href="javascript:void(0)" class="share-option-card" onclick="shareToFacebook()">
               <div class="share-option-icon facebook">
                  <i class="fab fa-facebook-f"></i>
               </div>
               <p class="share-option-name">Facebook</p>
            </a>

            <a href="javascript:void(0)" class="share-option-card" onclick="shareToTwitter()">
               <div class="share-option-icon twitter">
                  <i class="fab fa-twitter"></i>
               </div>
               <p class="share-option-name">Twitter</p>
            </a>

            <a href="javascript:void(0)" class="share-option-card" onclick="shareToTelegram()">
               <div class="share-option-icon telegram">
                  <i class="fab fa-telegram-plane"></i>
               </div>
               <p class="share-option-name">Telegram</p>
            </a>

            <a href="javascript:void(0)" class="share-option-card" onclick="shareToWhatsApp()">
               <div class="share-option-icon whatsapp">
                  <i class="fab fa-whatsapp"></i>
               </div>
               <p class="share-option-name">WhatsApp</p>
            </a>

            <a href="javascript:void(0)" class="share-option-card" onclick="copyToClipboard()">
               <div class="share-option-icon copy">
                  <i class="far fa-copy"></i>
               </div>
               <p class="share-option-name">Sao chép liên kết</p>
            </a>
         </div>
      </div>
   </div>
</div>
<script>
   // Hàm định dạng số lượt xem
   function formatViewCount(count) {
       if (count >= 1000000) {
           return (count / 1000000).toFixed(1) + 'M';
       } else if (count >= 1000) {
           return (count / 1000).toFixed(1) + 'K';
       } else {
           return count;
       }
   }
   
   $(document).ready(function() {
       // Kiểm tra cập nhật lượt xem từ sessionStorage
       const viewUpdates = JSON.parse(sessionStorage.getItem('viewUpdates') || '{}');
       const movieSlug = "{{$movie->slug}}";
       
       // Nếu có cập nhật lượt xem cho phim này
       if (viewUpdates[movieSlug]) {
           // Định dạng số lượt xem
           const formattedCount = formatViewCount(viewUpdates[movieSlug]);
           
           // Cập nhật lượt xem hiển thị
           $('.view-counter').text(formattedCount)
                              .addClass('updated');
           
           // Loại bỏ class 'updated' sau khi animation hoàn thành
           setTimeout(function() {
               $('.view-counter').removeClass('updated');
           }, 1000);
       }
       
       // Lắng nghe sự kiện cập nhật lượt xem từ các trang khác
       window.addEventListener('storage', function(e) {
           if (e.key === 'viewUpdates') {
               const updates = JSON.parse(e.newValue || '{}');
               if (updates[movieSlug] && updates[movieSlug] !== viewUpdates[movieSlug]) {
                   // Định dạng số lượt xem
                   const formattedCount = formatViewCount(updates[movieSlug]);
                   
                   // Cập nhật lượt xem hiển thị
                   $('.view-counter').text(formattedCount)
                                      .addClass('updated');
                   
                   // Loại bỏ class 'updated' sau khi animation hoàn thành
                   setTimeout(function() {
                       $('.view-counter').removeClass('updated');
                   }, 1000);
               }
           }
       });
   });
</script>
<script>
   // Hàm định dạng số lượt xem
function formatViewCount(count) {
    if (count >= 1000000) {
        return (count / 1000000).toFixed(1) + 'M';
    } else if (count >= 1000) {
        return (count / 1000).toFixed(1) + 'K';
    } else {
        return count;
    }
}

$(document).ready(function() {
    // Kiểm tra cập nhật lượt xem từ sessionStorage
    const viewUpdates = JSON.parse(sessionStorage.getItem('viewUpdates') || '{}');
    const movieSlug = "{{$movie->slug}}";

    // Nếu có cập nhật lượt xem cho phim này
    if (viewUpdates[movieSlug]) {
        // Định dạng số lượt xem
        const formattedCount = formatViewCount(viewUpdates[movieSlug]);

        // Cập nhật lượt xem hiển thị
        $('.view-counter').text(formattedCount)
            .addClass('updated');

        // Loại bỏ class 'updated' sau khi animation hoàn thành
        setTimeout(function() {
            $('.view-counter').removeClass('updated');
        }, 1000);
    }

    // Lắng nghe sự kiện cập nhật lượt xem từ các trang khác
    window.addEventListener('storage', function(e) {
        if (e.key === 'viewUpdates') {
            const updates = JSON.parse(e.newValue || '{}');
            if (updates[movieSlug] && updates[movieSlug] !== viewUpdates[movieSlug]) {
                // Định dạng số lượt xem
                const formattedCount = formatViewCount(updates[movieSlug]);

                // Cập nhật lượt xem hiển thị
                $('.view-counter').text(formattedCount)
                    .addClass('updated');

                // Loại bỏ class 'updated' sau khi animation hoàn thành
                setTimeout(function() {
                    $('.view-counter').removeClass('updated');
                }, 1000);
            }
        }
    });
});

// Xử lý thanh tương tác phim và modal
$(document).ready(function() {
    // Các biến thanh tương tác
    const favoriteBtn = $('#favoriteBtn');
    const actorsBtn = $('#actorsBtn');
    const shareBtn = $('#shareBtn');
    const commentBtn = $('#commentBtn');

    // Biến modal diễn viên
    const actorsModal = $('#actorsModal');
    const closeActorsModal = $('#closeActorsModal');
    
    // Biến modal chia sẻ
    const shareModal = $('#shareModal');
    const closeShareModal = $('#closeShareModal');

    // Kiểm tra trạng thái phim yêu thích từ localStorage
    const movieId = "{{$movie->id}}";
    const isFavorite = localStorage.getItem('favorite_' + movieId) === 'true';

    // Thiết lập trạng thái ban đầu cho nút yêu thích
    if (isFavorite) {
        favoriteBtn.addClass('active');
        favoriteBtn.find('i').removeClass('far').addClass('fas');
    }

    // Xử lý nút Yêu thích
    favoriteBtn.click(function() {
        $(this).toggleClass('active');

        if ($(this).hasClass('active')) {
            $(this).find('i').removeClass('far').addClass('fas');
            localStorage.setItem('favorite_' + movieId, 'true');
            // showToast('Đã thêm vào danh sách yêu thích');
        } else {
            $(this).find('i').removeClass('fas').addClass('far');
            localStorage.setItem('favorite_' + movieId, 'false');
            // showToast('Đã xóa khỏi danh sách yêu thích');
        }
    });

    // Xử lý nút diễn viên
    actorsBtn.click(function() {
        console.log("Nút diễn viên đã được click");
        actorsModal.addClass('active');
        $('body').css('overflow', 'hidden'); // Ngăn scroll trên body
    });

    // Đóng modal diễn viên
    closeActorsModal.click(function() {
        console.log("Nút đóng modal diễn viên đã được click");
        actorsModal.removeClass('active');
        $('body').css('overflow', ''); // Khôi phục scroll
    });

    // Đóng modal khi click vào vùng ngoài
    actorsModal.click(function(e) {
        if ($(e.target).is(actorsModal)) {
            actorsModal.removeClass('active');
            $('body').css('overflow', '');
        }
    });

    // Xử lý nút Chia sẻ
    shareBtn.click(function() {
        console.log("Nút chia sẻ đã được click");
        shareModal.addClass('active');
        $('body').css('overflow', 'hidden'); // Ngăn scroll trên body
    });

    // Đóng modal chia sẻ
    closeShareModal.click(function() {
        console.log("Nút đóng modal chia sẻ đã được click");
        shareModal.removeClass('active');
        $('body').css('overflow', ''); // Khôi phục scroll
    });

    // Đóng modal khi click vào vùng ngoài
    shareModal.click(function(e) {
        if ($(e.target).is(shareModal)) {
            shareModal.removeClass('active');
            $('body').css('overflow', '');
        }
    });

    // Đóng modal khi nhấn phím ESC
    $(document).keydown(function(e) {
        if (e.key === "Escape") {
            if (actorsModal.hasClass('active')) {
                actorsModal.removeClass('active');
                $('body').css('overflow', '');
            }
            if (shareModal.hasClass('active')) {
                shareModal.removeClass('active');
                $('body').css('overflow', '');
            }
        }
    });

    // Xử lý nút Bình luận
    commentBtn.click(function() {
        // Kích hoạt tab bình luận
        $('a[href="#movie-comments"]').tab('show');
        
        // Cuộn đến vị trí tab
        $('html, body').animate({
            scrollTop: $('.movie-details-tabs').offset().top - 70
        }, 500);
    });
    
    // Xử lý đánh giá phim
    $('.star').click(function() {
        // Thêm hiệu ứng pulse khi click
        $(this).addClass('pulse');
        
        // Xóa class pulse sau khi hiệu ứng hoàn thành
        setTimeout(() => {
            $(this).removeClass('pulse');
        }, 600);
        
        // Xử lý đánh giá ở đây...
        const rating = $(this).data('value');
        const movieId = $('#movie-rating-system-popup').data('movie-id');
        
        // Gửi đánh giá đến server - giả lập
        console.log(`Đánh giá ${rating} sao cho phim ID: ${movieId}`);
        
        // Hiển thị phản hồi
        $('#rating-feedback-popup')
            .addClass('success')
            .text('Cảm ơn bạn đã đánh giá phim!')
            .slideDown();
        
        // Ẩn phản hồi sau 3 giây
        setTimeout(() => {
            $('#rating-feedback-popup').slideUp();
        }, 3000);
    });

    // Hover effect cho star
    $('.star').hover(
        function() {
            const value = $(this).data('value');
            
            // Thêm class hover cho star hiện tại và các star trước đó
            $('.star').each(function() {
                if ($(this).data('value') <= value) {
                    $(this).addClass('hover');
                } else {
                    $(this).removeClass('hover');
                }
            });
        },
        function() {
            // Xóa tất cả class hover khi hết hover
            $('.star').removeClass('hover');
        }
    );
});

// Hàm chia sẻ
function shareToFacebook() {
    const url = encodeURIComponent(window.location.href);
    const title = encodeURIComponent("{{$movie->title}}");
    window.open(`https://www.facebook.com/sharer/sharer.php?u=${url}`, '_blank');
    $('#shareModal').removeClass('active');
    $('body').css('overflow', '');
    showToast('Đã mở cửa sổ chia sẻ Facebook');
}

function shareToTwitter() {
    const url = encodeURIComponent(window.location.href);
    const title = encodeURIComponent("{{$movie->title}}");
    window.open(`https://twitter.com/intent/tweet?text=${title}&url=${url}`, '_blank');
    $('#shareModal').removeClass('active');
    $('body').css('overflow', '');
    showToast('Đã mở cửa sổ chia sẻ Twitter');
}

function shareToTelegram() {
    const url = encodeURIComponent(window.location.href);
    const title = encodeURIComponent("{{$movie->title}}");
    window.open(`https://t.me/share/url?url=${url}&text=${title}`, '_blank');
    $('#shareModal').removeClass('active');
    $('body').css('overflow', '');
    showToast('Đã mở cửa sổ chia sẻ Telegram');
}

function shareToWhatsApp() {
    const url = encodeURIComponent(window.location.href);
    const title = encodeURIComponent("{{$movie->title}}");
    window.open(`https://wa.me/?text=${title} ${url}`, '_blank');
    $('#shareModal').removeClass('active');
    $('body').css('overflow', '');
    showToast('Đã mở cửa sổ chia sẻ WhatsApp');
}

function copyToClipboard() {
    const url = window.location.href;
    
    // Sử dụng Clipboard API nếu có hỗ trợ
    if (navigator.clipboard) {
        navigator.clipboard.writeText(url)
            .then(() => {
                showToast('Đã sao chép liên kết vào clipboard');
            })
            .catch(err => {
                showToast('Không thể sao chép: ' + err);
            });
    } else {
        // Fallback cho trình duyệt cũ
        const textArea = document.createElement("textarea");
        textArea.value = url;
        document.body.appendChild(textArea);
        textArea.select();
        document.execCommand('copy');
        document.body.removeChild(textArea);
        showToast('Đã sao chép liên kết vào clipboard');
    }
    
    $('#shareModal').removeClass('active');
    $('body').css('overflow', '');
}

// Hàm hiển thị thông báo
function showToast(message) {
    // Kiểm tra xem đã có container toast chưa
    if ($('.toast-container').length === 0) {
        $('body').append('<div class="toast-container"></div>');
    }

    // Tạo toast mới
    const toast = $(`
    <div class="toast">
      <div class="toast-content">${message}</div>
    </div>
    `);

    // Thêm toast vào container
    $('.toast-container').append(toast);

    // Hiển thị toast với hiệu ứng và tự động ẩn sau 3 giây
    setTimeout(() => {
        toast.addClass('show');
        setTimeout(() => {
            toast.removeClass('show');
            setTimeout(() => {
                toast.remove();
            }, 300);
        }, 3000);
    }, 10);
}

// Thêm CSS cho toast nếu chưa có
$(document).ready(function() {
    // Kiểm tra xem đã có CSS cho toast chưa
    if ($('#toast-styles').length === 0) {
        $('head').append(`
        <style id="toast-styles">
            .toast-container {
                position: fixed;
                bottom: 20px;
                right: 20px;
                z-index: 9999;
            }
            .toast {
                background: rgba(33, 43, 54, 0.9);
                color: #fff;
                padding: 12px 18px;
                border-radius: 6px;
                box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
                margin-top: 10px;
                transform: translateY(30px);
                opacity: 0;
                transition: all 0.3s ease;
            }
            .toast.show {
                transform: translateY(0);
                opacity: 1;
            }
        </style>
        `);
    }
});

</script>

<!-- Thêm script xử lý các nút tương tác vào cuối file, trước </body> -->
<script>
   $(document).ready(function() {
  // Xử lý nút bình luận
  $('#commentBtn').click(function(e) {
    e.preventDefault();
    // Kích hoạt tab bình luận
    $('a[href="#movie-comments"]').tab('show');
    
    // Cuộn đến vị trí tab
    $('html, body').animate({
      scrollTop: $('.movie-details-tabs').offset().top - 70
    }, 500);
  });
  
  // Xử lý nút trailer
  $('a.watch-trailer-btn').click(function(e) {
    e.preventDefault();
    // Kích hoạt tab trailer
    $('a[href="#movie-trailer"]').tab('show');
    
    // Cuộn đến vị trí tab
    $('html, body').animate({
      scrollTop: $('.movie-details-tabs').offset().top - 70
    }, 500);
  });
  
  // Xử lý link đọc thêm nội dung phim
  $('.read-more').click(function(e) {
    e.preventDefault();
    // Kích hoạt tab nội dung phim
    $('a[href="#movie-desc"]').tab('show');
    
    // Cuộn đến vị trí tab
    $('html, body').animate({
      scrollTop: $('.movie-details-tabs').offset().top - 70
    }, 500);
  });
});
</script>

<script>
   // Xử lý các chức năng bình luận
$(document).ready(function() {
   // Xử lý nút trả lời bình luận
   $(document).on('click', '.reply-btn', function() {
      var commentId = $(this).data('comment-id');
      $('#reply-form-' + commentId).slideToggle();
   });
   
   // Xử lý nút hủy trả lời
   $(document).on('click', '.cancel-reply', function() {
      var commentId = $(this).data('comment-id');
      $('#reply-form-' + commentId).slideUp();
   });
   
   // Xử lý nút sửa bình luận
   $(document).on('click', '.edit-btn', function() {
      var commentId = $(this).data('comment-id');
      $('#edit-form-' + commentId).slideToggle();
   });
   
   // Xử lý nút hủy sửa
   $(document).on('click', '.cancel-edit', function() {
      var commentId = $(this).data('comment-id');
      $('#edit-form-' + commentId).slideUp();
   });
});
</script>

@endsection

<script>
   // Script mới để xử lý phim yêu thích với database
$(document).ready(function() {
    const favoriteBtn = $('#favoriteBtn');
    const movieId = favoriteBtn.data('movie-id');
    
    // Kiểm tra phim đã yêu thích chưa khi trang tải
    checkFavoriteStatus();
    
    // Xử lý khi click vào nút yêu thích
    favoriteBtn.click(function() {
        @if(Auth::check())
            toggleFavorite();
        @else
            // Hiển thị modal đăng nhập nếu chưa đăng nhập
            $('#loginModal').modal('show');
            showToast('Vui lòng đăng nhập để sử dụng tính năng này');
        @endif
    });
    
    // Hàm kiểm tra trạng thái yêu thích
    function checkFavoriteStatus() {
        @if(Auth::check())
            $.ajax({
                url: '{{ route("favorites.check") }}',
                type: 'GET',
                data: {
                    movie_id: movieId
                },
                success: function(response) {
                    if (response.is_favorite) {
                        favoriteBtn.addClass('active');
                        favoriteBtn.find('i').removeClass('far').addClass('fas');
                    } else {
                        favoriteBtn.removeClass('active');
                        favoriteBtn.find('i').removeClass('fas').addClass('far');
                    }
                }
            });
        @endif
    }
    
    // Hàm toggle yêu thích
    function toggleFavorite() {
        $.ajax({
            url: '{{ route("favorites.add") }}',
            type: 'POST',
            data: {
                movie_id: movieId,
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                if (response.status === 'added') {
                    favoriteBtn.addClass('active');
                    favoriteBtn.find('i').removeClass('far').addClass('fas');
                    showToast(response.message);
                } else {
                    favoriteBtn.removeClass('active');
                    favoriteBtn.find('i').removeClass('fas').addClass('far');
                    showToast(response.message);
                }
            },
            error: function() {
                showToast('Có lỗi xảy ra, vui lòng thử lại sau');
            }
        });
    }
});
</script>

<script>
   // Biến toàn cục cho favorites.js
const isLoggedIn = @json(Auth::check());
const csrfToken = '{{ csrf_token() }}';
const checkFavoriteUrl = '{{ route("favorites.check") }}';
const toggleFavoriteUrl = '{{ route("favorites.add") }}';

// Hàm hiển thị toast message
function showToast(message) {
    // Kiểm tra xem đã tạo container cho toast chưa
    if ($('.toast-container').length === 0) {
        $('body').append('<div class="toast-container"></div>');
    }

    // Tạo toast mới
    const toast = $(`
    <div class="toast">
      <div class="toast-content">${message}</div>
    </div>
    `);

    // Thêm toast vào container
    $('.toast-container').append(toast);

    // Hiển thị toast với hiệu ứng và tự động ẩn sau 3 giây
    setTimeout(() => {
        toast.addClass('show');
        setTimeout(() => {
            toast.removeClass('show');
            setTimeout(() => {
                toast.remove();
            }, 300);
        }, 3000);
    }, 10);
}
</script>

<script src="{{ asset('js/favorites.js') }}"></script>