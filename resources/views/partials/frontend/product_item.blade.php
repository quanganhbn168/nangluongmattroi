<div class="product_item">
	<div class="box_image">
		<a href="{{route('frontend.product.show',$product->slug)}}" class="image_link">
			@if($product->image != null)
			<img src="{{asset($product->image)}}" alt="{{$product->name}}">
			@else
			<img src="{{asset('images/setting/dang-cap-nhat-san-pham.png')}}" alt="{{$product->name}}">
			@endif
		</a>
	</div>
	<div class="product_item-info text-center">
		<p class="product-code">{{$product->code}}</p>
		<a href="{{route('frontend.product.show',$product->slug)}}" class="text-black font-weight-bold">{{$product->name}}</a>
		<div class="product-price">
			<span>{{$product->price}}</span>
			<span>{{$product->price_discount}}</span>
		</div>
		<div class="">
			<a href="#">
				Thêm giỏ hàng 
				<i class="text-primary fa-solid fa-cart-shopping"></i>
			</a>
		</div>
	</div>
	{{-- <div class="product_item-price text-center py-2">
		<a href="#" class="text-danger font-weight-bold text-uppercase contact_link" data-id="{{$product->id}}" data-name="{{$product->name}}" data-toggle="modal" data-target="#contactModal">Liên hệ</a>
	</div> --}}
</div>
