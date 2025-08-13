@extends('layouts.master')
@section('title', 'Giỏ hàng của bạn')
@section('content')
<div class="container py-5">
    <h2 class="mb-4">Giỏ hàng của bạn</h2>
    <div class="row">
        {{-- Bảng chứa các sản phẩm trong giỏ hàng --}}
        <div class="col-lg-8">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th scope="col" colspan="2">Sản phẩm</th>
                            <th scope="col" class="text-center">Đơn giá</th>
                            <th scope="col" class="text-center">Số lượng</th>
                            <th scope="col" class="text-end">Tạm tính</th>
                            <th scope="col" class="text-center">Xóa</th>
                        </tr>
                    </thead>
                    {{-- JavaScript sẽ render các dòng sản phẩm vào tbody này --}}
                    <tbody id="cart-items-container">
                    </tbody>
                </table>
            </div>
        </div>
        {{-- Cột tóm tắt đơn hàng --}}
        <div class="col-lg-4">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">Tóm tắt đơn hàng</h5>
                    <ul class="list-group list-group-flush mt-3">
                        <li class="list-group-item d-flex justify-content-between align-items-center border-0 px-0 pb-0">
                            Tạm tính
                            <span id="summary-subtotal">0đ</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center border-0 px-0">
                            Phí vận chuyển
                            <span>Miễn phí</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center border-0 px-0 mb-3">
                            <div>
                                <strong>Tổng cộng</strong>
                            </div>
                            <span><strong id="summary-total">0đ</strong></span>
                        </li>
                    </ul>
                    <a href="{{ route('checkout.index') }}" class="btn btn-primary w-100 mt-3">
                        Tiến hành Thanh toán
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
{{-- TEMPLATE CHO MỘT DÒNG SẢN PHẨM TRONG BẢNG (GIAO DIỆN MỚI) --}}
<template id="cart-item-template">
    <tr class="cart-item-row" data-id="__ID__">
        <td style="width: 80px;">
            <img src="__IMAGE__" class="img-fluid rounded" alt="__NAME__">
        </td>
        <td>
            <h6 class="mb-0">__NAME__</h6>
        </td>
        <td class="text-center price-per-item" data-price="__PRICE_RAW__">__PRICE__</td>
        <td style="width: 150px;" class="text-center">
            {{-- Bắt đầu khối Input Group đã nâng cấp --}}
            <div class="input-group input-group-sm mx-auto" style="max-width: 120px;">
                <div class="input-group-prepend">
                    <button class="btn btn-outline-secondary btn-minus" type="button">-</button>
                </div>
                <input type="number" class="form-control text-center quantity-input" value="__QUANTITY__" min="0">
                <div class="input-group-append">
                    <button class="btn btn-outline-secondary btn-plus" type="button">+</button>
                </div>
            </div>
            {{-- Kết thúc khối Input Group --}}
        </td>
        <td class="text-end item-subtotal">__SUBTOTAL__</td>
        <td class="text-center">
            <a href="#!" class="text-danger remove-item-btn"><i class="fas fa-trash"></i></a>
        </td>
    </tr>
</template>
@endsection
@push('js')
<script>
$(document).ready(function() {
    const isGuest = {{ Auth::check() ? 'false' : 'true' }};
    const csrfToken = '{{ csrf_token() }}';
    const cartTbody = $('#cart-items-container');
    const itemTemplate = $('#cart-item-template').html();
    const STORAGE_KEY = 'guest_cart';
    const baseUrl = '{{ url('/') }}';
    const formatCurrency = (number) => new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(number);
    function updateCartSummary() {
        let total = 0;
        $('.cart-item-row').each(function() {
            const price = parseFloat($(this).find('.price-per-item').data('price'));
            const quantity = parseInt($(this).find('.quantity-input').val(), 10);
            if (!isNaN(price) && !isNaN(quantity)) {
                const subtotal = price * quantity;
                $(this).find('.item-subtotal').text(formatCurrency(subtotal));
                total += subtotal;
            }
        });
        $('#summary-subtotal').text(formatCurrency(total));
        $('#summary-total').text(formatCurrency(total));
    }
    function renderCart(items) {
        cartTbody.empty();
        if (!items || items.length === 0) {
            const emptyRow = `<tr><td colspan="6" class="text-center py-4">Giỏ hàng của bạn đang trống.</td></tr>`;
            cartTbody.html(emptyRow);
            updateCartSummary();
            return;
        }
        items.forEach(item => {
            const product = isGuest ? item : item.product;
            const price = parseFloat(product.price_discount || product.price);
            const quantity = item.quantity;
            const itemHtml = itemTemplate
                .replace(/__ID__/g, isGuest ? item.id : item.id) 
                .replace(/__IMAGE__/g, isGuest ? item.image : `${baseUrl}/${product.image}`)
                .replace(/__NAME__/g, product.name)
                .replace(/__PRICE_RAW__/g, price)
                .replace(/__PRICE__/g, formatCurrency(price))
                .replace(/__QUANTITY__/g, quantity)
                .replace(/__SUBTOTAL__/g, formatCurrency(price * quantity));
            cartTbody.append(itemHtml);
        });
        updateCartSummary();
    }
    function handleQuantityChange(input) {
        const cartItemRow = $(input).closest('.cart-item-row');
        const quantity = parseInt($(input).val(), 10);
        const itemId = cartItemRow.data('id');
        if (!isGuest) { 
            $.ajax({
                url: `/cart/update/${itemId}`,
                method: 'POST', 
                data: { 
                    _token: csrfToken, 
                    _method: 'PUT', 
                    quantity: quantity 
                },
                success: function(response) {
                    if (quantity == 0) cartItemRow.remove();
                    updateCartSummary();
                }
            });
        } else { 
            let cart = JSON.parse(localStorage.getItem(STORAGE_KEY)) || [];
            if (quantity == 0) {
                cart = cart.filter(item => item.id != itemId);
                cartItemRow.remove();
            } else {
                const itemInCart = cart.find(item => item.id == itemId);
                if (itemInCart) itemInCart.quantity = quantity;
            }
            localStorage.setItem(STORAGE_KEY, JSON.stringify(cart));
            updateCartSummary();
        }
    }
    function handleRemoveItem(button) {
        const cartItemRow = $(button).closest('.cart-item-row');
        const itemId = cartItemRow.data('id');
        if (!isGuest) { 
            if (!confirm('Bạn chắc chắn muốn xóa sản phẩm này?')) return;
            $.ajax({
                url: `/cart/remove/${itemId}`,
                method: 'POST', 
                data: { 
                    _token: csrfToken,
                    _method: 'DELETE' 
                },
                success: function(response) {
                    cartItemRow.remove();
                    updateCartSummary();
                }
            });
        } else { 
            let cart = JSON.parse(localStorage.getItem(STORAGE_KEY)) || [];
            cart = cart.filter(item => item.id != itemId);
            localStorage.setItem(STORAGE_KEY, JSON.stringify(cart));
            cartItemRow.remove();
            updateCartSummary();
        }
    }
    cartTbody.on('click', '.btn-plus, .btn-minus', function() {
        const input = $(this).closest('.input-group').find('.quantity-input');
        let currentValue = parseInt(input.val(), 10);
        if ($(this).hasClass('btn-plus')) {
            currentValue++;
        } else {
            currentValue = currentValue > 0 ? currentValue - 1 : 0;
        }
        input.val(currentValue).trigger('change');
    });
    cartTbody.on('change', '.quantity-input', function() { handleQuantityChange(this); });
    cartTbody.on('click', '.remove-item-btn', function(e) { e.preventDefault(); handleRemoveItem(this); });
    @auth('web')
        const authCartItems = {!! json_encode($cartItems ?? []) !!};
        renderCart(authCartItems);
    @else
        const guestCartItems = JSON.parse(localStorage.getItem(STORAGE_KEY)) || [];
        renderCart(guestCartItems);
    @endauth
});
</script>
@endpush