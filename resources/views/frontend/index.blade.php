@extends('layouts.master')
@section('title','Trang chủ - '.$setting->name)
@section('meta_description',$setting->meta_description)
@section('meta_keywords',$setting->meta_keywords)
@push('jsonld')
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "Store",
  "name": "{{$setting->name}}",
  "alternateName": "{{$setting->name}}",
  "url": "{{config(APP_URL)}}",
  "logo": "{{asset($setting->logo)}}",
  "description": "{{$setting->meta_description}}",
  "address": {
    "@type": "PostalAddress",
    "streetAddress": "{{$setting->address}}",
    "addressLocality": "Thành phố Bắc Ninh",
    "addressRegion": "Bắc Ninh",
    "postalCode": "220000",
    "addressCountry": "VN"
  },
  "telephone": "{{$setting->phone}}",
  "email": "{{$setting->email}}",
  "openingHoursSpecification": [
    {
      "@type": "OpeningHoursSpecification",
      "dayOfWeek": [
        "Monday",
        "Tuesday",
        "Wednesday",
        "Thursday",
        "Friday"
      ],
      "opens": "08:00",
      "closes": "17:30"
    },
    {
      "@type": "OpeningHoursSpecification",
      "dayOfWeek": "Saturday",
      "opens": "08:00",
      "closes": "12:00"
    }
  ],
  "sameAs": [
    "{{$setting->facebook}}",
    "{{$setting->youtube}}",
    "{{$setting->zalo}}"
  ]
}
</script>
@endpush
@push('css')
<link rel="stylesheet" href="{{asset('vendor/glightbox/css/glightbox.min.css')}}?{{time()}}">
@endpush
@section("content")
<section id="slider">
    @include("partials.frontend.slide")
</section>
<p>DANH MỤC SẢN PHẨM</p>
<section class="section sanphamnoibat">
    <div class="container">
        <p class="text-uppercase">Gợi ý cho bạn</p>
        <p>Chào mừng bạn! Có thể bạn sẽ thích sản phẩm này</p>
        <div class="sanphamnoibat-list">
            <div class="swiper-container sanphamnoibat-slider">
                <div class="swiper-wrapper">
                    @foreach($randomProducts as $key => $product)
                    <div class="swiper-slider">
                        @include('partials.frontend.product_item',['product'=>$product])
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>
<section class="section product-list">
    <div class="container">
        <h2 class="section-title">
            <span>Sản phẩm bán chạy</span>
        </h2>
        <div class="product-list_hot grid-5">
            @foreach($hotProducts as $key => $product)
                @include('partials.frontend.product',['product'=>$product])
            @endforeach
            <div class="text-center">
                <a href="#" class="btn btn-primary">Xem thêm</a>
            </div>
        </div>
    </div>
</section>
<section class="section category-list">
    @foreach($homeCategory as $key => $category)
    <div class="container">
        <div class="category-banenr">
            <img src="{{asset($category->banner)}}" alt="{{$category->name}}">
        </div>
        <div class="category-list_product">
            <div class="section-title">
                <h2 class="name-category">{{$category->name}}</h2>
                <a href="#" class="see-more">Xem thêm</a>
            </div>
            <div class="list_product-wrapper grid-5">
                @foreach($category->product()->where('status',1) as $key => $product)
                    @include('partials.frontend.product',['product'=>$product])
                @endforeach
            </div>
        </div>
    </div>
    @endforeach
</section>
<section class="section dichvu">
    <h2 class="section-title">Dịch vụ chúng tôi cung cấp</h2>
    <div class="container">
        
    </div>
</section>
<section class="section nhungconso">
    <div class="container">
        <div class="row">
            <div class="col-12 col-md-12 text-center">
                <h3>NHỮNG CON SỐ KHẲNG ĐỊNH CHẤT LƯỢNG</h3>
                <p>Mỗi dự án là một dấu ấn, mỗi đơn hàng là minh chứng cho sự tin tưởng và đồng hành của khách hàng. Mỗi sản phẩm gửi đi là tâm huyết của Masu Việt Nam.</p>

<p>Đây là những con số nổi bật sau hơn 1 năm ra nhập thị trường của MASU</p>
<div class="text-center"></div>
<div class="counter">
    <ul>
        <li>
            <p class="counter-number" data-target="500">0</p>
            <p>Đơn hàng</p>
        </li>
        <li>
            <p class="counter-number" data-target="3000">0</p>
            <p>Sản phẩm đã bán</p>
        </li>
        <li>
            <p class="counter-number" data-target="70">0</p>
            <p>Đại lý phân phối</p>
        </li>
        <li>
            <p class="counter-number" data-target="98" data-suffix="%">0%</p>
            <p>Phản hồi tích cực</p>
        </li>
    </ul>
</div>

            </div>
        </div>
    </div>
</section>
<section class="section tintuc">
    @foreach($serviceCategoryHome as $key => $category)
        <div class="tintuc-top">
            <h2 class="tintuc-title">{{$category->name}}</h2>
            <a href="" class="">Xem thêm <i class="fa-solid fa-angles-right"></i></a>
        </div>
        <div class="tintuc-wrapper">
            @foreach($category->post()->where('status',1)->get() as $key => $post)
            <div class="tintuc-item">
                <div class="card">
                    <div class="card-body">
                        <a href="{{route('slug.resolve',$post->slug)}}">
                            <img src="{{asset($post->image)}}" alt="{{$post->name}}">
                        </a>
                    </div>
                    <div class="card-footer">
                        <a href="{{route('slug.resolve',$post->slug)}}">
                            {{$post->name}}
                        </a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    @endforeach
</section>           
@endsection
@push('js')
<script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.5/dist/jquery.validate.min.js"></script>
<script>
        $.validator.addMethod("phoneVN", function (value, element) {
            return this.optional(element) || /^(0[3|5|7|8|9])[0-9]{8}$|^\+84[3|5|7|8|9][0-9]{8}$/.test(value);
        }, "Số điện thoại không hợp lệ");
        $(document).ready(function () {
            $('#contact-form').validate({
                rules: {
                    name: {
                        required: true,
                        minlength: 2
                    },
                    phone: {
                        required: true,
                        phoneVN: true
                    },
                    email: {
                        email: true
                    },
                    content: {
                        maxlength: 1000
                    }
                },
                messages: {
                    name: {
                        required: "Vui lòng nhập họ và tên",
                        minlength: "Tên quá ngắn"
                    },
                    phone: {
                        required: "Vui lòng nhập số điện thoại",
                        phoneVN: "Số điện thoại không hợp lệ (ví dụ: 098xxxxxxx)"
                    },
                    email: {
                        email: "Email không hợp lệ"
                    },
                    message: {
                        maxlength: "Ý kiến không vượt quá 1000 ký tự"
                    }
                },
                errorElement: 'small',
                errorClass: 'text-danger',
                highlight: function (element) {
                    $(element).addClass('is-invalid');
                },
                unhighlight: function (element) {
                    $(element).removeClass('is-invalid');
                }
            });
        });
</script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const counters = document.querySelectorAll('.counter-number');

        const runCounter = (el) => {
            const target = +el.getAttribute('data-target');
            const suffix = el.getAttribute('data-suffix') || '';
            let count = 0;
            const speed = 20;
            const step = Math.ceil(target / 60);

            const update = () => {
                count += step;
                if (count >= target) {
                    el.textContent = target.toLocaleString() + suffix;
                } else {
                    el.textContent = count.toLocaleString() + suffix;
                    requestAnimationFrame(update);
                }
            };

            update();
        };

        const observer = new IntersectionObserver(entries => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    runCounter(entry.target);
                observer.unobserve(entry.target); // chỉ chạy 1 lần
            }
        });
        }, {
            threshold: 0.6
        });

        counters.forEach(counter => observer.observe(counter));
    });
</script>

<script>
const partnerSwiper = new Swiper('.partner-swiper', {
    slidesPerView: 4,
    centeredSlides: true,
    spaceBetween: 30,
    loop: true,
    autoplay: {
                delay: 2500,
                disableOnInteraction: false,
            },
    navigation: {
        nextEl: '.swiper-button-next',
        prevEl: '.swiper-button-prev',
    },
    breakpoints: {
        768: {
            slidesPerView: 3,
        }
    }
});
</script>
<script>
const best = new Swiper('.sanphamnoibat-slider', {
    slidesPerView: 5,
    spaceBetween: 20,
    loop: true,
    autoplay: {
        delay: 2000,
        disableOnInteraction: false,
    },
    navigation: {
        nextEl: '.swiper-button-next',
        prevEl: '.swiper-button-prev',
    },
    breakpoints: {
        0: {
            slidesPerView: 1,
        },
        576: {
            slidesPerView: 2,
        },
        768: {
            slidesPerView: 3,
        },
        992: {
            slidesPerView: 5,
        },
    }
});
</script>
<script>
    const slider = new Swiper('.slider', {
        slidesPerView: 1,
        centeredSlides: true,
        spaceBetween: 20,
        loop: true,
        navigation: {
            nextEl: '.swiper-button-next',
            prevEl: '.swiper-button-prev',
        },
        autoplay: {
            delay: 3000, // thời gian chuyển slide, tính bằng ms (3000 = 3 giây)
            disableOnInteraction: false // vẫn tiếp tục autoplay sau khi người dùng tương tác
        },
        breakpoints: {
            768: {
                slidesPerView: 1,
            }
        }
    });
</script>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        new Swiper('.testimonial-swiper', {
            loop: true,
            autoplay: {
                delay: 5000,
                disableOnInteraction: false,
            },
            spaceBetween: 20,
            navigation: {
                nextEl: '.swiper-button-next',
                prevEl: '.swiper-button-prev',
            },
            breakpoints: {
                768: {
                    slidesPerView: 2,
                },
                992: {
                    slidesPerView: 3,
                },
                576: {
                    slidesPerView: 1,
                },
            }
        });
    });
</script>
<script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.5/dist/jquery.validate.min.js"></script>
        <script>
    // Custom rule kiểm tra số điện thoại Việt Nam
            $.validator.addMethod("phoneVN", function (value, element) {
                return this.optional(element) || /^(0[3|5|7|8|9])[0-9]{8}$|^\+84[3|5|7|8|9][0-9]{8}$/.test(value);
            }, "Số điện thoại không hợp lệ");
            $(document).ready(function () {
                $('#contact-form').validate({
                    rules: {
                        name: {
                            required: true,
                            minlength: 2
                        },
                        phone: {
                            required: true,
                            phoneVN: true
                        },
                        email: {
                            email: true
                        },
                        content: {
                            maxlength: 1000
                        }
                    },
                    messages: {
                        name: {
                            required: "Vui lòng nhập họ và tên",
                            minlength: "Tên quá ngắn"
                        },
                        phone: {
                            required: "Vui lòng nhập số điện thoại",
                            phoneVN: "Số điện thoại không hợp lệ (ví dụ: 098xxxxxxx)"
                        },
                        email: {
                            email: "Email không hợp lệ"
                        },
                        message: {
                            maxlength: "Ý kiến không vượt quá 1000 ký tự"
                        }
                    },
                    errorElement: 'small',
                    errorClass: 'text-danger',
                    highlight: function (element) {
                        $(element).addClass('is-invalid');
                    },
                    unhighlight: function (element) {
                        $(element).removeClass('is-invalid');
                    }
                });
            });
        </script>
@endpush