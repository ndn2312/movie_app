@extends('layout')
@include('partials.loading-screen')

@section('content')
<style>
   /* ==================== RESET & BASE STYLES ==================== */
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

   .episode {
      position: absolute;
      top: 10px;
      right: 10px;
      z-index: 10;
      background: none;
      padding: 0;
      margin: 0;
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
      animation: pulse 2s infinite;
   }

   @keyframes pulse {
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

   /* CSS cho bảng phím tắt */
   .keyboard-shortcuts-guide {
      margin-bottom: 15px;
      position: relative;
      font-size: 14px;
   }

   /* CSS tùy chỉnh giao diện thời gian Plyr */
   .plyr--full-ui .plyr__time {
      font-family: monospace;
      font-size: 14px !important;
      padding: 0 5px;
   }

   .plyr--full-ui .plyr__time+.plyr__time {
      margin-left: 0 !important;
   }

   .plyr--full-ui .plyr__time+.plyr__time::before {
      content: '/';
      margin-right: 5px;
      margin-left: 5px;
      color: rgba(255, 255, 255, 0.6);
   }

   /* Tùy chỉnh thanh tiến độ video */
   .plyr--video .plyr__progress__buffer {
      color: rgba(255, 255, 255, 0.25);
   }

   .plyr--full-ui input[type=range] {
      color: #04c1e7;
   }

   .plyr--video .plyr__control:hover {
      background: rgba(4, 193, 231, 0.7);
   }

   /* Tùy chỉnh vùng hiển thị thời gian khi hover vào thanh tiến độ */
   .plyr__tooltip {
      background: rgba(0, 0, 0, 0.8);
      border-radius: 4px;
      font-size: 13px;
      font-weight: 500;
      line-height: 1.3;
      padding: 5px 10px;
   }

   /* CSS cho modal và thông báo */
   .custom-notification {
      animation: fadeInUp 0.3s ease-out;
   }

   @keyframes fadeInUp {
      from {
         transform: translateY(20px);
         opacity: 0;
      }

      to {
         transform: translateY(0);
         opacity: 1;
      }
   }

   .modal-content {
      animation: zoomIn 0.3s ease-out;
   }

   @keyframes zoomIn {
      from {
         transform: scale(0.8);
         opacity: 0;
      }

      to {
         transform: scale(1);
         opacity: 1;
      }
   }

   #resumeWatchingBtn:hover {
      background: linear-gradient(135deg, #04c1e7 0%, #03636f 100%) !important;
      transform: translateY(-2px);
      transition: all 0.3s ease;
   }

   #restartWatchingBtn:hover {
      background: rgba(255, 255, 255, 0.2) !important;
      transform: translateY(-2px);
      transition: all 0.3s ease;
   }

   /* CSS cho bảng phím tắt */
   .keyboard-shortcuts-guide {
      margin-bottom: 15px;
      position: relative;
      font-size: 14px;
   }

   .toggle-shortcuts-guide {
      display: inline-flex;
      align-items: center;
      background: rgba(4, 193, 231, 0.1);
      padding: 5px 12px;
      border-radius: 20px;
      cursor: pointer;
      color: #04c1e7;
      font-size: 13px;
      font-weight: 500;
      margin-top: 10px;
      transition: all 0.2s ease;
      border: 1px solid rgba(4, 193, 231, 0.2);
   }

   .toggle-shortcuts-guide:hover {
      background: rgba(4, 193, 231, 0.2);
   }

   .toggle-shortcuts-guide i {
      margin-right: 5px;
   }

   .shortcuts-panel {
      position: absolute;
      width: 300px;
      background: rgba(27, 46, 67, 0.95);
      border-radius: 8px;
      box-shadow: 0 5px 20px rgba(0, 0, 0, 0.3);
      z-index: 100;
      border: 1px solid rgba(255, 255, 255, 0.1);
      left: 0;
      top: 40px;
      overflow: hidden;
      transition: all 0.3s ease;
   }

   .shortcuts-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 12px 15px;
      border-bottom: 1px solid rgba(255, 255, 255, 0.1);
   }

   .shortcuts-header h5 {
      margin: 0;
      color: #fff;
      font-size: 16px;
      font-weight: 600;
   }

   .close-shortcuts {
      color: #ddd;
      cursor: pointer;
      font-size: 20px;
      line-height: 1;
   }

   .close-shortcuts:hover {
      color: #fff;
   }

   .shortcuts-body {
      padding: 10px 0;
   }

   .shortcut-item {
      display: flex;
      align-items: center;
      padding: 8px 15px;
      transition: background 0.2s ease;
   }

   .shortcut-item:hover {
      background: rgba(255, 255, 255, 0.05);
   }

   .shortcut-item .key {
      display: inline-block;
      min-width: 32px;
      height: 32px;
      line-height: 32px;
      text-align: center;
      background: rgba(255, 255, 255, 0.1);
      border-radius: 4px;
      margin-right: 15px;
      font-weight: 600;
      color: #fff;
      border: 1px solid rgba(255, 255, 255, 0.2);
   }

   .shortcut-item .action {
      color: #ddd;
   }

   /* ==================== VIDEO PLAYER STYLES ==================== */
   .iframe-video {
      position: relative;
      padding-bottom: 56.25%;
      /* 16:9 Aspect Ratio */
      height: 0;
      background-color: #000;
      margin-bottom: 20px;
      box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
      border-radius: 8px;
      overflow: hidden;
   }

   .iframe-video iframe,
   .iframe-video video,
   .iframe-video .plyr__video-embed {
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      border: none;
      object-fit: contain;
   }
</style>


<div class="row container" id="wrapper">
   <div class="halim-panel-filter">
      <div class="panel-heading">
         <div class="row">
            <div class="col-xs-6">
               <div class="yoast_breadcrumb hidden-xs"><span><span><a
                           href="{{route('movie', $movie->slug)}}">{{$movie->title}}</a> » <span><a
                              href="{{route('country',[$movie->country->slug])}}">{{$movie->country->title}}</a> » <span
                              class="breadcrumb_last" aria-current="page">{{$movie->title}}</span></span></span></span>
               </div>
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
            <div class="iframe-video" data-episode-id="{{ $movie->id }}_{{ $tapphim }}">
               @if(isset($episode) && $episode)
               {!!$episode->linkphim!!}
               @else
               <div
                  style="background: #000; display: flex; align-items: center; justify-content: center; height: 400px; border-radius: 8px;">
                  <div class="text-center">
                     <i class="fas fa-exclamation-triangle"
                        style="font-size: 50px; color: #f8c200; margin-bottom: 20px;"></i>
                     <h4 style="color: #fff; font-size: 18px;">Không tìm thấy tập phim này</h4>
                     <p style="color: #aaa; margin: 10px 0 20px;">Tập phim này có thể đã bị xóa hoặc thay đổi</p>
                     <a href="{{ route('movie', $movie->slug) }}" class="btn btn-primary">
                        <i class="fas fa-film"></i> Xem các tập khác
                     </a>
                  </div>
               </div>
               @endif
            </div>

            <!-- Keyboard Shortcuts Guide -->
            <div class="keyboard-shortcuts-guide">
               <div class="toggle-shortcuts-guide">
                  <i class="fas fa-keyboard"></i> <span>Phím tắt</span>
               </div>
               <div class="shortcuts-panel" style="display: none;">
                  <div class="shortcuts-header">
                     <h5>Phím tắt trình phát</h5>
                     <span class="close-shortcuts">&times;</span>
                  </div>
                  <div class="shortcuts-body">
                     <div class="shortcut-item">
                        <span class="key"><i class="fas fa-arrow-left"></i></span>
                        <span class="action">Lùi 10 giây</span>
                     </div>
                     <div class="shortcut-item">
                        <span class="key"><i class="fas fa-arrow-right"></i></span>
                        <span class="action">Tiến 10 giây</span>
                     </div>
                     <div class="shortcut-item">
                        <span class="key">Space</span>
                        <span class="action">Phát/Tạm dừng</span>
                     </div>
                     <div class="shortcut-item">
                        <span class="key">F</span>
                        <span class="action">Toàn màn hình</span>
                     </div>
                     <div class="shortcut-item">
                        <span class="key">M</span>
                        <span class="action">Tắt/Bật âm thanh</span>
                     </div>
                  </div>
               </div>
            </div>

            <div class="clearfix"></div>
            <div class="clearfix"></div>
            <div class="title-block">
               <a href="javascript:;" data-toggle="tooltip" title="Add to bookmark">
                  {{-- <div id="bookmark" class="bookmark-img-animation primary_ribbon" data-id="37976">
                     <div class="halim-pulse-ring"></div>
                  </div> --}}
               </a>
               <div class="title-wrapper-xem full">
                  <h1 class="entry-title"><a href="" title="{{$movie->title}}" class="tl">{{$movie->title}}</a></h1>
               </div>
            </div>
            <div class="entry-content htmlwrap clearfix collapse" id="expand-post-content">
               <article id="post-37976" class="item-content post-37976"></article>
            </div>
            <div class="clearfix"></div>
            <div class="text-center">
               <div id="halim-ajax-list-server"></div>
            </div>
            <div id="halim-list-server">
               <ul class="nav nav-tabs" role="tablist">
                  @if($movie->resolution==0)
                  {{-- <span class="tag-base hd-tag"> --}}
                     <li role="presentation" class="active server-1"><a href="#server-0" aria-controls="server-0"
                           role="tab" data-toggle="tab"><i class="hl-server"></i> HD</a></li>

                     {{--
                  </span> --}}
                  @elseif($movie->resolution==1)
                  {{-- <span class="tag-base sd-tag"> --}}
                     <li role="presentation" class="active server-1"><a href="#server-0" aria-controls="server-0"
                           role="tab" data-toggle="tab"><i class="hl-server"></i> SD</a></li>

                     {{--
                  </span> --}}
                  @elseif($movie->resolution==2)
                  {{-- <span class="tag-base hdcam-tag"> --}}
                     <li role="presentation" class="active server-1"><a href="#server-0" aria-controls="server-0"
                           role="tab" data-toggle="tab"><i class="hl-server"></i> HDCam</a></li>

                     {{--
                  </span> --}}
                  @elseif($movie->resolution==3)
                  {{-- <span class="tag-base cam-tag"> --}}
                     <li role="presentation" class="active server-1"><a href="#server-0" aria-controls="server-0"
                           role="tab" data-toggle="tab"><i class="hl-server"></i> Cam</a></li>

                     {{--
                  </span> --}}
                  @elseif($movie->resolution==4)
                  {{-- <span class="tag-base fullhd-tag"> --}}
                     <li role="presentation" class="active server-1"><a href="#server-0" aria-controls="server-0"
                           role="tab" data-toggle="tab"><i class="hl-server"></i> FullHD</a></li>

                     {{--
                  </span> --}}
                  @else
                  {{-- <span class="tag-base trailer-tag"> --}}
                     <li role="presentation" class="active server-1"><a href="#server-0" aria-controls="server-0"
                           role="tab" data-toggle="tab"><i class="hl-server"></i> Trailer</a></li>

                     {{--
                  </span> --}}
                  @endif
                  <li role="presentation" class="active server-1"><a href="#server-0" aria-controls="server-0"
                        role="tab" data-toggle="tab"><i class="hl-server"></i> Vietsub</a></li>
               </ul>
               <div class="tab-content">
                  <div role="tabpanel" class="tab-pane active server-1" id="server-0">
                     <div class="halim-server">
                        <ul class="halim-list-eps">
                           @foreach($movie->episode as $key=>$sotap)
                           <a href="{{url('xem-phim/'.$movie->slug.'/tap-'.$sotap->episode)}}">
                              <li class="halim-episode"><span
                                    class="halim-btn halim-btn-2 {{$tapphim==$sotap->episode ? 'active' :''}} halim-info-1-1 box-shadow"
                                    data-post-id="37976" data-server="1" data-episode="1" data-position="first"
                                    data-embed="0"
                                    data-title="Xem phim {{$movie->title}} - Tập {{$sotap->episode}} - {{$movie->name_eng}} - vietsub + Thuyết Minh"
                                    data-h1="{{$movie->title}} - tập {{$sotap->episode}}">{{$sotap->episode}}</span>
                              </li>
                           </a>
                           @endforeach


                        </ul>
                        <div class="clearfix"></div>
                     </div>
                  </div>
               </div>
            </div>
            <div class="clearfix"></div>
            <div class="htmlwrap clearfix">
               <div id="lightout"></div>
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

<!-- Modal Tiếp tục xem phim -->
<div class="modal fade" id="resumeModal" tabindex="-1" role="dialog" aria-labelledby="resumeModalLabel"
   aria-hidden="true">
   <div class="modal-dialog modal-sm modal-dialog-centered" role="document"
      style="margin: 0 auto; position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%);">
      <div class="modal-content"
         style="background: rgba(27, 46, 67, 0.95); border-radius: 10px; border: 1px solid rgba(255, 255, 255, 0.1);">
         <div class="modal-header" style="border-bottom: 1px solid rgba(255, 255, 255, 0.1); padding: 15px;">
            <h5 class="modal-title" id="resumeModalLabel"
               style="color: #fff; font-weight: 600; margin: 0 auto; font-size: 18px;">Tiếp tục xem phim?</h5>
         </div>
         <div class="modal-body text-center" style="padding: 20px 15px;">
            <div style="margin-bottom: 15px;">
               <i class="fas fa-history" style="font-size: 32px; color: #04c1e7; margin-bottom: 15px;"></i>
               <p style="color: #ddd; font-size: 14px; margin-bottom: 5px;">Bạn đã xem phim này trước đó</p>
               <p style="color: #fff; font-size: 16px; font-weight: 600;">Tiếp tục từ: <span id="resumeTimeDisplay"
                     style="color: #ff9800;"></span></p>
            </div>
            <div class="row" style="margin-top: 20px;">
               <div class="col-xs-6">
                  <button type="button" id="resumeWatchingBtn" class="btn btn-block"
                     style="background: linear-gradient(135deg, #03f5fd 0%, #0f6060 100%); color: #fff; border-radius: 30px; padding: 8px; font-weight: 600; border: none;">
                     <i class="fas fa-play-circle"></i> Tiếp tục
                  </button>
               </div>
               <div class="col-xs-6">
                  <button type="button" id="restartWatchingBtn" class="btn btn-block"
                     style="background: rgba(255, 255, 255, 0.1); color: #ddd; border-radius: 30px; padding: 8px; font-weight: 600; border: none;">
                     <i class="fas fa-redo"></i> Xem lại
                  </button>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>

<script>
   $(document).ready(function() {
       // Lấy ID phim từ dữ liệu trang
       const movieId = "{{ $movie->id }}";
       const episodeSlug = "{{ $tapphim }}";
       const movieSlug = "{{ $movie->slug }}";
       const viewedKey = "viewed_movie_" + movieId;
       const isAuthenticated = {{ Auth::check() ? 'true' : 'false' }};
       let player = null;
       let playerReady = false;
       let currentTime = 0;
       let duration = 0;
       let watchIntervalId = null;
       let viewTimer = null;
       
       // Chỉ đếm lượt xem nếu chưa đếm trong phiên này
       if (localStorage.getItem(viewedKey) !== "1") {
           // Đặt timer 5 phút
           viewTimer = setTimeout(function() {
               $.ajax({
                   url: "{{ route('increment.view') }}",
                   type: "POST",
                   data: {
                       movie_id: movieId,
                       _token: "{{ csrf_token() }}"
                   },
                   success: function(response) {
                       console.log("Đã tính lượt xem sau 5 phút xem phim");
                       
                       // Đánh dấu đã đếm lượt xem cho phim này trong localStorage
                       localStorage.setItem(viewedKey, "1");
                       
                       // Cập nhật số lượt xem ở tất cả vị trí trên trang
                       updateViewCountDisplay(movieSlug, response.count_views);
                       
                       // Đặt thời hạn 24h cho việc đánh dấu đã xem
                       setTimeout(function() {
                           localStorage.removeItem(viewedKey);
                       }, 24 * 60 * 60 * 1000);
                   },
                   error: function(xhr) {
                       console.error("Lỗi khi tính lượt xem", xhr);
                   }
               });
           }, 300000); // 300000ms = 5 phút
           
           // Hủy timer nếu người dùng rời đi trước 5 phút
           $(window).on('beforeunload', function() {
               if (viewTimer) {
                   clearTimeout(viewTimer);
               }
               
               // Lưu tiến độ xem trước khi đóng trang
               saveWatchingProgress();
           });
       }
       
       // Hàm định dạng thời gian (chuyển giây thành hh:mm:ss)
       function formatTime(seconds) {
           const h = Math.floor(seconds / 3600);
           const m = Math.floor((seconds % 3600) / 60);
           const s = Math.floor(seconds % 60);
           
           return [
               h > 0 ? h : null,
               h > 0 ? (m < 10 ? '0' + m : m) : m,
               s < 10 ? '0' + s : s
           ].filter(Boolean).join(':');
       }
       
       // Hàm lấy tiến độ xem từ server cho người dùng đã đăng nhập
       function getWatchingProgressFromServer() {
           if (!isAuthenticated) return;
           
           $.ajax({
               url: "{{ url('/history/get') }}/" + movieId + "/" + episodeSlug,
               type: "GET",
               headers: {
                   'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
               },
               success: function(response) {
                   if (response.success && response.history) {
                       const savedTime = response.history.current_time;
                       
                       // Hiển thị thời gian đã lưu trong modal
                       $('#resumeTimeDisplay').text(formatTime(savedTime));
                       
                       // Hiển thị modal tiếp tục xem
                       setTimeout(function() {
                           $('#resumeModal').modal('show');
                       }, 800);
                       
                       // Xử lý sự kiện khi người dùng nhấn "Tiếp tục xem"
                       $('#resumeWatchingBtn').on('click', function() {
                           // Đóng modal trước
                           $('#resumeModal').modal('hide');
                           
                           // Chờ 500ms để modal đóng hoàn toàn rồi mới seek
                           setTimeout(function() {
                               if (player) {
                                   player.currentTime = parseFloat(savedTime);
                                   player.play().catch(error => {
                                       console.error("Lỗi khi phát video: ", error);
                                   });
                               }
                           }, 500);
                       });
                       
                       // Xử lý sự kiện khi người dùng nhấn "Xem lại từ đầu"
                       $('#restartWatchingBtn').on('click', function() {
                           $('#resumeModal').modal('hide');
                           
                           setTimeout(function() {
                               if (player) {
                                   player.currentTime = 0;
                                   player.play().catch(error => {
                                       console.error("Lỗi khi phát video: ", error);
                                   });
                               }
                           }, 500);
                       });
                   } else {
                       console.log("Không có lịch sử xem cho phim này");
                   }
               },
               error: function(xhr) {
                   // Có thể không có lịch sử xem hoặc lỗi khác
                   console.log("Không tìm thấy lịch sử xem", xhr);
                   
                   // Nếu không có lịch sử trên server, kiểm tra localStorage
                   checkLocalStorage();
               }
           });
       }
       
       // Kiểm tra localStorage (dành cho người dùng chưa đăng nhập hoặc khi server không có dữ liệu)
       function checkLocalStorage() {
           const savedTime = localStorage.getItem('movie_{{$movie->id}}_{{$tapphim}}_time');
           if (savedTime && savedTime > 10) {
               // Hiển thị thời gian đã lưu trong modal
               $('#resumeTimeDisplay').text(formatTime(savedTime));
               
               // Hiển thị modal tiếp tục xem
               setTimeout(function() {
                   $('#resumeModal').modal('show');
               }, 800);
               
               // Xử lý sự kiện khi người dùng nhấn "Tiếp tục xem"
               $('#resumeWatchingBtn').on('click', function() {
                   // Đóng modal trước
                   $('#resumeModal').modal('hide');
                   
                   // Chờ 500ms để modal đóng hoàn toàn rồi mới seek
                   setTimeout(function() {
                       if (player) {
                           player.currentTime = parseFloat(savedTime);
                           player.play().catch(error => {
                               console.error("Lỗi khi phát video: ", error);
                           });
                       }
                   }, 500);
               });
               
               // Xử lý sự kiện khi người dùng nhấn "Xem lại từ đầu"
               $('#restartWatchingBtn').on('click', function() {
                   localStorage.removeItem('movie_{{$movie->id}}_{{$tapphim}}_time');
                   $('#resumeModal').modal('hide');
                   
                   setTimeout(function() {
                       if (player) {
                           player.currentTime = 0;
                           player.play().catch(error => {
                               console.error("Lỗi khi phát video: ", error);
                           });
                       }
                   }, 500);
               });
           }
       }
       
       // Hàm lưu tiến độ xem lên server
       function saveWatchingProgress() {
           if (!isAuthenticated || !player || player.currentTime < 10) return;
           
           currentTime = player.currentTime;
           duration = player.duration || 0;
           
           // Không lưu nếu thời gian xem quá ngắn
           if (currentTime < 10) return;
           
           // Lưu lên server nếu đã đăng nhập
           if (isAuthenticated) {
               $.ajax({
                   url: "{{ route('history.save') }}",
                   type: "POST",
                   data: {
                       movie_id: movieId,
                       episode: episodeSlug,
                       current_time: currentTime,
                       duration: duration,
                       _token: "{{ csrf_token() }}"
                   },
                   success: function(response) {
                       console.log("Đã lưu tiến độ xem lên server", response);
                   },
                   error: function(xhr) {
                       console.error("Lỗi khi lưu tiến độ xem", xhr);
                       
                       // Fallback: lưu vào localStorage nếu lưu server thất bại
                       localStorage.setItem('movie_{{$movie->id}}_{{$tapphim}}_time', currentTime);
                   }
               });
           } else {
               // Lưu vào localStorage cho người dùng chưa đăng nhập
               localStorage.setItem('movie_{{$movie->id}}_{{$tapphim}}_time', currentTime);
           }
       }
       
       // Setup theo dõi player và lưu tiến độ định kỳ
       function setupProgressTracking(plyrInstance) {
           player = plyrInstance;
           
           // Lắng nghe sự kiện timeupdate để cập nhật thời gian hiện tại
           player.on('timeupdate', function() {
               currentTime = player.currentTime;
               duration = player.duration || 0;
           });
           
           // Lưu tiến độ định kỳ mỗi 30 giây
           watchIntervalId = setInterval(saveWatchingProgress, 30000);
           
           // Lưu tiến độ khi dừng video
           player.on('pause', saveWatchingProgress);
           
           // Lưu tiến độ khi kết thúc video
           player.on('ended', function() {
               // Khi video kết thúc, đặt thời gian hiện tại về 0 để lần sau xem lại từ đầu
               if (isAuthenticated) {
                   $.ajax({
                       url: "{{ route('history.save') }}",
                       type: "POST",
                       data: {
                           movie_id: movieId,
                           episode: episodeSlug,
                           current_time: 0,
                           duration: duration,
                           _token: "{{ csrf_token() }}"
                       }
                   });
               } else {
                   localStorage.removeItem('movie_{{$movie->id}}_{{$tapphim}}_time');
               }
           });
       }
   
       // Lấy tiến độ xem khi trang load xong
       if (isAuthenticated) {
           getWatchingProgressFromServer();
       } else {
           checkLocalStorage();
       }
       
       // Đăng ký hàm setup với player
       document.addEventListener('plyr_ready', function(e) {
           console.log('Plyr đã sẵn sàng, thiết lập theo dõi tiến độ');
           setupProgressTracking(e.detail.player);
       });
       
       // Cleanup khi rời trang
       $(window).on('beforeunload', function() {
           if (watchIntervalId) {
               clearInterval(watchIntervalId);
           }
           saveWatchingProgress();
       });
   });
   
   // Hàm cập nhật hiển thị lượt xem trên toàn trang
   function updateViewCountDisplay(movieSlug, newCount) {
       // Cập nhật lượt xem trên trang hiện tại
       $('.view-counter').text(newCount + ' lượt xem');
       
       // Cập nhật lượt xem trong sidebar hot items
       $('#sidebar .popular-post .item').each(function() {
           const itemHref = $(this).find('a').attr('href');
           if (itemHref && itemHref.includes('/phim/' + movieSlug)) {
               $(this).find('.viewsCount').text(newCount + ' lượt xem');
           }
       });
       
       // Lưu vào sessionStorage để các trang khác có thể đồng bộ
       const viewUpdates = JSON.parse(sessionStorage.getItem('viewUpdates') || '{}');
       viewUpdates[movieSlug] = newCount;
       sessionStorage.setItem('viewUpdates', JSON.stringify(viewUpdates));
   }
</script>

<!-- Script khởi tạo Plyr cho video HLS -->
<script>
   document.addEventListener('DOMContentLoaded', function() {
    // Tìm video HLS trong trang watch
    const hlsContainer = document.querySelector('.iframe-video');
    let player = null;
    let playerReady = false;
    let pendingSeek = null;
    let hlsInstance = null;
    
    // Kiểm tra nếu là iframe nhúng thì không khởi tạo Plyr
    const hasEmbed = hlsContainer && hlsContainer.querySelector('iframe');
    if (hasEmbed) {
        // Nếu là iframe thì không cần khởi tạo player, bỏ qua các bước tiếp theo
        console.log("Phát hiện iframe nhúng, bỏ qua việc khởi tạo Plyr");
        return;
    }
    
    // Hàm định dạng thời gian (chuyển giây thành hh:mm:ss)
    function formatTime(seconds) {
        const h = Math.floor(seconds / 3600);
        const m = Math.floor((seconds % 3600) / 60);
        const s = Math.floor(seconds % 60);
        
        return [
            h > 0 ? h : null,
            h > 0 ? (m < 10 ? '0' + m : m) : m,
            s < 10 ? '0' + s : s
        ].filter(Boolean).join(':');
    }
    
    // Hàm nhảy đến thời điểm cụ thể và phát video
    function seekToTime(time) {
        if (player && playerReady) {
            console.log("Đang nhảy đến: " + time + " giây");
            
            // Đảm bảo HLS đã tải xong trước khi seek
            try {
                player.currentTime = time;
                player.play().then(() => {
                    showNotification('Đang tiếp tục phát từ ' + formatTime(time), 'success');
                }).catch(error => {
                    console.error("Lỗi khi phát video: ", error);
                    showNotification('Có lỗi khi phát video', 'error');
                });
            } catch (error) {
                console.error("Lỗi khi thiết lập thời gian: ", error);
                // Thử lại sau 1 giây
                setTimeout(() => {
                    player.currentTime = time;
                    player.play();
                }, 1000);
            }
        } else {
            console.log("Player chưa sẵn sàng, đợi...");
            pendingSeek = time;
        }
    }
    
    if (hlsContainer) {
        const hlsVideo = hlsContainer.querySelector('video.hls-video');
        
        // Nếu có video HLS
        if (hlsVideo) {
            const videoSource = hlsVideo.querySelector('source');
            if (videoSource && videoSource.src.includes('.m3u8')) {
                // Cấu hình tùy chọn cho HLS player
                const hlsOptions = {
                    controls: [
                        'play-large', 'play', 'rewind', 'fast-forward', 'progress', 
                        'current-time', 'duration', 'mute', 'volume', 'settings', 
                        'pip', 'airplay', 'fullscreen'
                    ],
                    seekTime: 10,
                    keyboard: { focused: false, global: false },
                    i18n: {
                        rewind: 'Lùi {seektime} giây',
                        fastForward: 'Tiến {seektime} giây',
                        seekLabel: '{currentTime} / {duration}'
                    },
                    settings: ['captions', 'quality', 'speed'],
                    onReady: function(plyrInstance, hls) {
                        // Lưu đối tượng player để sử dụng sau này
                        player = plyrInstance;
                        playerReady = true;
                        
                        // Thông báo player đã sẵn sàng thông qua sự kiện tùy chỉnh
                        document.dispatchEvent(new CustomEvent('plyr_ready', { 
                            detail: { 
                                player: plyrInstance,
                                hls: hls
                            }
                        }));
                        
                        // Nếu có seek đang chờ xử lý, thực hiện nó
                        if (pendingSeek !== null) {
                            setTimeout(() => {
                                seekToTime(pendingSeek);
                                pendingSeek = null;
                            }, 800);
                        }
                        
                        // Lưu thời điểm hiện tại để phát hiện seek
                        localStorage.setItem('_last_known_time', 0);
                        
                        // Bắt sự kiện seeked để hiển thị thông báo
                        player.on('seeked', function() {
                            const lastKnownTime = parseFloat(localStorage.getItem('_last_known_time')) || 0;
                            const difference = player.currentTime - lastKnownTime;
                            
                            if (Math.abs(difference) >= 9 && Math.abs(difference) <= 11) {
                                if (difference > 0) {
                                    showSeekNotification('Đã tiến +10 giây');
                                } else {
                                    showSeekNotification('Đã lùi -10 giây');
                                }
                            }
                            
                            localStorage.setItem('_last_known_time', player.currentTime);
                        });
                    }
                };
                
                // Lấy nguồn video
                const sourceUrl = videoSource.src;
                
                // Khởi tạo HLS Player với tính năng chất lượng
                hlsInstance = initializeHLSPlayer(hlsVideo, sourceUrl, hlsOptions);
                
                // Gán các phím tắt
                const SEEK_TIME = 10;
                document.addEventListener('keydown', function(e) {
                    if (!playerReady) return;
                    
                    // Chỉ xử lý khi không đang nhập liệu
                    if (document.activeElement.tagName !== 'INPUT' && document.activeElement.tagName !== 'TEXTAREA') {
                        switch(e.key.toLowerCase()) {
                            case 'arrowleft': // Phím mũi tên trái: lùi 10s
                                e.preventDefault();
                                player.currentTime = Math.max(0, player.currentTime - SEEK_TIME);
                                showSeekNotification('Đã lùi -10 giây');
                                break;
                                
                            case 'arrowright': // Phím mũi tên phải: tiến 10s
                                e.preventDefault();
                                player.currentTime = Math.min(player.duration || 0, player.currentTime + SEEK_TIME);
                                showSeekNotification('Đã tiến +10 giây');
                                break;
                                
                            case ' ': // Phím space: play/pause
                                e.preventDefault();
                                if (player.playing) {
                                    player.pause();
                                } else {
                                    player.play();
                                }
                                break;
                                
                            case 'f': // Phím F: fullscreen
                                e.preventDefault();
                                player.fullscreen.toggle();
                                break;
                                
                            case 'm': // Phím M: mute/unmute
                                e.preventDefault();
                                player.muted = !player.muted;
                                break;
                        }
                    }
                });
            } else {
                // Video không phải HLS, khởi tạo Plyr thông thường
                // Định nghĩa thời gian nhảy mỗi lần tiến/lùi
                const SEEK_TIME = 10;
                
                player = new Plyr(hlsVideo, {
                    controls: [
                        'play-large', 'play', 'rewind', 'fast-forward', 'progress', 'current-time', 'duration', 'mute', 
                        'volume', 'settings', 'pip', 'airplay', 'fullscreen'
                    ],
                    hideControls: false,
                    autoplay: false,
                    seekTime: SEEK_TIME,
                    keyboard: { focused: false, global: false },
                    displayDuration: true,
                    toggleTime: true,
                    i18n: {
                        rewind: 'Lùi {seektime} giây',
                        fastForward: 'Tiến {seektime} giây',
                        seekLabel: '{currentTime} / {duration}'
                    }
                });
                
                // Xử lý các sự kiện
                player.on('ready', function() {
                    playerReady = true;
                    
                    // Thông báo player đã sẵn sàng
                    document.dispatchEvent(new CustomEvent('plyr_ready', { 
                        detail: { player: player }
                    }));
                });
            }
        }
    }
    
    // Hiển thị thông báo khi tiến/lùi
    function showSeekNotification(message) {
        // Xóa thông báo cũ nếu có
        const existingNotif = document.querySelector('.seek-notification');
        if (existingNotif) existingNotif.remove();
        
        // Tạo thông báo mới
        const notification = document.createElement('div');
        notification.className = 'seek-notification';
        notification.textContent = message;
        notification.style.cssText = `
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background-color: rgba(0, 0, 0, 0.7);
            color: white;
            padding: 12px 20px;
            border-radius: 5px;
            font-size: 18px;
            font-weight: bold;
            z-index: 9999;
            opacity: 0;
            transition: opacity 0.2s ease;
        `;
        
        hlsContainer.appendChild(notification);
        
        // Hiển thị với hiệu ứng fade
        setTimeout(() => {
            notification.style.opacity = '1';
        }, 10);
        
        // Tự động ẩn sau 1 giây
        setTimeout(() => {
            notification.style.opacity = '0';
            setTimeout(() => {
                notification.remove();
            }, 200);
        }, 1000);
    }
    
    // Hiển thị thông báo đẹp
    function showNotification(message, type = 'info') {
        // Tạo phần tử thông báo
        const notification = document.createElement('div');
        notification.className = 'custom-notification ' + type;
        
        // Chọn biểu tượng dựa trên loại thông báo
        let icon = 'info-circle';
        if (type === 'success') icon = 'check-circle';
        if (type === 'warning') icon = 'exclamation-triangle';
        if (type === 'error') icon = 'times-circle';
        
        // Thiết lập nội dung
        notification.innerHTML = `
            <div class="notification-icon">
                <i class="fas fa-${icon}"></i>
            </div>
            <div class="notification-content">
                ${message}
            </div>
        `;
        
        // Thêm CSS inline
        notification.style.cssText = `
            position: fixed;
            bottom: 20px;
            right: 20px;
            display: flex;
            align-items: center;
            padding: 15px;
            background-color: rgba(27, 46, 67, 0.95);
            color: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            z-index: 9999;
            transform: translateY(20px);
            opacity: 0;
            transition: all 0.3s ease;
            border-left: 4px solid #04c1e7;
        `;
        
        // Thiết lập màu sắc theo loại thông báo
        if (type === 'success') notification.style.borderColor = '#4CAF50';
        if (type === 'warning') notification.style.borderColor = '#FFC107';
        if (type === 'error') notification.style.borderColor = '#F44336';
        
        // Thêm CSS cho icon
        const iconDiv = notification.querySelector('.notification-icon');
        iconDiv.style.cssText = `
            font-size: 24px;
            margin-right: 15px;
            color: ${type === 'success' ? '#4CAF50' : type === 'warning' ? '#FFC107' : type === 'error' ? '#F44336' : '#04c1e7'};
        `;
        
        // Thêm vào body
        document.body.appendChild(notification);
        
        // Hiệu ứng hiển thị
        setTimeout(() => {
            notification.style.transform = 'translateY(0)';
            notification.style.opacity = '1';
        }, 50);
        
        // Tự động biến mất sau 3 giây
        setTimeout(() => {
            notification.style.transform = 'translateY(-20px)';
            notification.style.opacity = '0';
            
            // Xóa khỏi DOM sau khi hiệu ứng kết thúc
            setTimeout(() => {
                notification.remove();
            }, 300);
        }, 3000);
    }
});
</script>

<!-- Script xử lý hiển thị/ẩn bảng phím tắt -->
<script>
   $(document).ready(function() {
    // Xử lý hiện/ẩn bảng phím tắt
    $('.toggle-shortcuts-guide').on('click', function() {
        $('.shortcuts-panel').fadeToggle(200);
    });
    
    // Đóng bảng phím tắt
    $('.close-shortcuts').on('click', function() {
        $('.shortcuts-panel').fadeOut(200);
    });
    
    // Đóng bảng phím tắt khi click bên ngoài
    $(document).on('click', function(event) {
        if (!$(event.target).closest('.keyboard-shortcuts-guide').length) {
            $('.shortcuts-panel').fadeOut(200);
        }
    });
    
    // Điều chỉnh iframe cho đẹp và phù hợp
    const iframeContainer = document.querySelector('.iframe-video');
    const iframe = iframeContainer.querySelector('iframe');
    
    if (iframe) {
        // Đảm bảo iframe có đầy đủ thuộc tính
        iframe.setAttribute('allowfullscreen', 'true');
        iframe.setAttribute('frameborder', '0');
        iframe.setAttribute('scrolling', 'no');
        
        // Thêm class để CSS có thể nhận diện
        iframe.classList.add('embed-responsive-item');
        
        // Nếu chưa có width/height, thêm vào
        if (!iframe.getAttribute('width')) {
            iframe.setAttribute('width', '100%');
        }
        if (!iframe.getAttribute('height')) {
            iframe.setAttribute('height', '100%');
        }
    }
});
</script>

@endsection