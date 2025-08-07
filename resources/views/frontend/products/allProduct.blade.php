@extends('layouts.master')
@section('title','Tất cả sản phẩm')
@push('css')
<link rel="stylesheet" href="{{asset('css/product.css')}}">
@endpush
@section("content")
<div id="breadcrumb" class="bg-light">
	<div class="container">
		<nav aria-label="breadcrumb">
			<ol class="breadcrumb bg-light">
				<li class="breadcrumb-item"><a href="#">Trang chủ</a></li>
				<li class="breadcrumb-item active" aria-current="page">Tất cả sản phẩm</li>
			</ol>
		</nav>
	</div>
</div>
<div id="wrapper">
    <div class="container">
        <h2 class="text-uppercase">Tất cả sản phẩm</h2>
        <div class="banner">
            <img src="https://placehold.co/1920x300/005a9c/FFFFFF?text=Collection+Banner" alt="tất cả sản phẩm">
        </div>
        <div class="row">
            <aside class="col-lg-3 d-none d-lg-block">
                <div class="aside-filter">
                    <div class="aside-title">
                        <h3 class="title-head">
                            <i class="fa-solid fa-filter"></i>
                            Bộ lọc sản phẩm
                        </h3>
                    </div>
                    <div class="aside-content">
                        <div class="aside-item">
                            <div class="aside-item_title">Loại sản phẩm</div>
                            <ul class="aside-item_content">
                                <li>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="" id="cat1">
                                        <label class="form-check-label" for="cat1">Tấm Pin Mặt Trời</label>
                                    </div>
                                </li>
                                <li>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="" id="cat2">
                                        <label class="form-check-label" for="cat2">Biến Tần (Inverter)</label>
                                    </div>
                                </li>
                                 <li>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="" id="cat3">
                                        <label class="form-check-label" for="cat3">Pin Lưu Trữ</label>
                                    </div>
                                </li>
                            </ul>
                        </div>
                        <div class="aside-item">
                            <div class="aside-item_title">Khoảng giá</div>
                            <ul class="aside-item_content">
                                <li>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="" id="price1">
                                        <label class="form-check-label" for="price1">Dưới 1,000,000đ</label>
                                    </div>
                                </li>
                                <li>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="" id="price2">
                                        <label class="form-check-label" for="price2">Từ 1,000,000đ - 5,000,000đ</label>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </aside>

            <div class="col-lg-9 col-md-12">
                <div class="sort-products">
                    <div class="sort-by">
                        <label for="sort-options">Sắp xếp theo:</label>
                        <select class="form-control" id="sort-options">
                            <option>Mới nhất</option>
                            <option>Giá: Tăng dần</option>
                            <option>Giá: Giảm dần</option>
                            <option>Tên: A-Z</option>
                        </select>
                    </div>
                    <button class="btn btn-outline-dark d-lg-none">
                        <i class="fa-solid fa-filter"></i> Lọc
                    </button>
                </div>

                <div class="product-widget_wrapper">
                    <div class="product-item">
                        <div class="product-item_image">
                            <a href="#"><img src="https://placehold.co/400" alt="Sản phẩm"></a>
                        </div>
                        <div class="product-item_info">
                            <h3><a href="#">Tên sản phẩm mẫu</a></h3>
                            <span class="price text-danger text-bold">1,250,000đ</span>
                        </div>
                    </div>
                    </div>

                <nav class="pagination-wrapper">
                  <ul class="pagination">
                    <li class="page-item disabled"><a class="page-link" href="#">Trước</a></li>
                    <li class="page-item active"><a class="page-link" href="#">1</a></li>
                    <li class="page-item"><a class="page-link" href="#">2</a></li>
                    <li class="page-item"><a class="page-link" href="#">3</a></li>
                    <li class="page-item"><a class="page-link" href="#">Sau</a></li>
                  </ul>
                </nav>
            </div>
        </div>			
    </div>
</div>
@endsection
@push('js')

@endpush