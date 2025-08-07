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
  "url": "{{ url()->current() }}",
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
    <div class="container">
        <div class="row">
            <div class="col-lg-8 col-12">
                @include("partials.frontend.slide")
            </div>
            <div class="col-lg-4 col-12">
                <div class="banner-home p-2 mb-2">
                    <img src="https://placehold.co/500x250" alt="">
                </div>
                <div class="banner-home p-2 mb-2">
                    <img src="https://placehold.co/500x250" alt="">
                </div>
                
            </div>
        </div>
    </div>
</section>
<section class="feature">
    <div class="container">
        <div class="row">
            <div class="col-md-3 col-6">
                <div class="feature-item">
                    <div class="text-center">
                        <i class="fa-solid fa-piggy-bank"></i>
                    </div>
                    <h3 class="">Tiết kiệm tối đa</h3>
                    <p class="text-gray-600">Giảm đến 90% chi phí tiền điện hàng tháng, hoàn vốn nhanh chóng chỉ từ 4-5 năm.</p>
                </div>
            </div>
            <div class="col-md-3 col-6">
                <div class="feature-item">
                    <div class="text-center">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <h3 class="">Sản Phẩm Chính Hãng</h3>
                    <p class="text-gray-600">Giảm đến 90% chi phí tiền điện hàng tháng, hoàn vốn nhanh chóng chỉ từ 4-5 năm.</p>
                </div>
            </div>
            <div class="col-md-3 col-6">
                <div class="feature-item">
                    <div class="text-center">
                        <i class="fas fa-shield-alt"></i>
                    </div>
                    <h3 class="">Bảo Hành Dài Hạn</h3>
                    <p class="text-gray-600">Chính sách bảo hành hiệu suất 25 năm, bảo hành vật lý 12 năm, đảm bảo an tâm tuyệt đối.</p>
                </div>
            </div>
            <div class="col-md-3 col-6">
                <div class="feature-item">
                    <div class="text-center">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <h3 class="">Chính sách rõ ràng</h3>
                    <p class="text-gray-600">Giảm đến 90% chi phí tiền điện hàng tháng, hoàn vốn nhanh chóng chỉ từ 4-5 năm.</p>
                </div>
            </div>
        </div>
    </div>
</section>
<section class="categories">
    <div class="container">
        <div class="section-title">
            <h3>
                <a href="#" class="text-uppercase">Danh Mục Sản Phẩm Chính</a>
            </h3>
        </div>
        <div class="categories-wrapper">
            <div class="row">
                <div class="col-6 col-md-4 col-lg-2">
                    <div class="categories-item">
                        <div class="categories-item_image">
                            <img src="https://placehold.co/300x300/005a9c/FFFFFF?text=Tấm+Pin" alt="Tấm Pin Năng Lượng Mặt Trời">
                        </div>
                        <div class="categories-item_name">
                            <a href="#">Tấm Pin Mặt Trời</a>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-md-4 col-lg-2">
                    <div class="categories-item">
                        <div class="categories-item_image">
                            <img src="https://placehold.co/300x300/ffc107/333333?text=Inverter" alt="Biến tần (Inverter)">
                        </div>
                        <div class="categories-item_name">
                            <a href="#">Biến Tần (Inverter)</a>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-md-4 col-lg-2">
                    <div class="categories-item">
                        <div class="categories-item_image">
                            <img src="https://placehold.co/300x300/005a9c/FFFFFF?text=Pin+Lưu+Trữ" alt="Pin Lưu Trữ">
                        </div>
                        <div class="categories-item_name">
                            <a href="#">Pin Lưu Trữ (Hybrid)</a>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-md-4 col-lg-2">
                    <div class="categories-item">
                        <div class="categories-item_image">
                            <img src="https://placehold.co/300x300/ffc107/333333?text=Phụ+Kiện" alt="Phụ kiện lắp đặt">
                        </div>
                        <div class="categories-item_name">
                            <a href="#">Phụ Kiện Lắp Đặt</a>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-md-4 col-lg-2">
                    <div class="categories-item">
                        <div class="categories-item_image">
                            <img src="https://placehold.co/300x300/005a9c/FFFFFF?text=Đèn+NLMT" alt="Đèn năng lượng mặt trời">
                        </div>
                        <div class="categories-item_name">
                            <a href="#">Đèn Năng Lượng Mặt Trời</a>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-md-4 col-lg-2">
                    <div class="categories-item">
                        <div class="categories-item_image">
                            <img src="https://placehold.co/300x300/ffc107/333333?text=Hệ+Thống+Bơm" alt="Hệ thống bơm NLMT">
                        </div>
                        <div class="categories-item_name">
                            <a href="#">Hệ Thống Bơm NLMT</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<section class="product-list">
    <div class="container">
        <div class="product-widget">
            <div class="widget-title">
                <h3>
                    <a href="">Tấm pin năng lượng mặt trời</a>
                </h3>
                <div class="widget-tool">
                    <a href="">Xem thêm <i class="fa-solid fa-angles-right"></i></a>
                </div>
            </div>
            <div class="product-widget_wrapper">
                <div class="product-item">
                    <div class="product-item_image">
                        <a href="">
                            <img src="https://placehold.co/400" alt="">
                        </a>
                    </div>
                    <div class="product-item_info">
                        <h3><a href="">Tấm pin năng lượng mặt trời</a></h3>
                        <span class="price text-danger text-bold">Gọi để biết giá</span>
                    </div>
                </div>
                <div class="product-item">
                    <div class="product-item_image">
                        <a href="">
                            <img src="https://placehold.co/400" alt="">
                        </a>
                    </div>
                    <div class="product-item_info">
                        <h3><a href="">Tấm pin năng lượng mặt trời</a></h3>
                        <span class="price text-danger text-bold">Gọi để biết giá</span>
                    </div>
                </div>
                <div class="product-item">
                    <div class="product-item_image">
                        <a href="">
                            <img src="https://placehold.co/400" alt="">
                        </a>
                    </div>
                    <div class="product-item_info">
                        <h3><a href="">Tấm pin năng lượng mặt trời</a></h3>
                        <span class="price text-danger text-bold">Gọi để biết giá</span>
                    </div>
                </div>
                <div class="product-item">
                    <div class="product-item_image">
                        <a href="">
                            <img src="https://placehold.co/400" alt="">
                        </a>
                    </div>
                    <div class="product-item_info">
                        <h3><a href="">Tấm pin năng lượng mặt trời</a></h3>
                        <span class="price text-danger text-bold">Gọi để biết giá</span>
                    </div>
                </div>
                
            </div>
        </div>
    </div>
</section>
<section class="product-list">
    <div class="container">
        <div class="product-widget">
            <div class="widget-title">
                <h3>
                    <a href="">Bộ lưu trữ năng lượng mặt trời</a>
                </h3>
                <div class="widget-tool">
                    <a href="">Xem thêm <i class="fa-solid fa-angles-right"></i></a>
                </div>
            </div>
            <div class="product-widget_wrapper">
                <div class="product-item">
                    <div class="product-item_image">
                        <a href="">
                            <img src="https://placehold.co/400" alt="">
                        </a>
                    </div>
                    <div class="product-item_info">
                        <h3><a href="">Tấm pin năng lượng mặt trời</a></h3>
                        <span class="price text-danger text-bold">Gọi để biết giá</span>
                    </div>
                </div>
                <div class="product-item">
                    <div class="product-item_image">
                        <a href="">
                            <img src="https://placehold.co/400" alt="">
                        </a>
                    </div>
                    <div class="product-item_info">
                        <h3><a href="">Tấm pin năng lượng mặt trời</a></h3>
                        <span class="price text-danger text-bold">Gọi để biết giá</span>
                    </div>
                </div>
                <div class="product-item">
                    <div class="product-item_image">
                        <a href="">
                            <img src="https://placehold.co/400" alt="">
                        </a>
                    </div>
                    <div class="product-item_info">
                        <h3><a href="">Tấm pin năng lượng mặt trời</a></h3>
                        <span class="price text-danger text-bold">Gọi để biết giá</span>
                    </div>
                </div>
                <div class="product-item">
                    <div class="product-item_image">
                        <a href="">
                            <img src="https://placehold.co/400" alt="">
                        </a>
                    </div>
                    <div class="product-item_info">
                        <h3><a href="">Tấm pin năng lượng mặt trời</a></h3>
                        <span class="price text-danger text-bold">Gọi để biết giá</span>
                    </div>
                </div>
                <div class="product-item">
                    <div class="product-item_image">
                        <a href="">
                            <img src="https://placehold.co/400" alt="">
                        </a>
                    </div>
                    <div class="product-item_info">
                        <h3><a href="">Tấm pin năng lượng mặt trời</a></h3>
                        <span class="price text-danger text-bold">Gọi để biết giá</span>
                    </div>
                </div>
                <div class="product-item">
                    <div class="product-item_image">
                        <a href="">
                            <img src="https://placehold.co/400" alt="">
                        </a>
                    </div>
                    <div class="product-item_info">
                        <h3><a href="">Tấm pin năng lượng mặt trời</a></h3>
                        <span class="price text-danger text-bold">Gọi để biết giá</span>
                    </div>
                </div>
                
                <div class="product-item">
                    <div class="product-item_image">
                        <a href="">
                            <img src="https://placehold.co/400" alt="">
                        </a>
                    </div>
                    <div class="product-item_info">
                        <h3><a href="">Tấm pin năng lượng mặt trời</a></h3>
                        <span class="price text-danger text-bold">Gọi để biết giá</span>
                    </div>
                </div>
                
            </div>
        </div>
    </div>
</section>
<section class="news">
    <div class="container">
        <h2 class="section-title">
            <a href="">Tin tức</a>
            <div class="section-tool">
                <a href="">Xem thêm <i class="fa-solid fa-angles-right"></i></a>
            </div>
        </h2>
            <div class="row">
                <div class="col-12 col-md-6">
                    <div class="big-news">
                        <div class="big-news-image">
                            <img src="https://placehold.co/400" alt="">
                        </div>
                        <h3>
                            <a href="">Tin mới nhất</a>
                        </h3>
                    </div>
                </div>
                <div class="col-12 col-md-6">
                    <div class="news-item_list">
                        <div class="item_list-img">
                            <a href="">
                                <img src="https://placehold.co/400" alt="" width="80" height="80">
                            </a>
                        </div>
                        <div class="item_list-info">
                            <a href="">Tin tức 1</a>
                        </div>
                    </div>
                    <div class="news-item_list">
                        <div class="item_list-img">
                            <a href="">
                                <img src="https://placehold.co/400" alt="" width="80" height="80">
                            </a>
                        </div>
                        <div class="item_list-info">
                            <a href="">Tin tức 1</a>
                        </div>
                    </div>
                    <div class="news-item_list">
                        <div class="item_list-img">
                            <a href="">
                                <img src="https://placehold.co/400" alt="" width="80" height="80">
                            </a>
                        </div>
                        <div class="item_list-info">
                            <a href="">Tin tức 1</a>
                        </div>
                    </div>
                    <div class="news-item_list">
                        <div class="item_list-img">
                            <a href="">
                                <img src="https://placehold.co/400" alt="" width="80" height="80">
                            </a>
                        </div>
                        <div class="item_list-info">
                            <a href="">Tin tức 1</a>
                        </div>
                    </div>
                    
                </div>
            </div>
    </div>
</section>
<section id="contact" class="py-3">
    <div class="container">
        <div class="row">
            <div class="col-12 col-md-6 box-shadow bg-light mb-4">
                <h2 class="section-title text-dark text-center my-4">Liên hệ với chúng tôi</h2>
                <form id="contact-form" action="{{ route('contact.store') }}" method="POST" novalidate>
                    @csrf
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="name">Họ và tên <span class="text-danger">*</span></label>
                            <input type="text" name="name" id="name" class="form-control" required>
                        </div>

                        <div class="form-group col-md-6">
                            <label for="phone">Số điện thoại <span class="text-danger">*</span></label>
                            <input type="text" name="phone" id="phone" class="form-control" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="email">Email (không bắt buộc)</label>
                        <input type="email" name="email" id="email" class="form-control">
                    </div>

                    <div class="form-group">
                        <label for="message">Nội dung liên hệ</label>
                        <textarea name="message" id="message" rows="5" class="form-control" value="Tôi đang quan tâm đến sản phẩm"></textarea>
                    </div>

                    <div class="text-center mb-3">
                        <button type="submit" class="btn btn-dark">Gọi cho tôi</button>
                    </div>
                </form>
            </div>
            <div class="col-md-6 d-none d-md-block">
                <div class="contact-info__image">
                    <img src="{{asset($setting->logo)}}" alt="{{$setting->name}}">
                    <p class="">{{$setting->name}}</p>
                </div>
                <p class="contact-info__text">
                    Năng lượng mặt trời Tài Nguyễn
                </p>
                <div class="contact-info__button">
                    <a href="" class="w-100 btn btn-light rounded-pill d-block mb-3">{{$setting->phone}}</a>
                    <a href="" class="w-100 btn btn-dark rounded-pill d-block mb-3">Chat ngay</a>
                </div>
            </div>
        </div>
    </div>
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
                    message: {
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
                observer.unobserve(entry.target);
            }
        });
        }, {
            threshold: 0.6
        });

        counters.forEach(counter => observer.observe(counter));
    });
</script>
@endpush