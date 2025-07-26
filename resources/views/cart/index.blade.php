@extends('layouts.master')
@section('title', 'Giỏ hàng')

@section('content')
<div class="cart">
    <h2>Giỏ hàng</h2>
    @forelse($cartItems as $productId => $item)
        <div class="cart-item" data-id="{{ $productId }}">
            <img src="{{ asset($item['image']) }}" width="50">
            <span>{{ $item['name'] }}</span>
            <input type="number" class="quantity" value="{{ $item['quantity'] }}">
            <button class="remove-btn">Xoá</button>
        </div>
    @empty
        <p>Chưa có sản phẩm nào trong giỏ.</p>
    @endforelse

    <div class="total">Tổng tiền: {{ number_format($total, 0, ',', '.') }}₫</div>
</div>
@endsection
