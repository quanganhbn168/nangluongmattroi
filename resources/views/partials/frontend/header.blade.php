@push('css')
<style>

</style>
@endpush
<header class="header">
    <div class="header-top bg-dark">
        <div class="container d-flex justify-content-between align-items-center">
            <div class="header-top_item d-flex align-items-center">
                <a href="{{route('home')}}" style="color:rgba(255,255,255,0.7)">{{$setting->name}}</a>
            </div>
            <div class="header-top_item d-flex align-items-center">
                @if(isset($setting->phone)) 
                <a href="tel:{{ $setting->phone }}" class="text-white me-3">
                    <i class="fa fa-phone"></i> {{ $setting->phone }}
                </a>
                @endif

                <a href="{{ route('cart.index') }}" class="text-white me-3"> 
                    <i class="fa fa-shopping-cart"></i>
                </a>
                <a href="{{route('login')}}" class="text-white"> 
                    <i class="fa fa-user"></i> Đăng nhập
                </a>
            </div>
        </div>
    </div>
    <nav class="navbar navbar-expand-lg navbar-light" style="background-color:#ffffff">
        <div class="container-fluid">
            <a class="navbar-brand" href="{{route('home')}}">
                <img src="{{asset($setting->logo)}}" alt="{{$setting->name}}" class="d-inline-block align-top">
            </a>

            <button class="navbar-toggler" type="button" data-toggle="offcanvas" aria-controls="offcanvasNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item active">
                        <a class="nav-link" href="{{route('home')}}">TRANG CHỦ <span class="sr-only">(current)</span></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">GIỚI THIỆU</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">DỊCH VỤ</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownSanPham" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            SẢN PHẨM
                        </a>
                        <div class="dropdown-menu" aria-labelledby="navbarDropdownSanPham">
                            <a class="dropdown-item" href="#">Sản phẩm 1</a>
                            <a class="dropdown-item" href="#">Sản phẩm 2</a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="#">Sản phẩm khác</a>
                        </div>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">DỰ ÁN ĐÃ LÀM</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">BẢO HÀNH</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">TIN TỨC</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{route('contact.show')}}">LIÊN HỆ</a>
                    </li>
                </ul>
            </div>
        </div>

        <div class="offcanvas-collapse" id="offcanvasNav">
            <div class="offcanvas-header">
                <h5 class="offcanvas-title">
                    <a href="{{route('home')}}">
                        <img src="{{asset($setting->home)}}" alt="{{$setting->name}}" height="70">
                    </a>
                </h5>
                <button type="button" class="close" data-dismiss="offcanvas" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="offcanvas-body">
                <ul class="navbar-nav">
                    <li class="nav-item active">
                        <a class="nav-link" href="{{route('home')}}">TRANG CHỦ <span class="sr-only">(current)</span></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">GIỚI THIỆU</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">DỊCH VỤ</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="offcanvasDropdownSanPham" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            SẢN PHẨM
                        </a>
                        <div class="dropdown-menu" aria-labelledby="offcanvasDropdownSanPham">
                            <a class="dropdown-item" href="#">Sản phẩm 1</a>
                            <a class="dropdown-item" href="#">Sản phẩm 2</a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="#">Sản phẩm khác</a>
                        </div>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">DỰ ÁN ĐÃ LÀM</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">BẢO HÀNH</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">TIN TỨC</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{route('contact.show')}}">LIÊN HỆ</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <div class="offcanvas-backdrop"></div>
</header>
@push('js')
<script>
    document.addEventListener('DOMContentLoaded', function() {
    const offcanvasToggle = document.querySelector('[data-toggle="offcanvas"]');
    const offcanvasNav = document.getElementById('offcanvasNav');
    const offcanvasBackdrop = document.querySelector('.offcanvas-backdrop');
    const offcanvasCloseButton = offcanvasNav.querySelector('.close');
    const body = document.body;

    function toggleOffcanvas() {
        offcanvasNav.classList.toggle('open');
        offcanvasBackdrop.classList.toggle('show');
        body.classList.toggle('offcanvas-active'); // To prevent body scrolling
    }

    // Toggle when hamburger button is clicked
    if (offcanvasToggle) {
        offcanvasToggle.addEventListener('click', toggleOffcanvas);
    }

    // Close when backdrop is clicked
    if (offcanvasBackdrop) {
        offcanvasBackdrop.addEventListener('click', toggleOffcanvas);
    }

    // Close when the close button inside offcanvas is clicked
    if (offcanvasCloseButton) {
        offcanvasCloseButton.addEventListener('click', toggleOffcanvas);
    }

    // Handle dropdowns inside the offcanvas
    // This part is important because Bootstrap 4's dropdowns rely on a parent
    // being visible. When the offcanvas slides in, the dropdowns need to be re-initialized
    // or handled manually. We'll use custom JS to toggle them.
    const offcanvasDropdownToggles = offcanvasNav.querySelectorAll('.dropdown-toggle');

    offcanvasDropdownToggles.forEach(function(toggle) {
        toggle.addEventListener('click', function(event) {
            event.preventDefault(); // Prevent default link behavior
            const dropdownMenu = this.nextElementSibling;
            if (dropdownMenu && dropdownMenu.classList.contains('dropdown-menu')) {
                // Toggle 'show' class to display/hide the dropdown
                dropdownMenu.classList.toggle('show');
            }

            // Close other open dropdowns in the offcanvas
            offcanvasDropdownToggles.forEach(function(otherToggle) {
                if (otherToggle !== toggle) {
                    const otherDropdownMenu = otherToggle.nextElementSibling;
                    if (otherDropdownMenu && otherDropdownMenu.classList.contains('dropdown-menu')) {
                        otherDropdownMenu.classList.remove('show');
                    }
                }
            });
        });
    });

    // Close dropdowns if clicking outside (within the offcanvas, not the backdrop)
    offcanvasNav.addEventListener('click', function(event) {
        if (!event.target.closest('.dropdown-toggle') && !event.target.closest('.dropdown-menu')) {
            offcanvasNav.querySelectorAll('.dropdown-menu.show').forEach(function(menu) {
                menu.classList.remove('show');
            });
        }
    });
});
</script>
@endpush