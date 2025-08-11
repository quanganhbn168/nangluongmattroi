@extends('layouts.master')
@section('title', 'Thanh toán')

@section('content')
<div class="container py-5">
    <form action="{{ route('checkout.place') }}" method="POST" id="checkout-form">
        @csrf
        <div class="row">
            <div class="col-md-7">
                <h4>Thông tin giao hàng</h4>
                <hr>
                <div class="mb-3">
                    <label for="customer_name" class="form-label">Họ và tên</label>
                    <input type="text" class="form-control" id="customer_name" name="customer_name" value="{{ auth()->user()->name ?? '' }}" required>
                </div>
                <div class="mb-3">
                    <label for="customer_phone" class="form-label">Số điện thoại</label>
                    <input type="tel" class="form-control" id="customer_phone" name="customer_phone" value="{{ auth()->user()->phone ?? '' }}" required>
                </div>
                <div class="mb-3">
                    <label for="customer_address" class="form-label">Địa chỉ</label>
                    <input type="text" class="form-control" id="customer_address" name="customer_address" value="{{ auth()->user()->address ?? '' }}" required>
                </div>
                 <div class="mb-3">
                    <label for="note" class="form-label">Ghi chú đơn hàng (tùy chọn)</label>
                    <textarea class="form-control" id="note" name="note" rows="3"></textarea>
                </div>
            </div>
            <div class="col-md-5">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Đơn hàng của bạn</h4>
                        <div id="order-summary">
                            {{-- JS sẽ render tóm tắt đơn hàng của khách vào đây --}}
                        </div>
                        <hr>
                        <h5>Phương thức thanh toán</h5>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="payment_method" id="payment_cod" value="cod" checked>
                            <label class="form-check-label" for="payment_cod">
                                Thanh toán khi nhận hàng (COD)
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="payment_method" id="payment_bank" value="bank_transfer">
                            <label class="form-check-label" for="payment_bank">
                                Chuyển khoản ngân hàng
                            </label>
                        </div>
                        <button type="submit" class="btn btn-primary w-100 mt-3">ĐẶT HÀNG</button>
                    </div>
                </div>
            </div>
        </div>
        <input type="hidden" name="cart_data" id="cart_data_input">
    </form>
</div>
@endsection

@push('js')
<script>
    $(document).ready(function() {
        // Render tóm tắt đơn hàng cho khách
        if (!'{{ Auth::check() }}') {
            const cart = JSON.parse(localStorage.getItem('cart')) || [];
            let summaryHtml = '';
            let total = 0;
            cart.forEach(item => {
                summaryHtml += `<p>${item.name} x ${item.quantity} <span>${(item.price * item.quantity).toLocaleString('vi-VN')}đ</span></p>`;
                total += item.price * item.quantity;
            });
            summaryHtml += `<hr><p class="fw-bold">Tổng cộng: <span>${total.toLocaleString('vi-VN')}đ</span></p>`;
            $('#order-summary').html(summaryHtml);
        }

        // Gửi dữ liệu giỏ hàng của khách đi kèm form
        $('#checkout-form').on('submit', function() {
            if (!'{{ Auth::check() }}') {
                $('#cart_data_input').val(localStorage.getItem('cart'));
            }
        });
    });
</script>
@endpush