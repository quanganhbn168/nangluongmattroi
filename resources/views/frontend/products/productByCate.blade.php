@extends("layouts.master")

@section("title",$category->name)
@section("meta_description",$category->meta_description)
@section("meta_keywords",$category->meta_keywords)
@section("meta_image",$category->meta_image)

@push("css")

<link rel="stylesheet" href="{{asset('css/product.css')}}">

@endpush
<x-schema.category :category="$category" :products="$products" />

@section("content")

<section id="productByCate" class="mt-2">

	<div class="container">

		<nav aria-label="breadcrumb" id="breadcrumb">

			<ol class="breadcrumb">

				<li class="breadcrumb-item">

					<i class="fas fa-home"></i>

					<a href="/" class="text-uppercase">Trang chủ</a>

				</li>

				<li class="breadcrumb-item active" aria-current="page">{{$category->name}}</li>

			</ol>

		</nav>

		<div class="content-main">

			<div class="row">

				<div class="col-sm-9 col-xs-12">

					<h3 class="block-title title-module">

						<a href="{{route('slug.resolve',$category->slug)}}" class="text-uppercase">

							{{$category->name}}

						</a>

					</h3>

					<div class="list_products pt-2">

						<div class="row">

							@foreach($products as $product)

								@include('partials.frontend.product_item',['product'=>$product])

							@endforeach

						</div>

					</div>

				</div>

				<div class="col-sm-3 d-none d-sm-block">

					@include('partials.frontend.contact_register')

				</div>

			</div>

		</div>			

	</div>

</section>

<section id="branch" class="mt-3 py-3">

	<div class="container">

		<div class="row d-flex justify-content-between">

			@foreach($branches as $branch)

			<div class="col-sm-4 col-xs-12">

				<div class="branch">

					<div class="branch_item text-uppercase">

						<strong>{{$branch->name}}</strong>

					</div>

					<hr>

					<div class="branch_info">

						<p><strong>Địa chỉ: </strong>{{$branch->address}}</p>

						<p><strong>Hotline: </strong>{{$branch->phone}}</p>

						<p><strong>Email: </strong>{{$branch->email}}</p>

					</div>

				</div>

			</div>

			@endforeach

		</div>

	</div>

</section>

@endsection

@push("js")

@endpush