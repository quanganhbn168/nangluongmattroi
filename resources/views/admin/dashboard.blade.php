@extends('layouts.admin')

{{-- Tiêu đề trang --}}
@section('title', 'Bảng điều khiển')

{{-- Header của trang --}}
@section('content_header')
    Bảng điều khiển
            @stop

{{-- Nội dung chính của trang --}}
@section('content')
    <div class="container-fluid">
        <div class="row">
            {{-- Tổng số sản phẩm --}}
            <div class="col-lg-4 col-6">
                <div class="small-box bg-info">
                    <div class="inner">
                        <h3>{{ $totalProducts ?? 0 }}</h3>
                        <p>Tổng số Sản phẩm</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-box"></i>
                    </div>
                    <a href="{{-- route('products.index') --}}" class="small-box-footer">
                        Xem chi tiết <i class="fas fa-arrow-circle-right"></i>
                    </a>
                </div>
            </div>

            {{-- Tổng số bài viết --}}
            <div class="col-lg-4 col-6">
                <div class="small-box bg-success">
                    <div class="inner">
                        <h3>{{ $totalPosts ?? 0 }}</h3>
                        <p>Tổng số Bài viết</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-newspaper"></i>
                    </div>
                    <a href="{{-- route('posts.index') --}}" class="small-box-footer">
                        Xem chi tiết <i class="fas fa-arrow-circle-right"></i>
                    </a>
                </div>
            </div>

            {{-- Tổng số liên hệ --}}
            <div class="col-lg-4 col-6">
                <div class="small-box bg-warning">
                    <div class="inner">
                        <h3>{{ $totalContacts ?? 0 }}</h3>
                        <p>Tổng số Liên hệ</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-envelope"></i>
                    </div>
                    <a href="{{-- route('contacts.index') --}}" class="small-box-footer">
                        Xem chi tiết <i class="fas fa-arrow-circle-right"></i>
                    </a>
                </div>
            </div>
        </div>
        {{-- Bạn có thể thêm các thành phần khác của dashboard tại đây --}}

    </div>@stop

{{-- Chèn CSS tùy chỉnh (nếu cần) --}}
@push('css')
    {{-- <link rel="stylesheet" href="/css/admin_custom.css"> --}}
@endpush

{{-- Chèn JS tùy chỉnh (nếu cần) --}}
@push('js')
    <script> console.log('Hi!'); </script>
@endpush