{{-- resources/views/layouts/master.blade.php --}}
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    {{-- Basic --}}
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    {{-- CSRF --}}
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="is-authenticated" content="{{ Auth::check() ? 'true' : 'false' }}">
    {{-- Title & SEO --}}
    <title>@yield('title')</title>
    <meta name="description" content="@yield('meta_description', $setting->meta_description)">
    <meta name="keywords" content="@yield('meta_keywords', $setting->meta_keywords)">
    <meta name="robots" content="@yield('meta_robots', 'index, follow')">
    {{-- Canonical --}}
    <link rel="canonical" href="{{ url()->current() }}" />
    {{-- Open Graph --}}
    <meta property="og:type"        content="@yield('og_type','website')" />
    <meta property="og:title"       content="@yield('title', config('app.name')) " />
    <meta property="og:description" content="@yield('meta_description', $setting->meta_description)" />
    <meta property="og:url"         content="{{ url()->current() }}" />
    <meta property="og:site_name"   content="{{ $setting->name }}" />
    <meta property="og:image"       content="@yield('meta_image', $setting->share_image)" />
    {{-- Twitter Card --}}
    <meta name="twitter:card"        content="summary_large_image" />
    <meta name="twitter:title"       content="@yield('title', config('app.name'))" />
    <meta name="twitter:description" content="@yield('meta_description')" />
    <meta name="twitter:image"       content="@yield('meta_image', $setting->share_image)" />
    {{-- Fonts, Favicons --}}
    <link rel="icon" href="{{ asset($setting->favicon) }}" type="image/x-icon" />
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset($setting->favicon) }}" />
    {{-- CSS & JS --}}
    <link rel="stylesheet" href="{{asset('vendor/bootstrap/css/bootstrap.min.css')}}">
    <link rel="stylesheet" href="{{asset('vendor/fontawesome/css/all.min.css')}}">
    <link rel="stylesheet" href="{{asset('vendor/swiper/swiper-bundle.min.css')}}">
    <link rel="stylesheet" href="{{asset('plugins/sweetalert2/bootstrap-4.min.css')}}">
    <link rel="stylesheet" href="{{ asset('css/slide.css') }}?v={{ filemtime(public_path('css/slide.css')) }}">
    <link rel="stylesheet" href="{{ asset('css/global.css') }}?v={{ filemtime(public_path('css/global.css')) }}">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}?v={{ filemtime(public_path('css/style.css')) }}">
    <link rel="stylesheet" href="{{ asset('css/responsive.css') }}?v={{ filemtime(public_path('css/responsive.css')) }}">
    @stack('css')
    {!!$setting->head_script!!}
    @stack('jsonld')
    @stack('conversion_script')
</head>
<body>
    {!!$setting->body_script!!}
    @include('partials.frontend.header')
    @yield('content')
    @include('frontend.modal.contact')
    @include('frontend.modal.branch')
    @include('partials.frontend.footer')
    <script src="{{asset('/js/jquery-3.7.1.min.js')}}?{{time()}}"></script>
    <script src="{{asset('/vendor/bootstrap/popper.min.js')}}?{{time()}}"></script>
    <script src="{{asset('/vendor/bootstrap/js/bootstrap.min.js')}}?{{time()}}"></script>
    <script src="{{asset('/vendor/swiper/swiper-bundle.min.js')}}?{{time()}}"></script>
    <script src="{{asset('plugins/sweetalert2/sweetalert2.min.js')}}"></script>
    <script src="{{ asset('js/cart.js') }}"></script>
    @if(session('success'))
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Thành công',
            text: @json(session('success')),
            confirmButtonText: 'OK'
        });
    </script>
    @endif
    @if(session('error'))
    <script>
        Swal.fire({
            icon: 'error',
            title: 'Lỗi',
            text: @json(session('error')),
            confirmButtonText: 'OK'
        });
    </script>
    @endif
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            document.querySelector(".gotop").addEventListener("click", function(e) {
                e.preventDefault();
                window.scrollTo({
                    top: 0,
                    behavior: "smooth"
                });
            });
        });
    </script>
    <script type="text/javascript">
        function googleTranslateElementInit() {
            new google.translate.TranslateElement({
                pageLanguage: 'vi',      
                includedLanguages: 'vi,en', 
                autoDisplay: false
            }, 'google_translate_element');
            setActiveFlag();
        }
        function changeLanguage(lang) {
            var a = document.querySelector("#google_translate_element select");
            if (a) {
                a.value = lang;
                a.dispatchEvent(new Event('change'));
            }
        }
        function setActiveFlag() {
            var currentLang = getCookie('googtrans') ? getCookie('googtrans').split('/')[2] : 'vi';
            document.querySelectorAll('.language-switcher-flags a').forEach(function(el) {
                if (el.getAttribute('data-lang') === currentLang) {
                    el.classList.add('active');
                } else {
                    el.classList.remove('active');
                }
            });
        }
        function getCookie(name) {
            var value = "; " + document.cookie;
            var parts = value.split("; " + name + "=");
            if (parts.length == 2) return parts.pop().split(";").shift();
        }
        var originalTranslateElementInit = window.googleTranslateElementInit;
        window.googleTranslateElementInit = function() {
            originalTranslateElementInit();
            var observer = new MutationObserver(function(mutations) {
                mutations.forEach(function(mutation) {
                    if(mutation.type === 'attributes' && mutation.attributeName === 'class' && mutation.target.nodeName === 'BODY') {
                        if(!document.body.classList.contains('google-translating')) {
                            setActiveFlag();
                        }
                    }
                });
            });
            observer.observe(document.body, { attributes: true });
        };
    </script>
    <script type="text/javascript" src="https://translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>
    <script>
        $(document).ready(function(){
            $('#contactModal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget); 
            var productName = button.data('name'); 
            var modal = $(this);
            var messageContent = "Tôi đang quan tâm đến sản phẩm: " + productName + "\n\n";
            var messageTextarea = modal.find('textarea#message');
            messageTextarea.val(messageContent).focus();
            messageTextarea[0].setSelectionRange(messageContent.length, messageContent.length);
        });
        });
    </script>
    <script>
        $(document).ready(function() {
            $('.add-to-cart-btn').on('click', function(e) {
                e.preventDefault();
                const productId = $(this).data('product-id');
        const quantity = 1; 
        const isAuthenticated = $('meta[name="is-authenticated"]').attr('content') === 'true';
        if (isAuthenticated) {
            addToCartAPI(productId, quantity);
        } else {
            addToCartLocalStorage(productId, quantity);
        }
    });
            function addToCartAPI(productId, quantity) {
                $.ajax({
            url: '{{ route("cart.add") }}', 
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}', 
                product_id: productId,
                quantity: quantity
            },
            success: function(response) {
                alert(response.message); 
                $('body').addClass('show-cart-offcanvas');
            },
            error: function(xhr) {
                alert('Có lỗi xảy ra, vui lòng thử lại.');
            }
        });
            }
            function addToCartLocalStorage(productId, quantity) {
                let cart = JSON.parse(localStorage.getItem('cart')) || [];
                const existingItem = cart.find(item => item.productId === productId);
                if (existingItem) {
                    existingItem.quantity += quantity;
                } else {
                    const productName = $('.add-to-cart-btn[data-product-id="' + productId + '"]').data('product-name');
                    const productPrice = $('.add-to-cart-btn[data-product-id="' + productId + '"]').data('product-price');
                    const productImage = $('.add-to-cart-btn[data-product-id="' + productId + '"]').data('product-image');
                    cart.push({ 
                        productId: productId, 
                        quantity: quantity,
                        name: productName,
                        price: productPrice,
                        image: productImage
                    });
                }
                localStorage.setItem('cart', JSON.stringify(cart));
                alert('Sản phẩm đã được thêm vào giỏ!');
                updateOffCanvasFromLocalStorage();
                $('body').addClass('show-cart-offcanvas');
            }
            function updateOffCanvasFromLocalStorage() {
                const cart = JSON.parse(localStorage.getItem('cart')) || [];
                const offcanvasBody = $('.offcanvas-body');
        offcanvasBody.empty(); 
        let totalPrice = 0;
        if (cart.length === 0) {
            offcanvasBody.html('<p class="text-center">Giỏ hàng của bạn đang trống.</p>');
        } else {
            cart.forEach(item => {
                const itemHtml = `
                    <div class="cart-item" data-id="${item.productId}">
                        <div class="cart-item_image"><img src="${item.image}" alt="${item.name}"></div>
                        <div class="cart-item_info">
                            <a href="#" class="item-name">${item.name}</a>
                            <div class="item-meta">
                                <span class="item-price">${new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(item.price)}</span>
                                <span class="item-quantity">x ${item.quantity}</span>
                            </div>
                        </div>
                        <a href="#" class="item-remove"><i class="fa fa-trash"></i></a>
                    </div>
                `;
                offcanvasBody.append(itemHtml);
                totalPrice += item.price * item.quantity;
            });
        }
        $('.offcanvas-footer .total-price').text(new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(totalPrice));
    }
        }
    </script>
    @stack('js')
</body>
</html>