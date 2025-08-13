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
                        <div class="user-actions d-none d-md-block">
                            @auth('web')
                                <div class="dropdown user-dropdown">
                                    <a href="#" class="dropdown-toggle" id="userMenuDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i class="fa-solid fa-user-circle"></i> 
                                        <span>{{ Auth::guard('web')->user()->name }}</span>
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="userMenuDropdown">
                                        <a class="dropdown-item" href="{{ route('user.dashboard') }}">
                                            <i class="fa-solid fa-gauge-high"></i> Bảng điều khiển
                                        </a>
                                        <div class="dropdown-divider"></div>
                                        <a class="dropdown-item" href="{{ route('logout') }}"
                                           onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                            <i class="fa-solid fa-right-from-bracket"></i> Đăng xuất
                                        </a>
                                    </div>
                                </div>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">@csrf</form>
                            @else
                                <a href="/login">Đăng nhập</a> / <a href="/register">Đăng ký</a>
                            @endauth
                        </div>
                    <div class="cart-action">
                        <a href="#" class="cart-icon"> 
                            <i class="fa fa-shopping-cart"></i>
                            @auth('web')
                            <span class="cart-count">{{ $cartTotalQuantity ?? 0 }}</span>
                            @else
                            <span class="cart-count" id="guest-cart-count">0</span>
                            @endauth
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
                        @foreach($categoryMenus as $category)
                        <li>
                            <a href="{{ route('products.by_category', $category->slug) }}">
                                {{ $category->name }}
                            </a>
                            @if($category->children->isNotEmpty())
                            <span class="submenu-toggle"><i class="fa fa-angle-right"></i></span>
                            <ul class="sub-menu">
                                @foreach($category->children as $menuChild)
                                <li><a href="{{ route('products.by_category', $menuChild->slug) }}">{{ $menuChild->name }}</a></li>
                                @endforeach
                            </ul>
                            @endif
                        </li>
                        @endforeach
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
                <li><a href="{{route('intro.show')}}">Về Chúng Tôi</a></li>
                <li><a href="/tra-cuu-bao-hanh">Bảo hành</a></li>
                <li><a href="/lien-he">Liên Hệ</a></li>
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
    @auth('web')
        <div class="offcanvas-body">
            @forelse($cartItems as $item)
            <div class="cart-item cart-item-auth">
                <div class="cart-item_image">
                    <img src="{{ asset($item->product->image ?? 'https://placehold.co/100x100') }}" alt="{{ $item->product->name }}">
                </div>
                <div class="cart-item_info">
                    <a href="{{ route('frontend.product.show', $item->product->slug) }}" class="item-name">{{ $item->product->name }}</a>
                    <div class="item-meta">
                        <span class="item-price">{{ number_format($item->product->price) }}đ</span>
                        <span class="item-quantity">x {{ $item->quantity }}</span>
                    </div>
                </div>
                <a href="#" class="item-remove" title="Xóa sản phẩm" data-item-id="{{ $item->id }}">
                    <i class="fa fa-trash"></i>
                </a>
            </div>
            @empty
            <p class="text-center p-4">Giỏ hàng của bạn đang trống.</p>
            @endforelse
        </div>
        <div class="offcanvas-footer">
            <div class="cart-summary">
                <span>Tổng cộng:</span>
                <span class="total-price">{{ number_format($cartTotal ?? 0) }}đ</span>
            </div>
            <a href="/cart" class="btn btn-dark w-100">Xem Giỏ Hàng</a>
            <a href="/checkout" class="btn btn-primary w-100 mt-2">Thanh Toán</a>
        </div>
    @else
        <div id="guest-cart-body" class="offcanvas-body">
            <p class="text-center p-4">Giỏ hàng của bạn đang trống.</p>
        </div>
        <div id="guest-cart-footer" class="offcanvas-footer" style="display: none;">
            <div class="cart-summary">
                <span>Tổng cộng:</span>
                <span id="guest-cart-total" class="total-price">0đ</span>
            </div>
            <a href="/cart" class="btn btn-dark w-100">Xem Giỏ Hàng</a>
            <a href="/checkout" class="btn btn-primary w-100 mt-2">Thanh Toán</a>
        </div>
    @endguest
</div>
<template id="guest-cart-item-template">
    <div class="cart-item">
        <div class="cart-item_image">
            <img src="__IMAGE__" alt="__NAME__">
        </div>
        <div class="cart-item_info">
            <a href="__URL__" class="item-name">__NAME__</a>
            <div class="item-meta">
                <span class="item-price">__PRICE__đ</span>
                <span class="item-quantity">x __QUANTITY__</span>
            </div>
        </div>
        <a href="#" class="item-remove" title="Xóa sản phẩm" data-item-id="__ID__">
            <i class="fa fa-trash"></i>
        </a>
    </div>
</template>
<div class="offcanvas-overlay"></div>
@push('js')
<script>
    $(document).ready(function() {
        window.addEventListener('scroll', function() {
            const header = document.querySelector('.header');
            const scrollPosition = window.scrollY;
            if (scrollPosition > 50) { 
                header.classList.add('header-scrolled');
                header.classList.remove('is-unsticking'); 
            } else {
                if (header.classList.contains('header-scrolled')) {
                    header.classList.remove('header-scrolled');
                    header.classList.add('is-unsticking');
                    setTimeout(function() {
                        header.classList.remove('is-unsticking');
                    }, 20);
                }
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