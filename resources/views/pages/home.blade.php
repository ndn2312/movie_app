@extends('layout')
@section('content')
<div class="row container" id="wrapper">
            <div class="halim-panel-filter">
               <div id="ajax-filter" class="panel-collapse collapse" aria-expanded="true" role="menu">
                  <div class="ajax"></div>
               </div>
            </div>
             
            <div id="halim_related_movies-2xx" class="wrap-slider">
               <div class="section-bar clearfix">
                   <h3 class="section-title"><span>PHIM HOT</span></h3>
               </div>
               <div id="halim_related_movies-2" class="owl-carousel owl-theme related-film">
               @foreach($phimhot as $key => $hot)
                  <article class="thumb grid-item post-38498">
                     <div class="halim-item">
                         <a class="halim-thumb" href="{{route('movie',$hot->slug)}}" title="{{$hot->title}}">
                             <figure><img class="lazy img-responsive"
                                     src="{{asset('uploads/movie/'.$hot->image)}}"
                                     alt="{{$hot->title}}" title="{{$hot->title}}"></figure>
                             <span class="status">
                                 @if($hot->resolution==0)
                                 HD
                                 @elseif($hot->resolution==1)
                                 SD
                                 @elseif($hot->resolution==2)
                                 HDCam
                                 @elseif($hot->resolution==3)
                                 Cam
                                 @elseif($hot->resolution==4)
                                 FullHD
                                 @endif
                              </span><span class="episode"><i class="fa fa-play"
                                     aria-hidden="true"></i>
                                    @if($hot->phude==0)
                                    Phụ đề
                                    @else
                                    Thuyết minh
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
                   owl.owlCarousel({
                       loop: true,
                       margin: 5,
                       autoplay: true,
                       autoplayTimeout: 4000,
                       autoplayHoverPause: true,
                       nav: true,
                       navText: ['<i class="hl-down-open rotate-left"></i>',
                           '<i class="hl-down-open rotate-right"></i>'
                       ],
                       responsiveClass: true,
                       responsive: {
                           0: {
                               items: 2
                           },
                           480: {
                               items: 3
                           },
                           600: {
                               items: 5
                           },
                           1000: {
                               items: 5
                           }
                       }
                   })
               });
            </script>
         </div>
            <main id="main-contents" class="col-xs-12 col-sm-12 col-md-8">
               @foreach($category_home as $key => $cate_home)
               <section id="halim-advanced-widget-2">
                  <div class="section-heading">
                     {{-- <a href="danhmuc.php" title="Phim Bộ"> --}}
                     <span class="h-text">{{$cate_home->title}}</span>
                     </a>
                  </div>
                  <div id="halim-advanced-widget-2-ajax-box" class="halim_box">
                     @foreach($cate_home->movie->take(10) as $key => $mov)

                     <article class="col-md-3 col-sm-3 col-xs-6 thumb grid-item post-37606">
                        <div class="halim-item">
                           <a class="halim-thumb" href="{{route('movie',$mov->slug)}}" title="{{$mov->title}}">
                              <figure><img class="lazy img-responsive" src="{{asset('uploads/movie/'.$mov->image)}}" title="{{$mov->title}}"></figure>
                              <span class="status">
                                    @if($mov->resolution==0)
                                    HD
                                    @elseif($mov->resolution==1)
                                    SD
                                    @elseif($mov->resolution==2)
                                    HDCam
                                    @elseif($mov->resolution==3)
                                    Cam
                                    @elseif($mov->resolution==4)
                                    FullHD
                                    @endif
                              
                              </span><span class="episode"><i class="fa fa-play" aria-hidden="true"></i>
                                 @if($mov->phude==0)
                                 Phụ đề
                                 @else
                                 Thuyết minh
                                 @endif
                              </span> 
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
               </section>
               <div class="clearfix"></div>
               @endforeach
            </main>
            {{-- Sidebar --}}
            @include('pages.include.sidebar')
         </div> 
@endsection

