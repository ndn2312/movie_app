@extends('layout')
@section('content')
<style>
   .tag-base {
       display: inline-block;
       padding: 4px 10px;
       /* margin-right: 6px; */
       font-size: 11px;
       font-weight: 600;
       border-radius: 20px;  /* Bo tròn hoàn toàn */
       color: #FFFFFF;
       box-shadow: 0 2px 4px rgba(0,0,0,0.2);
       text-transform: uppercase;
       letter-spacing: 0.5px;
       transition: all 0.3s ease;
   }

   /* Vị trí cho status và episode */
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
   /* .sotap-position {
   position: absolute;
   top:40px;
   left: 6px;
   z-index: 10;
   } */


   /* Style cho từng loại tag với gradient */
   .trailer-tag {
       background: linear-gradient(135deg, #e304af 0%, #dc07d5 100%);
   }

   .fullhd-tag {
       background: red;
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
       background: green;
       /* margin-right: 3px; */

   }

   .phude-tag {
       background: gray;
       /* margin-right: 0; */

   }
   .ss-tag {
       background: linear-gradient(135deg, #034ba8 30%, #04c1e7 70%);
       
       /* margin-right: 3px; */

   }
   .sotap-tag {
       background: linear-gradient(135deg, #ea8d40 30%, #e70404 70%);
       /* background-color: #ea8d40; */

       
       /* margin-right: 3px; */

   }
   


   .season-tag {
       background: linear-gradient(135deg, #e74c3c 0%, #ff5e57 100%);
   }

   /* Hiệu ứng hover */
   .halim-thumb:hover .tag-base {
       filter: brightness(1.1);
       transform: translateY(-1px);
       box-shadow: 0 3px 5px rgba(0,0,0,0.3);
   }
       .halim-post-title .entry-title {
           font-weight: 700;          
           font-size: 15px;           
           margin-bottom: 3px;       
           line-height: 1.3;          
       }

       .halim-post-title .original_title {
           font-weight: 600;        
           font-size: 13px;          
           line-height: 1.2;          
       }

       .halim-thumb:hover .entry-title,
       .halim-thumb:hover .original_title {
           color: #ff6600;        /
       }


</style><div class="row container" id="wrapper">
         <div class="halim-panel-filter">
            <div class="panel-heading">
               <div class="row">
                  <div class="col-xs-6">
                     <div class="yoast_breadcrumb hidden-xs"><span><span><a href="">{{$movie->title}}</a> » <span><a href="{{route('country',[$movie->country->slug])}}">{{$movie->country->title}}</a> » <span class="breadcrumb_last" aria-current="page">{{$movie->title}}</span></span></span></span></div>
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
                  <style type="text/css">
                     .iframe-video {
                        position: relative;
                        padding-bottom: 56.25%;
                        height: 0;
                        overflow: hidden;
                     }
                     .iframe-video iframe {
                        position: absolute;
                        top: 0;
                        left: 0;
                        width: 100%;
                        height: 100%;
                     }

                  </style>
                  <div class="iframe-video">
                     {!!$episode->linkphim!!}

                  </div>
                  
                  {{-- <div class="button-watch">
                     <ul class="halim-social-plugin col-xs-4 hidden-xs">
                        <li class="fb-like" data-href="" data-layout="button_count" data-action="like" data-size="small" data-show-faces="true" data-share="true"></li>
                     </ul>
                     <ul class="col-xs-12 col-md-8">
                        <div id="autonext" class="btn-cs autonext">
                           <i class="icon-autonext-sm"></i>
                           <span><i class="hl-next"></i> Autonext: <span id="autonext-status">On</span></span>
                        </div>
                        <div id="explayer" class="hidden-xs"><i class="hl-resize-full"></i>
                           Expand 
                        </div>
                        <div id="toggle-light"><i class="hl-adjust"></i>
                           Light Off 
                        </div>
                        <div id="report" class="halim-switch"><i class="hl-attention"></i> Report</div>
                        <div class="luotxem"><i class="hl-eye"></i>
                           <span>1K</span> lượt xem 
                        </div>
                        <div class="luotxem">
                           <a class="visible-xs-inline" data-toggle="collapse" href="#moretool" aria-expanded="false" aria-controls="moretool"><i class="hl-forward"></i> Share</a>
                        </div>
                     </ul>
                  </div> --}}
                  {{-- <div class="collapse" id="moretool">
                     <ul class="nav nav-pills x-nav-justified">
                        <li class="fb-like" data-href="" data-layout="button_count" data-action="like" data-size="small" data-show-faces="true" data-share="true"></li>
                        <div class="fb-save" data-uri="" data-size="small"></div>
                     </ul>
                  </div> --}}
               
                  <div class="clearfix"></div>
                  <div class="clearfix"></div>
                  <div class="title-block">
                     <a href="javascript:;" data-toggle="tooltip" title="Add to bookmark">
                        <div id="bookmark" class="bookmark-img-animation primary_ribbon" data-id="37976">
                           <div class="halim-pulse-ring"></div>
                        </div>
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
                              <li role="presentation" class="active server-1"><a href="#server-0" aria-controls="server-0" role="tab" data-toggle="tab"><i class="hl-server"></i> HD</a></li>

                           {{-- </span> --}}
                        @elseif($movie->resolution==1)
                           {{-- <span class="tag-base sd-tag"> --}}
                              <li role="presentation" class="active server-1"><a href="#server-0" aria-controls="server-0" role="tab" data-toggle="tab"><i class="hl-server"></i> SD</a></li>

                           {{-- </span> --}}
                        @elseif($movie->resolution==2)
                           {{-- <span class="tag-base hdcam-tag"> --}}
                              <li role="presentation" class="active server-1"><a href="#server-0" aria-controls="server-0" role="tab" data-toggle="tab"><i class="hl-server"></i> HDCam</a></li>

                           {{-- </span> --}}
                        @elseif($movie->resolution==3)
                           {{-- <span class="tag-base cam-tag"> --}}
                              <li role="presentation" class="active server-1"><a href="#server-0" aria-controls="server-0" role="tab" data-toggle="tab"><i class="hl-server"></i> Cam</a></li>

                           {{-- </span> --}}
                        @elseif($movie->resolution==4)
                           {{-- <span class="tag-base fullhd-tag"> --}}
                              <li role="presentation" class="active server-1"><a href="#server-0" aria-controls="server-0" role="tab" data-toggle="tab"><i class="hl-server"></i> FullHD</a></li>

                           {{-- </span> --}}
                        @else
                           {{-- <span class="tag-base trailer-tag"> --}}
                              <li role="presentation" class="active server-1"><a href="#server-0" aria-controls="server-0" role="tab" data-toggle="tab"><i class="hl-server"></i> Trailer</a></li>

                           {{-- </span> --}}
                        @endif
                        <li role="presentation" class="active server-1"><a href="#server-0" aria-controls="server-0" role="tab" data-toggle="tab"><i class="hl-server"></i> Vietsub</a></li>
                     </ul>
                     <div class="tab-content">
                        <div role="tabpanel" class="tab-pane active server-1" id="server-0">
                           <div class="halim-server">
                              <ul class="halim-list-eps">
                                 @foreach($movie->episode as $key=>$sotap)               
                                    <a href="{{url('xem-phim/'.$movie->slug.'/tap-'.$sotap->episode)}}">
                                       <li class="halim-episode"><span class="halim-btn halim-btn-2 {{$tapphim==$sotap->episode ? 'active' :''}} halim-info-1-1 box-shadow" data-post-id="37976" data-server="1" data-episode="1" data-position="first" data-embed="0" data-title="Xem phim {{$movie->title}} - Tập {{$sotap->episode}} - {{$movie->name_eng}} - vietsub + Thuyết Minh" data-h1="{{$movie->title}} - tập {{$sotap->episode}}">{{$sotap->episode}}</span>
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
                              <figure><img class="lazy img-responsive" src="{{asset('uploads/movie/'.$hot->image)}}"
                                    alt="{{$hot->title}}" title="{{$hot->title}}"></figure>
                              <span class="status">
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
                              </span>
      
                              <span class="episode">
                                 <span class="tag-base sotap-tag">{{$movie->episode_count}}/{{$movie->sotap}}</span>
                                @if($movie->phude==0)
                                <span class="tag-base phude-tag">P.Đề</span>
                                @else
                                <span class="tag-base thuyetminh-tag">T.Minh</span>
                                @endif
      
                                 @if($movie->season!=0)
                                 <span class="tag-base ss-tag">S.{{$movie->season}}</span>
                                 @endif
                              </span>
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
@endsection