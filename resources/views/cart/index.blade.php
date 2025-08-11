@extends('layouts.master')
@section('title', 'Giỏ hàng của bạn')

@section('content')
<div class="container py-5">
    <h2 class="mb-4">Giỏ hàng của bạn</h2>
    <div class="row">
        <div class="col-lg-8" id="cart-items-container">
            {{-- Dành cho người dùng đã đăng nhập --}}
            @auth
                @forelse ($cartItems as $item)
                    <div class="card mb-3 cart-item-row" data-item-id="{{ $item->id }}">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div class="d-flex flex-row align-items-center">
                                    <div>
                                        <img src="{{ asset($item->product->image) }}" class="img-fluid rounded-3" alt="Shopping item" style="width: 65px;">
                                    </div>
                                    <div class="ms-3">
                                        <h5>{{ $item->product->name }}</h5>
                                        <p class="small mb-0">{{ number_format($item->product->price) }}đ</p>
                                    </div>
                                </div>
                                <div class="d-flex flex-row align-items-center">
                                    <div style="width: 100px;">
                                        <input type="number" class="form-control quantity-input" value="{{ $item->quantity }}" min="0">
                                    </div>
                                    <h5 class="ms-4 me-4 item-subtotal">{{ number_format($item->product->price * $item->quantity) }}đ</h5>
                                    <a href="#!" class="text-danger remove-item-btn"><i class="fas fa-trash fa-lg"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <p>Giỏ hàng của bạn đang trống.</p>
                @endforelse
            @endauth

            {{-- Dành cho khách vãng lai, JS sẽ render vào đây --}}
            @guest
                <div id="guest-cart-items">
                    <p>Giỏ hàng của bạn đang trống.</p>
                </div>
            @endguest
        </div>
        <div class="col-lg-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Tóm tắt đơn hàng</h5>
                    <hr>
                    <div class="d-flex justify-content-between">
                        <p class="mb-2">Tạm tính</p>
                        <p class="mb-2" id="summary-subtotal">0đ</p>
                    </div>
                    <div class="d-flex justify-content-between">
                        <p class="mb-2">Phí vận chuyển</p>
                        <p class="mb-2">Miễn phí</p>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between">
                        <p class="mb-2">Tổng cộng</p>
                        <p class="mb-2 fw-bold" id="summary-total">0đ</p>
                    </div>

                    <a href="{{ route('checkout.index') }}" class="btn btn-primary w-100 mt-3">
                        Tiến hành Thanh toán
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('js')
<script>
    $(document).ready(function() {
        // --- HÀM TIỆN ÍCH ---
        const formatCurrency = (number) => new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(number);

        // --- HÀM CẬP NHẬT GIAO DIỆN ---
        function updateCartSummary() {
            let total = 0;
            $('.cart-item-row').each(function() {
                const priceText = $(this).find('.small').text().replace(/[^0-9]/g, '');
                const quantity = $(this).find('.quantity-input').val();
                const price = parseInt(priceText, 10);

                if (!isNaN(price) && !isNaN(quantity)) {
                    const subtotal = price * quantity;
                    $(this).find('.item-subtotal').text(formatCurrency(subtotal));
                    total += subtotal;
                }
            });
            $('#summary-subtotal').text(formatCurrency(total));
            $('#summary-total').text(formatCurrency(total));
        }

        // --- HÀM DÀNH CHO KHÁCH VÃNG LAI ---
        function renderGuestCart() {
            const cart = JSON.parse(localStorage.getItem('cart')) || [];
            const container = $('#guest-cart-items');
            container.empty();

            if (cart.length === 0) {
                container.html('<p>Giỏ hàng của bạn đang trống.</p>');
                return;
            }

            cart.forEach(item => {
                const itemHtml = `
                    <div class="card mb-3 cart-item-row" data-product-id="${item.productId}">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div class="d-flex flex-row align-items-center">
                                    <div><img src="${item.image}" class="img-fluid rounded-3" style="width: 65px;"></div>
                                    <div class="ms-3">
                                        <h5>${item.name}</h5>
                                        <p class="small mb-0">${formatCurrency(item.price)}</p>
                                    </div>
                                </div>
                                <div class="d-flex flex-row align-items-center">
                                    <div style="width: 100px;"><input type="number" class="form-control quantity-input" value="${item.quantity}" min="0"></div>
                                    <h5 class="ms-4 me-4 item-subtotal">${formatCurrency(item.price * item.quantity)}</h5>
                                    <a href="#!" class="text-danger remove-item-btn"><i class="fas fa-trash fa-lg"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
                container.append(itemHtml);
            });
            updateCartSummary();
        }

        // --- XỬ LÝ SỰ KIỆN ---
        // Cập nhật số lượng
        $('#cart-items-container').on('change', '.quantity-input', function() {
            const quantity = $(this).val();
            const cartItemRow = $(this).closest('.cart-item-row');

            if ('{{ Auth::check() }}') {
                // Logic cho người dùng đã đăng nhập
                const itemId = cartItemRow.data('item-id');
                $.ajax({
                    url: `/cart/update/${itemId}`,
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        quantity: quantity,
                    },
                    success: function(response) {
                        if (quantity == 0) {
                            cartItemRow.remove();
                        }
                        updateCartSummary();
                    }
                });
            } else {
                // Logic cho khách vãng lai
                const productId = cartItemRow.data('product-id');
                let cart = JSON.parse(localStorage.getItem('cart')) || [];
                if (quantity == 0) {
                    cart = cart.filter(item => item.productId != productId);
                    cartItemRow.remove();
                } else {
                    const itemInCart = cart.find(item => item.productId == productId);
                    if (itemInCart) {
                        itemInCart.quantity = quantity;
                    }
                }
                localStorage.setItem('cart', JSON.stringify(cart));
                updateCartSummary();
            }
        });

        // Xóa sản phẩm
        $('#cart-items-container').on('click', '.remove-item-btn', function(e) {
            e.preventDefault();
            const cartItemRow = $(this).closest('.cart-item-row');

            if ('{{ Auth::check() }}') {
                const itemId = cartItemRow.data('item-id');
                $.ajax({
                    url: `/cart/remove/${itemId}`,
                    method: 'DELETE',
                    data: { _token: '{{ csrf_token() }}' },
                    success: function(response) {
                        cartItemRow.remove();
                        updateCartSummary();
                    }
                });
            } else {
                const productId = cartItemRow.data('product-id');
                let cart = JSON.parse(localStorage.getItem('cart')) || [];
                cart = cart.filter(item => item.productId != productId);
                localStorage.setItem('cart', JSON.stringify(cart));
                cartItemRow.remove();
                updateCartSummary();
            }
        });

        // --- KHỞI TẠO BAN ĐẦU ---
        @guest
            renderGuestCart();
        @endguest

        @auth
            updateCartSummary();
        @endauth
    });
</script>
@endpush