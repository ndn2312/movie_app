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


</style>
   
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
                                    <span class="sotap-position">
                                     </span>
                                    
                                    <span class="episode">
                                        <span class="tag-base sotap-tag">{{$hot->episode_count}}/{{$hot->sotap}}</span>

                                      @if($hot->phude==0)
                                      <span class="tag-base phude-tag">Phụ Đề</span>
                                      @else
                                      <span class="tag-base thuyetminh-tag">T.Minh</span>
                                      @endif
                                      
                                    @if($hot->season!=0)
                                        <span class="tag-base ss-tag">S{{$hot->season}}</span>
                                            {{-- -Tập{{$hot->sotap}}</span> --}}
                                    @endif
                                        {{-- <span class="tag-base sotap-tag">Tập{{$hot->sotap}}</span>
                                    @endif --}}

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
                       navText: [
  '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"></polyline></svg>',
  '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"></polyline></svg>'
]

,
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
                                <span class="tag-base thuyetminh-tag">T.Minh</span>
                                @endif
                                
                                @if($mov->season!=0)
                                        <span class="tag-base ss-tag">S{{$mov->season}}</span>
                                        {{-- -Tập{{$mov->sotap}}</span> --}}
                                @endif
                                        {{-- <span class="tag-base sotap-tag">Tập{{$mov->sotap}}</span>
                                @endif --}}
                                
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

