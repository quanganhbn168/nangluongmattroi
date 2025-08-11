<header class="header">
    <div class="top-bar d-none d-lg-block">
        <div class="container">
            </div>
    </div>
    <div class="main-header">
        <div class="container">
            <div class="main-header-inner">
                <div class="header-col-left">
                    <div class="mobile-menu-toggle d-lg-none">
                        <a href="#" aria-label="Toggle Menu"><i class="fa fa-bars"></i></a>
                    </div>
                    <div class="logo d-none d-lg-block">
                        <a href="{{ url('/') }}">
                            <img src="{{asset($setting->logo)}}" alt="Logo">
                        </a>
                    </div>
                </div>
                <div class="header-col-center">
                    <div class="logo d-lg-none">
                        <a href="{{ url('/') }}">
                            <img src="{{asset($setting->logo)}}" alt="Logo">
                        </a>
                    </div>
                    <div class="search-box d-none d-lg-block">
                        <form action="/search" method="get">
                            <input type="text" class="form-control" placeholder="Bạn tìm gì hôm nay?">
                            <button type="submit"><i class="fa fa-search"></i></button>
                        </form>
                    </div>
                </div>
                <div class="header-col-right">
                    <div class="header-actions">
                        <div class="user-actions d-none d-lg-block">
                            <a href="/login">Đăng nhập</a> / <a href="/register">Đăng ký</a>
                        </div>
                        <div class="cart-action">
                            <a href="/cart" class="cart-icon">
                                <i class="fa fa-shopping-cart"></i>
                                <span class="cart-count">3</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="mobile-search-container d-lg-none">
        <div class="container">
            <div class="search-box">
                <form action="/search" method="get">
                    <input type="text" class="form-control" placeholder="Tìm kiếm sản phẩm...">
                    <button type="submit"><i class="fa fa-search"></i></button>
                </form>
            </div>
        </div>
    </div>
    <nav class="main-nav-container d-none d-lg-block">
        <div class="container">
            <ul class="main-menu-desktop">
                <li><a href="/">Trang Chủ</a></li>
                <li class="menu-item-has-children">
                    <a href="/san-pham">Sản Phẩm</a>
                    <span class="submenu-toggle"><i class="fa fa-angle-down"></i></span>
                    <ul class="sub-menu">
                        <li><a href="#">Áo Sơ Mi</a></li>
                        <li><a href="#">Áo Thun</a></li>
                        <li class="menu-item-has-children">
                            <a href="#">Quần Dài</a>
                            <span class="submenu-toggle"><i class="fa fa-angle-right"></i></span>
                            <ul class="sub-menu">
                                <li><a href="#">Quần Kaki</a></li>
                                <li><a href="#">Quần Jeans</a></li>
                                <li><a href="#">Quần Tây</a></li>
                            </ul>
                        </li>
                        <li><a href="#">Phụ Kiện</a></li>
                    </ul>
                </li>
                <li class="menu-item-has-children">
                    <a href="/blog">Tin Tức</a>
                     <span class="submenu-toggle"><i class="fa fa-angle-down"></i></span>
                    <ul class="sub-menu">
                        <li><a href="#">Tin Khuyến Mãi</a></li>
                        <li><a href="#">Tin Thời Trang</a></li>
                    </ul>
                </li>
                <li><a href="/about-us">Về Chúng Tôi</a></li>
                <li><a href="/tra-cuu-bao-hanh">Bảo hành</a></li>
                <li><a href="/contact">Liên Hệ</a></li>
            </ul>
        </div>
    </nav>
</header>
<div class="offcanvas-menu-wrapper">
    <div class="offcanvas-header">
        <h5 class="offcanvas-title">MENU</h5>
        <a href="#" class="offcanvas-close"><i class="fa fa-times"></i></a>
    </div>
    <div class="offcanvas-menu-content">
        </div>
</div>
<div class="cart-offcanvas-wrapper">
    <div class="offcanvas-header">
        <h5 class="offcanvas-title">Giỏ Hàng Của Bạn</h5>
        <a href="#" class="offcanvas-close js-close-cart"><i class="fa fa-times"></i></a>
    </div>
    <div class="offcanvas-body">
        <div class="cart-item">
            <div class="cart-item_image">
                <img src="https://placehold.co/100x100/EF6C00/FFFFFF?text=SP1" alt="Sản phẩm 1">
            </div>
            <div class="cart-item_info">
                <a href="#" class="item-name">Tấm pin năng lượng mặt trời Mono 550W</a>
                <div class="item-meta">
                    <span class="item-price">2,500,000đ</span>
                    <span class="item-quantity">x 2</span>
                </div>
            </div>
            <a href="#" class="item-remove"><i class="fa fa-trash"></i></a>
        </div>
        <div class="cart-item">
            <div class="cart-item_image">
                <img src="https://placehold.co/100x100/424242/FFFFFF?text=SP2" alt="Sản phẩm 2">
            </div>
            <div class="cart-item_info">
                <a href="#" class="item-name">Biến tần Inverter Hybrid 5kW</a>
                <div class="item-meta">
                    <span class="item-price">15,000,000đ</span>
                    <span class="item-quantity">x 1</span>
                </div>
            </div>
            <a href="#" class="item-remove"><i class="fa fa-trash"></i></a>
        </div>
        </div>
    <div class="offcanvas-footer">
        <div class="cart-summary">
            <span>Tổng cộng:</span>
            <span class="total-price">20,000,000đ</span>
        </div>
        <a href="/cart" class="btn btn-dark w-100">Xem Giỏ Hàng</a>
        <a href="/checkout" class="btn btn-primary w-100 mt-2">Thanh Toán</a>
    </div>
</div>
<div class="offcanvas-overlay"></div>
@push('js')
<script>
    $(document).ready(function() {
        const header = $('header.header');
            $(window).on('scroll', function() {
                if ($(window).scrollTop() > 10) {
                    header.addClass('header-scrolled');
                } else {
                    header.removeClass('header-scrolled');
                }
            });
        if ($('.offcanvas-menu-content .main-menu-desktop').length === 0) {
            $('.main-menu-desktop').clone().appendTo('.offcanvas-menu-content');
        }
        $('.mobile-menu-toggle a').on('click', function(e) {
            e.preventDefault();
            $('body').addClass('show-offcanvas');
        });
        $('.offcanvas-menu-content').on('click', '.submenu-toggle', function(e) {
            e.preventDefault();
            $(this).parent('.menu-item-has-children').toggleClass('open');
            $(this).siblings('.sub-menu').slideToggle(300);
        });
        $('.cart-action > a').on('click', function(e) {
            e.preventDefault(); 
            $('body').addClass('show-cart-offcanvas');
        });
        $('.offcanvas-menu-wrapper .offcanvas-close').on('click', function(e) {
            e.preventDefault();
            $('body').removeClass('show-offcanvas');
        });
        $('.cart-offcanvas-wrapper .js-close-cart').on('click', function(e) {
            e.preventDefault();
            $('body').removeClass('show-cart-offcanvas');
        });
        $('.offcanvas-overlay').on('click', function(e) {
            e.preventDefault();
            $('body').removeClass('show-offcanvas show-cart-offcanvas');
        });
    });
</script>
@endpush