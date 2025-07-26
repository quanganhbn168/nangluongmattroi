{{-- resources/views/layouts/master.blade.php --}}
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    {{-- Basic --}}
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    {{-- CSRF --}}
    <meta name="csrf-token" content="{{ csrf_token() }}">

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
        // Hàm này được gọi khi thư viện Google được tải xong
        function googleTranslateElementInit() {
            new google.translate.TranslateElement({
                pageLanguage: 'vi',      // Ngôn ngữ gốc của trang
                includedLanguages: 'vi,en', // Các ngôn ngữ cần dịch
                autoDisplay: false
            }, 'google_translate_element');
            
            // Đặt trạng thái active cho cờ hiện tại
            setActiveFlag();
        }

        // Hàm để thay đổi ngôn ngữ khi bấm vào cờ
        function changeLanguage(lang) {
            var a = document.querySelector("#google_translate_element select");
            if (a) {
                a.value = lang;
                a.dispatchEvent(new Event('change'));
            }
        }

        // Hàm để làm nổi bật cờ của ngôn ngữ đang được chọn
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

        // Hàm tiện ích để đọc cookie
        function getCookie(name) {
            var value = "; " + document.cookie;
            var parts = value.split("; " + name + "=");
            if (parts.length == 2) return parts.pop().split(";").shift();
        }
        
        // Lắng nghe khi Google dịch xong để cập nhật lại cờ
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
            var button = $(event.relatedTarget); // Nút được bấm
            var productName = button.data('name'); // Lấy tên sản phẩm từ data-name
            var modal = $(this);
            
            // Tạo chuỗi nội dung quan tâm
            var messageContent = "Tôi đang quan tâm đến sản phẩm: " + productName + "\n\n";
            
            // Đặt nội dung này vào textarea
            // Đồng thời di chuyển con trỏ xuống cuối để khách hàng gõ tiếp
            var messageTextarea = modal.find('textarea#message');
            messageTextarea.val(messageContent).focus();
            messageTextarea[0].setSelectionRange(messageContent.length, messageContent.length);
        });
        });
    </script>
    @stack('js')
</body>
</html>
