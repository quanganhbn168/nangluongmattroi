@extends('layouts.master')
@section('title',$product->name)
@push('css')
<link rel="stylesheet" href="{{asset('css/product.css')}}">
<style>
	fieldset.product_detail-saleoff{
		border: 1px solid #ddd;
		padding: 5px 10px;
	}
	fieldset.product_detail-saleoff legend{
		padding-left: 10px;
		padding-right: 10px;
		width: auto;
		color: red;
		font-size: 16px;
	}
	.product_des{}
	.product_des .nav-link{color: #000; font-weight: bold; background-color: #e9e6ed; border-radius:inherit;}
	.product_des .nav-link.active{color: #38578f; background-color: #fff;}
	.product_des .tab-content{border: 1px solid #ddd}
	.product_des .tab-content .tab-pane{text-align: justify}
	.title-cate:before,.title-cate:after {
		content: '';
		left: 0;
		height: 3px;
		position: absolute;
		bottom: -10px
	}
	.title-cate {
		text-transform: uppercase;
		font-weight: 700;
		font-size: 20px;
		width: 100%;
		position: relative;
	}
	.title-cate:before {
		background: rgba(84,84,84,.4);
		width: 100%
	}
	.title-cate:after {
		background: #2883e7;
		width: 100px
	}
	.title-cate span{
		font-size: 16px;
	}
	.add-to-cart{
		border-radius: inherit;
		color: #ffffff;
		font-weight: bold;
	}
	input#quantity {
		padding: 5px;
	}
	.add-to-cart:hover{
		box-shadow: 5px 5px gray;
	}
</style>
@endpush
<x-schema.product :product="$product"/>
@section("content")
<section id="product">
	<div class="container">
		<div class="row">
			<div class="col-md-12">
				<nav aria-label="breadcrumb" id="breadcrumb">
					<ol class="breadcrumb">
						<li class="breadcrumb-item">
							<a href="{{route('home')}}" class="text-uppercase">Trang chủ</a>
						</li>
						<li class="breadcrumb-item">
							<a href="{{route('slug.resolve',$product->category->slug)}}" class="text-uppercase">
								{{$product->category->name}}
							</a>
						</li>
						<li class="breadcrumb-item active" aria-current="page">{{$product->name}}</li>
					</ol>
				</nav>
			</div>
			<div class="col-md-9">
				<div class="product-detail">
					<div class="row">
						<div class="col-sm-6 col-xs-12">
						    <div class="product-detail_image">
						        <div style="--swiper-navigation-color: #fff; --swiper-pagination-color: #fff" class="swiper main-slider">
						            <div class="swiper-wrapper">
						                @if($images->isNotEmpty())
						                    @foreach($images as $image)
						                        <div class="swiper-slide">
						                            <img src="{{ asset($image->image) }}" alt="{{ $image->name }}">
						                        </div>
						                    @endforeach
						                @else
						                    <div class="swiper-slide">
						                        <img src="{{ asset($product->image) }}" alt="{{ $product->name }}">
						                    </div>
						                @endif
						            </div>
						            <div class="swiper-button-next"></div>
						            <div class="swiper-button-prev"></div>
						        </div>
						        <div class="swiper thumbnail-slider mt-2">
						            <div class="swiper-wrapper">
						                 @if($images->isNotEmpty())
						                    @foreach($images as $image)
						                        <div class="swiper-slide">
						                            <img src="{{ asset($image->image) }}" alt="{{ $image->name }}">
						                        </div>
						                    @endforeach
						                @else
						                    <div class="swiper-slide">
						                        <img src="{{ asset($product->image) }}" alt="{{ $product->name }}">
						                    </div>
						                @endif
						            </div>
						        </div>
						    </div>
						</div>
						<div class="col-sm-6 col-xs-12 detail_product">
							<h3 class="product_title font-weight-bold">{{$product->name}}</h3>
							<div class="price">
								<p class="product-code">
									<span>
										<strong>Mã sản phẩm: </strong>{{$product->code}}
									</span>
								</p>
								<p class="product-sm">
									<strong>Công xuất: </strong>
									{{$product->sm}}
								</p>
								<p class="product-ll">
									<strong>Lưu lượng khí xả: </strong>
									{{$product->ll}}
								</p>
								{{-- @if($product->price != 0)
								<span class="text-dark"><del>{{number_format($product->price,0)}}</del></span>
								@endif
								<span class="text-danger font-weight-bold display-5">
									{{number_format($product->price_unit,0)}}đ
								</span> --}}
								<div class="product_title">
									{!! nl2br(e($product->description)) !!}
								</div>
								<a href="#" data-id="{{$product->id}}" data-name='{{$product->name}}' class="btn btn-danger display-5 font-weight-bold text-uppercase" data-toggle="modal" data-target="#contactModal">
									Liên hệ 
								</a>
							</div>
							<div class="product-info">
								{!!$product->title!!}
							</div>
							{{-- <div class="cart mt-3">
								<form id="add-to-cart-form" method="post">
									<input type="number" id="quantity" name="quantity" value="1" min="1">
									<button data-id="{{$product->id}}" class="btn btn-primary add-to-cart"> Thêm vào giỏ hàng</button>
								</form>
							</div> --}}
						</div>
					</div>
				</div>
			</div>
			<div class="col-md-3 d-none d-sm-block">
				<div id="policy">
					<ul>
						<li class="item_policy first">
							<i class="fas fa-dollar-sign"></i>UY TÍN <br> HÀNG ĐẦU
						</li>
						<li class="item_policy">
							<i class="fa fa-umbrella"></i>BÁN HÀNG<br> CHÍNH HÃNG
						</li>
						<li class="item_policy">
							<i class="fas fa-dollar-sign"></i>CAM KẾT <br>GIÁ TỐT
						</li>
						<li class="item_policy">
							<i class="fas fa-truck"></i>GIAO HÀNG<br>TOÀN QUỐC
						</li>
						<li class="item_policy">
							<i class="fas fa-calendar-alt"></i>BẢO HÀNH <br> 1 NĂM ĐẾN 5 NĂM
						</li>
						<li class="item_policy">
							<i class="fas fa-calendar-alt"></i>DỊCH VỤ <br>HỖ TRỢ 24/7
						</li>
						<li class="item_policy yahoo-sky last">
							<a href="tel:0965625210" type="button" class="btn btn-success text-center w-100">Hotline: {{$setting->phone}}</a>
						</li>
					</ul>
				</div>
			</div>
			<div class="col-sm-12 col-xs-12">
				<div class="product_des mt-4">
					<nav>
						<div class="nav nav-tabs" id="nav-tab" role="tablist">
							<a class="nav-item m-0 text-black nav-link active" id="nav-home-tab" data-toggle="tab" href="#nav-home" role="tab" aria-controls="nav-home" aria-selected="true">Mô tả</a>
							<a class="nav-item m-0 text-black nav-link" id="nav-profile-tab" data-toggle="tab" href="#nav-profile" role="tab" aria-controls="nav-profile" aria-selected="false">Thông số kỹ thuật</a>
							{{-- <a class="nav-item m-0 text-black nav-link" id="nav-contact-tab" data-toggle="tab" href="#nav-contact" role="tab" aria-controls="nav-contact" aria-selected="false">Đánh giá</a> --}}
						</div>
					</nav>
					<div class="tab-content py-3 px-2" id="nav-tabContent">
						<div class="tab-pane fade show active" id="nav-home" role="tabpanel" aria-labelledby="nav-home-tab">
							{!!$product->content!!}
						</div>
						<div class="tab-pane fade" id="nav-profile" role="tabpanel" aria-labelledby="nav-profile-tab">
							{!!$product->specifications!!}
						</div>
						{{-- <div class="tab-pane fade" id="nav-contact" role="tabpanel" aria-labelledby="nav-contact-tab">
							Lorem ipsum dolor sit amet consectetur adipisicing elit. Officiis voluptate aliquid autem totam unde odio nam provident fugiat cupiditate, praesentium, ipsum impedit quis! Perspiciatis laudantium, ut exercitationem natus totam eaque sapiente! Illo doloremque placeat facere officiis nam dolorum repellat, sed magnam enim incidunt numquam fuga voluptatibus, molestias ab architecto, nemo.
						</div> --}}
					</div>
				</div>
			</div>
		</div>					
	</div>
</section>
<section class="related-products py-3 my-3">
	<div class="container">
		<h4 class="title-cate"><span>Sản phẩm cùng danh mục</span></h4>
		<div class="related-products_list py-3">
			<div class="row">
				@foreach($relatedProducts as $product)
				@include('partials.frontend.product_item',['product'=>$product])
				@endforeach
			</div>
		</div>
	</div>
</section>
<section class="related-products py-3 my-3">
	<div class="container">
		<h4 class="title-cate"><span>Tin tức liên quan</span></h4>
		<div class="related-products_list py-3">
			<div class="row">
				@foreach($posts as $post)
				<div class="col-12 col-sm-6 col-md-4 mb-4">
                {{-- Thêm class h-100 để các card trong cùng một hàng cao bằng nhau --}}
                <div class="news_item p-sm-3 p-2 h-100">
                    <div class="box_image">
                        <a href="{{route("frontend.post.detail", $post->slug)}}">
                            <img src="{{asset($post->image)}}" alt="{{$post->title}}" title="{{$post->title}}">
                        </a>
                    </div>
                    <div class="news_item-info text-center">
                        <a href="{{route("frontend.post.detail", $post->slug)}}" class="text-black font-weight-bold">
                            {{$post->title}}
                        </a>
                        <div class="info_des text-justify">
                            {{Str::limit($post->description, 100, '...')}}
                        </div>
                    </div>
                </div>
            </div>
				@endforeach
			</div>
		</div>
	</div>
</section>
@endsection
@push('js')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Khởi tạo slider ảnh nhỏ (thumbnail)
        var thumbnailSlider = new Swiper(".thumbnail-slider", {
            spaceBetween: 10,
            slidesPerView: 4, // Số lượng ảnh nhỏ hiển thị cùng lúc
            freeMode: true,
            watchSlidesProgress: true,
        });
        // Khởi tạo slider ảnh lớn
        var mainSlider = new Swiper(".main-slider", {
            spaceBetween: 10,
            navigation: {
                nextEl: ".swiper-button-next",
                prevEl: ".swiper-button-prev",
            },
            // Liên kết slider lớn với slider nhỏ
            thumbs: {
                swiper: thumbnailSlider,
            },
        });
    });
</script>
@endpush