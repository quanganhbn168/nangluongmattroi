@php
    // Logic tính toán giảm giá, đưa vào đây để tái sử dụng
    $hasDiscount = $product->price_discount && $product->price > $product->price_discount;
    if ($hasDiscount) {
        $discountPercentage = round((($product->price - $product->price_discount) / $product->price) * 100);
    }
@endphp

<div class="product-card">
    <div class="product-card__image-wrapper">
        <a href="{{ route('frontend.product.show', $product->slug) }}">
            <img src="{{ asset($product->image ?? 'images/setting/no-image.png') }}" class="product-card__image" alt="{{ $product->name }}">
        </a>

        @if($hasDiscount)
            <div class="product-card__discount-badge">-{{ $discountPercentage }}%</div>
        @endif
        
        <div class="product-card__actions">
            <button class="action-btn btn-add-to-cart"
                    data-id="{{ $product->id }}"
                    data-name="{{ $product->name }}"
                    data-price="{{ $hasDiscount ? $product->price_discount : $product->price }}"
                    data-image="{{ asset($product->image ?? 'images/setting/no-image.png') }}"
                    data-slug="{{ $product->slug }}" 
                    data-quantity="1"
                    title="Thêm vào giỏ hàng">
                <i class="fa fa-cart-plus"></i>
            </button>
            <a href="{{ route('frontend.product.show', $product->slug) }}" class="action-btn" title="Xem chi tiết">
                <i class="fa fa-eye"></i>
            </a>
        </div>
    </div>
    <div class="product-card__body">
        <h3 class="product-card__title">
            <a href="{{ route('frontend.product.show', $product->slug) }}" title="{{ $product->name }}">{{ $product->name }}</a>
        </h3>
        <div class="product-card__price">
            @if($product->price > 0)
                @if($hasDiscount)
                    <span class="price-new">{{ number_format($product->price_discount) }}đ</span>
                    <del class="price-old">{{ number_format($product->price) }}đ</del>
                @else
                    <span class="price-new">{{ number_format($product->price) }}đ</span>
                @endif
            @else
                <span class="price-contact">Liên hệ</span>
            @endif
        </div>
    </div>
</div>