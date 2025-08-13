@extends('layouts.master')

@section('title', $product->name)

@push('css')
<link rel="stylesheet" href="{{asset('css/product.css')}}">
@endpush

<x-schema.product :product="$product"/>

@section("content")
<section id="product-detail-page" class="py-4">
    <div class="container">
        {{-- BREADCRUMB --}}
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-transparent px-0 py-2">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Trang chủ</a></li>
                <li class="breadcrumb-item"><a href="{{ route('products.by_category', $product->category->slug) }}">{{ $product->category->name }}</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{ Str::limit($product->name, 50) }}</li>
            </ol>
        </nav>

        <div class="product-main-content bg-white p-3 p-md-4">
            <div class="row">
                {{-- CỘT BÊN TRÁI: GALLERY ẢNH --}}
                <div class="col-lg-5">
                    <div class="product-gallery">
                        <div class="swiper main-slider mb-3">
                            <div class="swiper-wrapper">
                                @if($product->images->isNotEmpty())
                                    @foreach($product->images as $image)
                                        <div class="swiper-slide"><img src="{{ asset($image->image) }}" class="img-fluid" alt="{{ $image->name }}"></div>
                                    @endforeach
                                @else
                                    <div class="swiper-slide"><img src="{{ asset($product->image) }}" class="img-fluid" alt="{{ $product->name }}"></div>
                                @endif
                            </div>
                            <div class="swiper-button-next"></div>
                            <div class="swiper-button-prev"></div>
                        </div>
                        <div class="swiper thumbnail-slider">
                            <div class="swiper-wrapper">
                                @if($product->images->isNotEmpty())
                                    @foreach($product->images as $image)
                                        <div class="swiper-slide"><img src="{{ asset($image->image) }}" class="img-fluid" alt="{{ $image->name }}"></div>
                                    @endforeach
                                @else
                                    <div class="swiper-slide"><img src="{{ asset($product->image) }}" class="img-fluid" alt="{{ $product->name }}"></div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                {{-- CỘT GIỮA: THÔNG TIN SẢN PHẨM --}}
                <div class="col-lg-4">
                    <div class="product-info">
                        <h1 class="product-title">{{ $product->name }}</h1>
                        <div class="product-meta">
                            <span>Mã: <strong>{{ $product->code ?? 'Đang cập nhật' }}</strong></span>
                            <span class="mx-2">|</span>
                            <span>Tình trạng: <strong class="text-success">Còn hàng</strong></span>
                        </div>

                        <div class="price-area">
                            @php
                                $hasDiscount = $product->price_discount && $product->price > $product->price_discount;
                            @endphp
                            <span class="price-new">{{ number_format($hasDiscount ? $product->price_discount : $product->price) }}đ</span>
                            @if($hasDiscount)
                                <del class="price-old">{{ number_format($product->price) }}đ</del>
                                <span class="discount-badge">
                                    -{{ round((($product->price - $product->price_discount) / $product->price) * 100) }}%
                                </span>
                            @endif
                        </div>

                        {{-- Lựa chọn biến thể --}}
                        @php
                            $displayableVariants = $product->variants->filter(fn($v) => $v->attributeValues->isNotEmpty());
                        @endphp
                        @if($displayableVariants->isNotEmpty())
                            <div class="variants-container">
                                @foreach ($displayableVariants->groupBy('attributeValues.first.attribute.name') as $attributeName => $variants)
                                <div class="variant-group">
                                    <label class="variant-label">{{ $attributeName }}:</label>
                                    <div class="variant-options">
                                        @foreach ($variants->pluck('attributeValues.first')->unique('id') as $attributeValue)
                                            <div class="variant-item">
                                                <input type="radio" id="variant_{{ $attributeValue->id }}" name="attribute_{{ Str::slug($attributeName) }}" value="{{ $attributeValue->id }}" class="variant-selector">
                                                <label for="variant_{{ $attributeValue->id }}">{{ $attributeValue->value }}</label>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        @endif
                        
                        {{-- Chọn số lượng --}}
                        <div class="quantity-area">
                            <label class="quantity-label">Số lượng:</label>
                            <div class="quantity-input">
                                <button type="button" class="quantity-btn" data-action="decrease">-</button>
                                <input type="number" id="quantity" value="1" min="1">
                                <button type="button" class="quantity-btn" data-action="increase">+</button>
                            </div>
                        </div>

                        {{-- Nút bấm hành động --}}
                        <div class="action-buttons">
                            <button id="add-to-cart-btn" class="btn btn-add-to-cart"
                                data-id="{{ $product->id }}"
                                data-name="{{ $product->name }}"
                                data-price="{{ $product->price_discount ?? $product->price }}"
                                data-image="{{ asset($product->image) }}"
                                data-url="{{ route('frontend.product.show', $product->slug) }}"
                                data-quantity="1">
                                Thêm vào giỏ
                            </button>
                            <button id="buy-now-btn" class="btn btn-buy-now">Mua ngay</button>
                        </div>
                        <form id="buy-now-form" class="d-none" method="POST" action="{{ route('cart.buy_now') }}">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $product->id }}">
                            <input type="hidden" name="variant_id" value="">
                            <input type="hidden" name="quantity" value="1">
                        </form>
                    </div>
                </div>

                {{-- CỘT BÊN PHẢI: CHÍNH SÁCH --}}
                <div class="col-lg-3">
                    <div class="policy-commitment">
                        <ul class="list-unstyled">
                            <li class="policy-item">
                                <div class="policy-icon"><i class="fa fa-check-circle"></i></div>
                                <div class="policy-text">
                                    <strong>Cam kết chuẩn chất lượng</strong>
                                    <span>Đảm bảo chất lượng như mô tả</span>
                                </div>
                            </li>
                            <li class="policy-item">
                                <div class="policy-icon"><i class="fa fa-truck"></i></div>
                                <div class="policy-text">
                                    <strong>Giao hàng nhanh toàn quốc</strong>
                                    <span>Vận chuyển an toàn, nhanh chóng</span>
                                </div>
                            </li>
                             <li class="policy-item">
                                <div class="policy-icon"><i class="fa fa-sync-alt"></i></div>
                                <div class="policy-text">
                                    <strong>Đổi trả hàng trong 7 ngày</strong>
                                    <span>Nếu có lỗi từ nhà sản xuất</span>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        {{-- ====================================================================== --}}
{{-- =      BẮT ĐẦU KHỐI TABS VÀ SẢN PHẨM LIÊN QUAN                    --}}
{{-- ====================================================================== --}}
<div class="product-details-tabs mt-4">
    {{-- THANH ĐIỀU HƯỚNG TABS --}}
    <ul class="nav nav-tabs" id="productTab" role="tablist">
        <li class="nav-item">
            {{-- Dùng <a> thay cho <button> và đổi data-bs-* thành data-* --}}
            <a class="nav-link active" id="description-tab" data-toggle="tab" href="#description" role="tab" aria-controls="description" aria-selected="true">Mô tả sản phẩm</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="specifications-tab" data-toggle="tab" href="#specifications" role="tab" aria-controls="specifications" aria-selected="false">Thông số kỹ thuật</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="reviews-tab" data-toggle="tab" href="#reviews" role="tab" aria-controls="reviews" aria-selected="false">Đánh giá</a>
        </li>
    </ul>

    {{-- NỘI DUNG CÁC TABS (Giữ nguyên, không thay đổi) --}}
    <div class="tab-content bg-white p-3 p-md-4" id="productTabContent">
        {{-- Tab Mô tả --}}
        <div class="tab-pane fade show active" id="description" role="tabpanel" aria-labelledby="description-tab">
            @if($product->content)
                {!! $product->content !!}
            @else
                <p>Nội dung đang được cập nhật...</p>
            @endif
        </div>
        {{-- Tab Thông số kỹ thuật --}}
        <div class="tab-pane fade" id="specifications" role="tabpanel" aria-labelledby="specifications-tab">
            @if($product->specifications)
                {!! $product->specifications !!}
            @else
                <p>Thông số kỹ thuật đang được cập nhật...</p>
            @endif
        </div>
        {{-- Tab Đánh giá --}}
        <div class="tab-pane fade" id="reviews" role="tabpanel" aria-labelledby="reviews-tab">
            <p>Tính năng đánh giá sản phẩm sẽ sớm được ra mắt.</p>
        </div>
    </div>
</div>

{{-- SẢN PHẨM LIÊN QUAN --}}
@if(isset($relatedProducts) && $relatedProducts->isNotEmpty())
<div class="related-products mt-5">
    <h3 class="section-title">Sản phẩm liên quan</h3>
    <div class="row">
        @foreach($relatedProducts as $related)
        <div class="col-6 col-md-4 col-lg-3">
            {{-- Sử dụng component card sản phẩm chung (nếu có) --}}
            {{-- Giả sử anh có một component tên là `product-card` --}}
            @include('partials.frontend.product_item',['product'=>$related])
        </div>
        @endforeach
    </div>
</div>
@endif

{{-- ====================================================================== --}}
{{-- =      KẾT THÚC KHỐI TABS VÀ SẢN PHẨM LIÊN QUAN                     --}}
{{-- ====================================================================== --}}
    </div>
</section>
@endsection
@push('js')
<script>
document.addEventListener('DOMContentLoaded', function () {
    // =================================================================
    // KHỞI TẠO SWIPER GALLERY
    // =================================================================
    var thumbnailSlider = new Swiper(".thumbnail-slider", {
        spaceBetween: 10,
        slidesPerView: 4,
        freeMode: true,
        watchSlidesProgress: true,
    });
    var mainSlider = new Swiper(".main-slider", {
        spaceBetween: 10,
        navigation: {
            nextEl: ".swiper-button-next",
            prevEl: ".swiper-button-prev",
        },
        thumbs: {
            swiper: thumbnailSlider,
        },
    });

    // =================================================================
    // KHAI BÁO BIẾN CHUNG
    // =================================================================
    const quantityInput = document.getElementById('quantity');
    const quantityButtons = document.querySelectorAll('.quantity-btn');
    const addToCartBtn = document.getElementById('add-to-cart-btn');
    const variantSelectors = document.querySelectorAll('.variant-selector');

    // =================================================================
    // LOGIC XỬ LÝ SỐ LƯỢNG (ĐÃ TỐI ƯU)
    // =================================================================
    // Hàm cập nhật data-quantity trên nút "Thêm vào giỏ"
        // ============================
    // NÚT MUA NGAY
    // ============================
    const buyNowBtn  = document.getElementById('buy-now-btn');
    const buyNowForm = document.getElementById('buy-now-form');

    function allVariantGroupsSelected() {
        const groups = document.querySelectorAll('.variant-group');
        if (!groups.length) return true;
        const selected = document.querySelectorAll('.variant-selector:checked');
        return selected.length === groups.length;
    }

    if (buyNowBtn && buyNowForm) {
        buyNowBtn.addEventListener('click', function (e) {
            e.preventDefault();

            // Yêu cầu chọn đủ biến thể (nếu có)
            if (!allVariantGroupsSelected()) {
                alert('Vui lòng chọn đầy đủ tùy chọn sản phẩm.');
                return;
            }

            // Lấy ID đang gắn trên nút add-to-cart:
            // - Nếu có biến thể và đã chọn, anh đang set dataset.id = variant_id trong logic phía trên
            // - Nếu không có biến thể, dataset.id = product_id
            const chosenId = addToCartBtn ? addToCartBtn.dataset.id : "{{ $product->id }}";
            const baseProductId = "{{ $product->id }}";
            const qty = Math.max(1, parseInt(quantityInput?.value || '1', 10));

            // Gán vào form ẩn rồi submit
            buyNowForm.querySelector('input[name="product_id"]').value = baseProductId;
            buyNowForm.querySelector('input[name="variant_id"]').value = (chosenId !== baseProductId) ? chosenId : '';
            buyNowForm.querySelector('input[name="quantity"]').value   = qty;

            buyNowForm.submit();
        });
    }

    function updateCartButtonQuantity() {
        if (addToCartBtn && quantityInput) {
            const newQuantity = parseInt(quantityInput.value, 10);
            if (newQuantity > 0) {
                addToCartBtn.dataset.quantity = newQuantity;
            }
        }
    }
    
    // Lắng nghe sự kiện click trên nút +/-
    quantityButtons.forEach(button => {
        button.addEventListener('click', function() {
            // 1. Thay đổi giá trị trong ô input
            const action = this.dataset.action;
            let currentValue = parseInt(quantityInput.value, 10);
            if (action === 'increase') {
                quantityInput.value = currentValue + 1;
            } else if (action === 'decrease' && currentValue > 1) {
                quantityInput.value = currentValue - 1;
            }
            // 2. Cập nhật ngay lập tức data-quantity của nút cart
            updateCartButtonQuantity();
        });
    });

    // Lắng nghe sự kiện khi người dùng tự nhập số lượng
    if (quantityInput) {
        quantityInput.addEventListener('change', updateCartButtonQuantity);
    }
    

    // =================================================================
    // LOGIC XỬ LÝ BIẾN THỂ (GIỮ NGUYÊN)
    // =================================================================
    if (variantSelectors.length > 0) {
        const variants = @json($product->variants->filter(fn($v) => $v->attributeValues->isNotEmpty())->mapWithKeys(fn($v) => [$v->attributeValues->pluck('id')->sort()->implode('-') => ['id' => $v->id, 'price' => $v->price, 'price_discount' => $v->price_discount]]));
        const priceContainer = document.querySelector('.price-area');
        const originalPriceHTML = priceContainer.innerHTML;
        const variantError = document.getElementById('variant-error');

        variantSelectors.forEach(selector => selector.addEventListener('change', updateProductDetails));

        function updateProductDetails() {
            const selectedOptions = document.querySelectorAll('.variant-selector:checked');
            const totalOptionGroups = document.querySelectorAll('.variant-group').length;

            if (selectedOptions.length < totalOptionGroups) {
                if(addToCartBtn) addToCartBtn.disabled = true;
                if(variantError) variantError.classList.remove('d-none');
                return;
            }

            if(addToCartBtn) addToCartBtn.disabled = false;
            if(variantError) variantError.classList.add('d-none');

            const selectedIds = Array.from(selectedOptions).map(input => input.value).sort().join('-');
            const selectedVariant = variants[selectedIds];
            
            if (selectedVariant) {
                let newPriceHTML = '';
                const hasDiscount = selectedVariant.price_discount && selectedVariant.price > selectedVariant.price_discount;
                newPriceHTML += `<span class="price-new">${Number(hasDiscount ? selectedVariant.price_discount : selectedVariant.price).toLocaleString('vi-VN')}đ</span>`;
                if(hasDiscount) {
                    newPriceHTML += `<del class="price-old">${Number(selectedVariant.price).toLocaleString('vi-VN')}đ</del>`;
                    newPriceHTML += `<span class="discount-badge">-${Math.round(((selectedVariant.price - selectedVariant.price_discount) / selectedVariant.price) * 100)}%</span>`;
                }
                priceContainer.innerHTML = newPriceHTML;

                if(addToCartBtn) {
                    addToCartBtn.dataset.id = selectedVariant.id;
                    addToCartBtn.dataset.price = selectedVariant.price_discount ?? selectedVariant.price;
                }
            } else {
                priceContainer.innerHTML = originalPriceHTML;
                if(addToCartBtn) addToCartBtn.disabled = true;
            }
        }
        
        if(addToCartBtn) addToCartBtn.disabled = true;
    }
});
</script>
@endpush