<li class="treeview">
    <a href="{{url('/home')}}">
        <i class="fa fa-dashboard"></i> <span>Thống Kê</span>
    </a>
</li>
<li class="treeview">
    <a href="#">
        <i class="fa-solid fa-list"></i>
        <span>Danh Mục Phim</span>
        <i class="fa fa-angle-left pull-right"></i>
    </a>
    <ul class="treeview-menu">
        <li>
            <a href="{{route('category.create')}}"><i class="fa fa-angle-right"></i> Thêm
                Danh Mục</a>
        </li>
        <li>
            <a href="{{route('category.index')}}"><i class="fa fa-angle-right"></i> Liệt Kê
                danh Mục</a>
        </li>
    </ul>
</li>
<li class="treeview">
    <a href="#">
        <i class="fas fa-masks-theater"></i>
        <span>Thể Loại Phim</span>
        <i class="fa fa-angle-left pull-right"></i>
    </a>
    <ul class="treeview-menu">
        <li>
            <a href="{{route('genre.create')}}"><i class="fa fa-angle-right"></i> Thêm Thể
                Loại</a>
        </li>
        <li>
            <a href="{{route('genre.index')}}"><i class="fa fa-angle-right"></i> Liệt Kê Thể
                Loại</a>
        </li>
    </ul>
</li>
<li class="treeview">
    <a href="#">
        <i class="fa-solid fa-globe"></i>
        <span>Quốc Gia Phim</span>
        <i class="fa fa-angle-left pull-right"></i>
    </a>
    <ul class="treeview-menu">
        <li>
            <a href="{{route('country.create')}}"><i class="fa fa-angle-right"></i> Thêm
                Quốc Gia</a>
        </li>
        <li>
            <a href="{{route('country.index')}}"><i class="fa fa-angle-right"></i> Liệt Kê
                Quốc Gia</a>
        </li>
    </ul>
</li>
<li class="treeview">
    <a href="#">
        <i class="fa-solid fa-film"></i>
        <span>Phim</span>
        <i class="fa fa-angle-left pull-right"></i>
    </a>
    <ul class="treeview-menu">
        <li>
            <a href="{{route('movie.create')}}"><i class="fa fa-angle-right"></i> Thêm
                Phim</a>
        </li>
        <li>
            <a href="{{route('movie.index')}}"><i class="fa fa-angle-right"></i> Liệt Kê
                Phim</a>
        </li>
        <li>
            <a href="{{route('leech-search')}}"><i class="fa fa-angle-right"></i> Tìm Kiếm
                Phim Theo API</a>
        </li>
        <li>
            <a href="{{route('leech-movie')}}"><i class="fa fa-angle-right"></i> API
                Phim Mới Nhất</a>
        </li>
        <li>
            <a href="{{route('api-list')}}"><i class="fa fa-angle-right"></i> Danh Sách Tổng
                Hợp</a>
        </li>
    </ul>
</li>
<li class="treeview">
    <a href="#">
        <i class="fa-solid fa-circle-play"></i>
        <span>Quản lý tập phim</span>
        <i class="fa fa-angle-left pull-right"></i>
    </a>
    <ul class="treeview-menu">
        <li>
            <a href="{{route('episode.create')}}"><i class="fa fa-angle-right"></i> Thêm tập
                phim</a>
        </li>
        <li>
            <a href="{{route('episode.index')}}"><i class="fa fa-angle-right"></i> Liệt kê
                tập phim</a>
        </li>
    </ul>
</li>
<li class="treeview">
    <a href="#">
        <i class="fa-solid fa-users"></i>
        <span>Quản lý người dùng</span>
        <i class="fa fa-angle-left pull-right"></i>
    </a>
    <ul class="treeview-menu">
        <li>
            <a href="{{route('user.index')}}"><i class="fa fa-angle-right"></i> Danh sách người dùng</a>
        </li>
    </ul>
</li>