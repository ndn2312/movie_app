@extends('layout')
@section('content')
<style>
   .tag-base {
       display: inline-block;
       padding: 4px 10px;
       margin-right: 6px;
       font-size: 11px;
       font-weight: 600;
       border-radius: 20px;  /* Bo tròn hoàn toàn */
       color: #FFFFFF;
       box-shadow: 0 2px 4px rgba(0,0,0,0.2);
       text-transform: uppercase;
       letter-spacing: 0.5px;
       transition: all 0.3s ease;
   }
 
 
   /* Style cho từng loại tag với gradient */
   .trailer-tag {
       background: linear-gradient(135deg, #e304af 0%, #dc07d5 100%);
   }
 
   .fullhd-tag {
       background: linear-gradient(135deg, #e30404 0%, #dc2407 100%);
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
       background: linear-gradient(135deg, #16a085 0%, #2ecc71 100%);
   }
 
   .phude-tag {
       background: linear-gradient(135deg, #5aa469 0%, #7ec544 100%);
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

     .fb-comments{
      
      background: rgb(236, 230, 230);
     }
      

</style>
<div class="row container" id="wrapper">
    <div class="halim-panel-filter">
       <div class="panel-heading">
          <div class="row">
             <div class="col-xs-6">
                <div class="yoast_breadcrumb hidden-xs"><span><span><a href="{{route('category',[$movie->category->slug])}}">{{$movie->category->title}}</a> » 
                  <span>
                     <a href="{{route('country',[$movie->country->slug])}}">{{$movie->country->title}}</a> » 
                        @foreach($movie->movie_genre as $gen)
                     <a href="{{route('genre',[$gen->slug])}}">{{$gen->title}}</a> » 
                     @endforeach

                  <span class="breadcrumb_last" aria-current="page">{{$movie->title}}</span></span></span></span></div>
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
                <div class="title-block">
                   <div id="bookmark" class="bookmark-img-animation primary_ribbon" data-id="38424">
                      <div class="halim-pulse-ring"></div>
                   </div>
                   <div class="title-wrapper" style="font-weight: bold;">
                      Bookmark
                   </div>
                </div>
                <div class="movie_info col-xs-12">
                   <div class="movie-poster col-md-3">
                      <img class="movie-thumb" src="{{asset('uploads/movie/'.$movie->image)}}" alt="{{$movie->title}}">
                        @if($movie->resolution!==5)
                           <div class="bwa-content">
                              <div class="loader"></div>
                              <a href="{{route('watch',[$movie->slug])}}" class="bwac-btn">
                              <i class="fa fa-play"></i>
                              </a>
                           </div>
                        @else
                           <a href="#watch_trailer" style="display:block" class="btn btn-primary watch_trailer">Xem trailer</a>
                        @endif
                   </div>
                   <div class="film-poster col-md-9">
                      <h1 class="movie-title title-1" style="display:block;line-height:35px;margin-bottom: -14px;color: #ffed4d;text-transform: uppercase;font-size: 18px;">{{$movie->title}}</h1>
                      <h2 class="movie-title title-2" style="font-size: 12px;">{{$movie->name_eng}}</h2>
                      <ul class="list-info-group">
                         <li class="list-info-group-item"><span>Trạng Thái</span> : 
                           <span class="quality">
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
                        @if($movie->resolution==0)
                           <span class="episode">
                              @if($movie->phude==0)
                                 Phụ đề
                              @else
                                 Thuyết minh
                              @endif
                           </span>     
                        @endif
                        </li>
                         <li class="list-info-group-item"><span>Điểm IMDb</span> : 
                           <span class="imdb">7.2</span>
                        </li>
                         <li class="list-info-group-item"><span>Thời lượng</span> : 
                           {{$movie->thoiluong}}
                        </li>
                        <li class="list-info-group-item"><span>Số tập</span> : 
                           {{$movie->sotap}}/{{$movie->sotap}}
                        </li>
                           @if($movie->season!==0)
                              <li class="list-info-group-item"><span>Season</span> : {{$movie->season}}</li>
                           @endif
                           
                         <li class="list-info-group-item"><span>Thể loại</span> : 
                           @foreach($movie->movie_genre as  $gen)
                              <a href="{{route('genre',$gen->slug)}}" rel="category tag">{{$gen->title}}</a>
                           @endforeach
                         </li>
                         <li class="list-info-group-item"><span>Danh mục phim</span> : 
                           <a href="{{route('category',[$movie->category->slug])}}" rel="category tag">{{$movie->category->title}}</a>
                         </li>
                         <li class="list-info-group-item"><span>Quốc gia</span> : 
                           <a href="{{route('country',[$movie->country->slug])}}" rel="tag">{{$movie->country->title}}</a>
                        </li>
                        
                       
                      </ul>
                      <div class="movie-trailer hidden"></div>
                   </div>
                </div>
             </div>
             <div class="clearfix"></div>
             <div id="halim_trailer"></div>
             <div class="clearfix"></div>
            <div class="section-bar clearfix">
                <h2 class="section-title"><span style="color:#ffed4d">Nội dung phim</span></h2>
            </div>
               <div class="entry-content htmlwrap clearfix">
                <div class="video-item halim-entry-box">
                   <article id="post-38424" class="item-content">
                      {{$movie->description}}
                   </article>
                </div>
               </div>
               {{-- Tags phim --}}
               <div class="section-bar clearfix">
                  <h2 class="section-title"><span style="color:#ffed4d">Tags phim</span></h2>
              </div>
                 <div class="entry-content htmlwrap clearfix">
                  <div class="video-item halim-entry-box">
                     <article id="post-38424" class="item-content">
                        @if($movie->tags!=NULL)
                        @php
                        $tags = array();
                        $tags = explode(",",$movie->tags);
                        @endphp
                        @foreach($tags as $key => $tag)
                        <a href="{{url('tag/'.$tag)}}">{{$tag}}</a>
                        @endforeach
                        @else
                        {{$movie->title}}
                        @endif
                     </article>
                  </div>
                 </div>
                 {{-- CMT fb    --}}
               <div class="section-bar clearfix">
                  <h2 class="section-title"><span style="color:#ffed4d">BÌNH LUẬN</span></h2>
              </div>
                 <div class="entry-content htmlwrap clearfix">
                  @php
                     $current_url = Request::url();
                  @endphp
                  <div class="video-item halim-entry-box">
                     <article id="post-38424" class="item-content">
                        <div  class="fb-comments" data-href="{{$current_url}}" data-width="100%" data-numposts="3"></div>
                     </article>
                  </div>
                 </div>
                 {{-- Trailer phim --}}
                 <div class="section-bar clearfix">
                  <h2 class="section-title"><span style="color:#ffed4d">Trailer phim</span></h2>
              </div>
                 <div class="entry-content htmlwrap clearfix">
                  <div class="video-item halim-entry-box">
                     <article id="watch_trailer" class="item-content">
                        <iframe width="100%" height="500" src="https://www.youtube.com/embed/{{$movie->trailer}}" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>
                        {{-- <iframe width="560" height="415" src="https://www.youtube.com/embed/{{$movie->trailer}}" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe> --}}
                     </article>
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
                                     src="{{asset('uploads/movie/'.$hot->image)}}"
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
                                       @if($movie->phude==0)
                                       <span class="tag-base phude-tag">Phụ đề</span>
                                       @else
                                       <span class="tag-base thuyetminh-tag">T.Minh</span>
                                       @endif
                                       
                                       @if($movie->season!=0)
                                       <span class="tag-base season-tag">S.{{$movie->season}}</span>
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