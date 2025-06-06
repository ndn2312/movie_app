<aside id="sidebar" class="col-xs-12 col-sm-12 col-md-4">
    {{-- Phim hot --}}
    <div id="halim_tab_popular_videos-widget-7" class="widget halim_tab_popular_videos-widget">
        <div class="section-bar clearfix">
            <div class="section-title">
                <span>Phim Hot</span>

            </div>
        </div>
        <section class="tab-content">
            <div role="tabpanel" class="tab-pane active halim-ajax-popular-post">
                <div class="halim-ajax-popular-post-loading hidden"></div>
                @foreach($phimhot_sidebar as $key => $hot_sidebar)
                <div id="halim-ajax-popular-post" class="popular-post">
                    <div class="item post-37176">
                        <a href="{{route('movie', $hot_sidebar->slug)}}" title="{{$hot_sidebar->title}}">
                            <div class="item-link">
                                <img src="@if(Str::startsWith($hot_sidebar->image, ['http://', 'https://'])){{$hot_sidebar->image}}@else{{asset('uploads/movie/'.$hot_sidebar->image)}}@endif"
                                    class="lazy post-thumb" alt="{{$hot_sidebar->title}}"
                                    title="{{$hot_sidebar->title}}" />
                                <span class="is_trailer">
                                    @if($hot_sidebar->resolution==0)
                                    HD
                                    @elseif($hot_sidebar->resolution==1)
                                    SD
                                    @elseif($hot_sidebar->resolution==2)
                                    HDCam
                                    @elseif($hot_sidebar->resolution==3)
                                    Cam
                                    @elseif($hot_sidebar->resolution==4)
                                    FullHD
                                    @else
                                    Trailer
                                    @endif
                                </span>
                            </div>
                            <p class="title">{{$hot_sidebar->title}}</p>
                        </a>
                        <div class="viewsCount" style="color: #9d9d9d;">
                            @if($hot_sidebar->count_views > 0)
                            <span class="view-count-display">{{$hot_sidebar->count_views}} lượt xem</span>
                            @else
                            <span class="view-count-display">0 lượt xem</span>
                            @endif

                        </div>
                        <div style="float: left;">
                            <span class="user-rate-image post-large-rate stars-large-vang"
                                style="display: block;/* width: 100%; */">
                                <span style="width: 0%"></span>
                            </span>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </section>
        <div class="clearfix"></div>
    </div>

    {{-- Phim sắp chiếu --}}
    <div id="halim_tab_popular_videos-widget-7" class="widget halim_tab_popular_videos-widget">
        <div class="section-bar clearfix">
            <div class="section-title">
                <span>Phim Sắp Chiếu</span>
            </div>
        </div>
        <section class="tab-content">
            <div role="tabpanel" class="tab-pane active halim-ajax-popular-post">
                <div class="halim-ajax-popular-post-loading hidden"></div>
                @if(count($phimhot_trailer) > 0)
                @foreach($phimhot_trailer as $key => $hot_sidebar)
                <div id="halim-ajax-popular-post" class="popular-post">
                    <div class="item post-37176">
                        <a href="{{route('movie', $hot_sidebar->slug)}}" title="{{$hot_sidebar->title}}">
                            <div class="item-link">
                                <img src="@if(Str::startsWith($hot_sidebar->image, ['http://', 'https://'])){{$hot_sidebar->image}}@else{{asset('uploads/movie/'.$hot_sidebar->image)}}@endif"
                                    class="lazy post-thumb" alt="{{$hot_sidebar->title}}"
                                    title="{{$hot_sidebar->title}}" />
                                <span class="is_trailer">
                                    @if($hot_sidebar->resolution==0) HD
                                    @elseif($hot_sidebar->resolution==1) SD
                                    @elseif($hot_sidebar->resolution==2) HDCam
                                    @elseif($hot_sidebar->resolution==3) Cam
                                    @elseif($hot_sidebar->resolution==4) FullHD
                                    @else Trailer
                                    @endif
                                </span>
                            </div>
                            <p class="title">{{$hot_sidebar->title}}</p>
                        </a>
                        <div class="viewsCount" style="color: #9d9d9d;">
                            @if($hot_sidebar->count_views > 0)
                            <span class="view-count-display">{{$hot_sidebar->count_views}} lượt xem</span>
                            @else
                            <span class="view-count-display">0 lượt xem</span>
                            @endif
                        </div>
                        <div style="float: left;">
                            <span class="user-rate-image post-large-rate stars-large-vang"
                                style="display: block; width: 100%;">
                                <span style="width: 0%"></span>
                            </span>
                        </div>
                    </div>
                </div>
                @endforeach
                @else
                <div class="empty-content">
                    <p>Chưa có phim sắp chiếu.</p>
                </div>
                @endif
            </div>
        </section>
        <div class="clearfix"></div>
    </div>


    {{-- Top views --}}
    <div id="halim_tab_popular_videos-widget-7" class="widget halim_tab_popular_videos-widget">
        <div class="section-bar clearfix">
            <div class="section-title">
                <span>Top Views</span>

            </div>
        </div>
        <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
            <li class="nav-item active">
                <a class="nav-link filter-sidebar" id="pills-home-tab" data-toggle="pill" href="#ngay" role="tab"
                    aria-controls="pills-home" aria-selected="true">Ngày</a>
            </li>
            <li class="nav-item">
                <a class="nav-link filter-sidebar" id="pills-profile-tab" data-toggle="pill" href="#tuan" role="tab"
                    aria-controls="pills-profile" aria-selected="false">Tuần</a>
            </li>
            <li class="nav-item">
                <a class="nav-link filter-sidebar" id="pills-contact-tab" data-toggle="pill" href="#thang" role="tab"
                    aria-controls="pills-contact" aria-selected="false">Tháng</a>
            </li>
        </ul>

        <div id="halim-ajax-popular-post" class="popular-post">
            <span id="show_default">

            </span>
        </div>
        <div class="tab-content" id="pills-tabContent">
            <div id="halim-ajax-popular-post-default" class="popular-post">
                <span id="show_data_default"></span>
            </div>
            <div class="tab-pane fade show active" id="tuan" role="tabpanel" aria-labelledby="ngay-tab">
                <div id="halim-ajax-popular-post" class="popular-post">

                    <span id="show_data">

                    </span>


                </div>
            </div>



        </div>
        <div class="clearfix"></div>
    </div>
</aside>