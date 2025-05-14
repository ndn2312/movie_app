@extends('layout')
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
</style>
<div class="row container" id="wrapper">
   <div class="halim-panel-filter">
      <div class="panel-heading">
         <div class="row">
            <div class="col-xs-6">
               <div class="yoast_breadcrumb hidden-xs"><span><span><a href="">Phim theo tags</a> » <span
                           class="breadcrumb_last" aria-current="page">{{$tag}}</span></span></span></div>
            </div>
         </div>
      </div>
      <div id="ajax-filter" class="panel-collapse collapse" aria-expanded="true" role="menu">
         <div class="ajax"></div>
      </div>
   </div>
   <main id="main-contents" class="col-xs-12 col-sm-12 col-md-8">
      <section>
         <div class="section-bar clearfix">
            <h1 class="section-title"><span>Tag: {{$tag}}</span></h1>
         </div>
         {{-- <div class="section-bar clearfix">
            <div class="row">
               @include('pages.include.locphim')
            </div>
         </div> --}}
         <div class="halim_box">
            @foreach($movie as $key =>$mov)
            <article class="col-md-3 col-sm-3 col-xs-6 thumb grid-item post-37606">
               <div class="halim-item">
                  <a class="halim-thumb" href="{{route('movie',$mov->slug)}}">
                     <figure><img class="lazy img-responsive" src="{{asset('uploads/movie/'.$mov->image)}}"
                           alt="{{$mov->title}}" title="{{$mov->title}}"></figure>
                     <span class="status">
                        @if($mov->resolution==0)
                        <span class="tag-base hd-tag">HD</span>
                        @elseif($mov->resolution==1)
                        <span class="tag-base sd-tag">SD</span>
                        @elseif($mov->resolution==2)
                        <span class="tag-base hdcam-tag">HDCam</span>
                        @elseif($mov->resolution==3)
                        <span class="tag-base cam-tag">Cam</span>
                        @elseif($mov->resolution==4)
                        <span class="tag-base fullhd-tag">FullHD</span>
                        @else
                        <span class="tag-base trailer-tag">Trailer</span>
                        @endif
                     </span>

                     <span class="episode">
                        <span class="tag-base sotap-tag">{{$mov->episode_count}}/{{$mov->sotap}}</span>
                        @if($mov->phude==0)
                        <span class="tag-base phude-tag">P.Đề</span>
                        @else
                        <span class="tag-base thuyetminh-tag">T.M</span>
                        @endif

                        @if($mov->season!=0)
                        <span class="tag-base ss-tag">{{$mov->season}}.S</span>
                        @endif
                     </span>
                     <div class="play-button">
                        <i class="fas fa-play"></i>
                     </div>
                     <div class="icon_overlay"></div>
                     <div class="halim-post-title-box">
                        <div class="halim-post-title ">
                           <p class="entry-title">{{$mov->title}}</p>
                           <p class="original_title">{{$mov->name_eng}}</p>
                        </div>
                     </div>
                  </a>
               </div>
            </article>
            @endforeach

         </div>
         <div class="clearfix"></div>
         <div class="text-center">
            {{-- <ul class='page-numbers'>
               <li><span aria-current="page" class="page-numbers current">1</span></li>
               <li><a class="page-numbers" href="">2</a></li>
               <li><a class="page-numbers" href="">3</a></li>
               <li><span class="page-numbers dots">&hellip;</span></li>
               <li><a class="page-numbers" href="">55</a></li>
               <li><a class="next page-numbers" href=""><i class="hl-down-open rotate-right"></i></a></li>
            </ul> --}}
            {!! $movie->links("pagination::bootstrap-4") !!}
         </div>
      </section>
   </main>
   @include('pages.include.sidebar')

</div>

@endsection