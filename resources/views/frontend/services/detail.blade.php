@extends('layouts.master')
@section('title', $service->name)

@push('css')
<link rel="stylesheet" href="{{ asset('css/product.css') }}">
@endpush

@section('content')
<div id="category-wrapper">
    <!-- Banner -->
    <div class="category-banner">
        <img src="{{ asset($category->banner) }}" alt="{{ $service->name }}" width="1920" height="300">
        <div class="category-banner_overlay"></div>
        <div class="category-banner_info">
            <h1 class="category-banner_title">{{ $service->name }}</h1>
            <div class="category-banner_meta">
                <i class="fas fa-user"></i> Admin
                <span class="dot">•</span>
                <i class="fas fa-clock"></i> {{ $service->updated_at->format('d/m/Y') }}
            </div>
        </div>
    </div>

    <!-- Breadcrumb -->
    <div class="breadcrumb-wrapper">
        <div class="container">
            <ul class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ url('/') }}">Trang chủ</a></li>
                <li class="breadcrumb-item"><a href="{{ route('slug.resolve', $category->slug) }}">{{ $category->name }}</a></li>
                <li class="breadcrumb-item active">{{ $service->name }}</li>
            </ul>
        </div>
    </div>

    <!-- Nội dung -->
    <div class="container">
        <div class="row">
            <!-- Content -->
            <div class="col-md-9">
                <div class="service-detail">
                    <h1 class="service-title text-primary">{{ $service->name }}</h1>
                    <div class="service-content">
                        {!! $service->content !!}
                    </div>
                </div>

                <!-- Chia sẻ -->
                <div class="social-share mt-4">
                    <span class="social-share_label">Chia sẻ:</span>
                    <a href="#" class="social-share_item facebook"><i class="fab fa-facebook-f"></i></a>
                    <a href="#" class="social-share_item twitter"><i class="fab fa-twitter"></i></a>
                    <a href="#" class="social-share_item linkedin"><i class="fab fa-linkedin-in"></i></a>
                </div>

                <!-- Dịch vụ liên quan -->
                @if($relatedServices->count() > 0)
                <div class="related-services mt-5">
                    <h3 class="services-title text-center">Dịch vụ liên quan</h3>
                    <div class="row services-list">
                        @foreach($relatedServices as $related)
                        <div class="col-md-4 col-sm-6 mb-4">
                            <div class="services-list_item">
                                <a href="{{ route('slug.resolve', $related->slug) }}">
                                    <div class="item-image">
                                        <img src="{{ asset($related->image) }}" alt="{{ $related->name }}">
                                    </div>
                                    <div class="item-description">
                                        {{ $related->name }}
                                    </div>
                                </a>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>

            <!-- Sidebar -->
            @include("partials.frontend.aside")
        </div>
    </div>
</div>
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
                name: { required: true, minlength: 2 },
                phone: { required: true, phoneVN: true }
            },
            messages: {
                name: { required: "Vui lòng nhập họ và tên", minlength: "Tên quá ngắn" },
                phone: { required: "Vui lòng nhập số điện thoại", phoneVN: "Số điện thoại không hợp lệ" }
            },
            errorElement: 'small',
            errorClass: 'text-danger',
            highlight: function (element) { $(element).addClass('is-invalid'); },
            unhighlight: function (element) { $(element).removeClass('is-invalid'); }
        });
    });
</script>
@endpush
