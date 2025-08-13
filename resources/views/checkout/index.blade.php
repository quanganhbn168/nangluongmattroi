@extends('layouts.master')
@section('title', 'Thanh toán')

@section('content')
{{-- VÙNG CHỨA THÔNG TIN CẤU HÌNH NGÂN HÀNG TỪ BACKEND --}}
{{-- <div id="bank-config-data" 
    data-bank-id="{{ $bankConfig['bankId'] ?? '' }}"
    data-account-no="{{ $bankConfig['accountNo'] ?? '' }}"
    data-account-name="{{ $bankConfig['accountName'] ?? '' }}"
    data-bank-name="{{ $bankConfig['bankName'] ?? '' }}"
    style="display: none;">
</div> --}}

<div class="container py-5">
    <form action="{{ route('checkout.place') }}" method="POST" id="checkout-form" novalidate>
        @csrf
        <div class="row">
            {{-- CỘT THÔNG TIN GIAO HÀNG --}}
            <div class="col-md-7">
                <h4>Thông tin giao hàng</h4>
                <hr>
                @auth('web')
                    <div class="alert alert-info">
                        Đang đặt hàng với tài khoản: <strong>{{ auth('web')->user()->name }}</strong>
                        (<a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Đăng xuất</a>)
                    </div>
                @endauth

                <div class="mb-3">
                    <label for="customer_name" class="form-label">Họ và tên <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="customer_name" name="customer_name" value="{{ auth('web')->user()->name ?? old('customer_name') }}" required>
                </div>
                <div class="mb-3">
                    <label for="customer_phone" class="form-label">Số điện thoại <span class="text-danger">*</span></label>
                    <input type="tel" class="form-control" id="customer_phone" name="customer_phone" value="{{ auth('web')->user()->phone ?? old('customer_phone') }}" required>
                </div>
                <div class="mb-3">
                    <label for="customer_address" class="form-label">Địa chỉ <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="customer_address" name="customer_address" value="{{ auth('web')->user()->address ?? old('customer_address') }}" required>
                </div>
                 <div class="mb-3">
                    <label for="note" class="form-label">Ghi chú đơn hàng (tùy chọn)</label>
                    <textarea class="form-control" id="note" name="note" rows="3">{{ old('note') }}</textarea>
                </div>
            </div>

            {{-- CỘT ĐƠN HÀNG VÀ THANH TOÁN --}}
            <div class="col-md-5">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h4 class="card-title">Đơn hàng của bạn</h4>
                        <ul class="list-group list-group-flush mt-3" id="order-summary-list">
                            {{-- JS hoặc Blade sẽ render các sản phẩm vào đây --}}
                        </ul>
                        <hr>
                        <ul class="list-group list-group-flush">
                             <li class="list-group-item d-flex justify-content-between align-items-center border-0 px-0 pb-0">
                                Tạm tính
                                <span id="summary-subtotal">0đ</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center border-0 px-0 mb-3">
                                <div><strong>Tổng cộng</strong></div>
                                <span><strong id="summary-total">0đ</strong></span>
                            </li>
                        </ul>
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
                                Chuyển khoản ngân hàng (VietQR)
                            </label>
                        </div>

                        {{-- <div id="bank-info-box" class="mt-3 p-3 border rounded bg-light" style="display: none;">
                            <p class="mb-2 text-center">Quét mã QR để thanh toán</p>
                            <div class="text-center mb-3">
                                <img id="qr-code-image" src="https://placehold.co/250x250?text=Ch%E1%BB%8D" alt="VietQR Code" class="img-fluid">
                                <small id="qr-loading-text" class="d-block mt-2" style="display: none;">Đang tạo mã QR...</small>
                            </div>
                            <hr>
                            <p>Hoặc chuyển khoản thủ công với nội dung bên dưới:</p>
                            <ul class="list-unstyled" id="manual-transfer-info">
                                <li><strong>Ngân hàng:</strong> <span id="bank-name-display"></span></li>
                                <li><strong>Số tài khoản:</strong> <span id="account-no-display"></span></li>
                                <li><strong>Chủ tài khoản:</strong> <span id="account-name-display"></span></li>
                                <li><strong>Nội dung:</strong> <span class="fw-bold" id="transfer-memo"></span></li>
                            </ul>
                        </div> --}}

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
<script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.5/dist/jquery.validate.min.js"></script>
<script>
$(document).ready(function() {
    // --- CÁC BIẾN VÀ CÀI ĐẶT CHUNG ---
    const isGuest = {{ Auth::guard('web')->check() ? 'false' : 'true' }};
    const STORAGE_KEY = 'guest_cart';
    const cartContainer = $('#order-summary-list'); 
    // --- CÁC HÀM TIỆN ÍCH ---
    const formatCurrency = (number) => new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(number);

    // --- HÀM CẬP NHẬT GIAO DIỆN ---
    function updateSummaryTotal() {
        let total = 0;
        cartContainer.find('.item-total').each(function() {
            total += parseFloat($(this).data('total')) || 0;
        });
        $('#summary-subtotal').text(formatCurrency(total));
        $('#summary-total').text(formatCurrency(total));
    }

    function renderAuthSummary() {
        const authCartItems = {!! json_encode($cartItems ?? []) !!};
        cartContainer.empty();
        if (authCartItems.length === 0) {
            cartContainer.html('<li class="list-group-item">Giỏ hàng trống</li>');
            $('#checkout-form button[type="submit"]').prop('disabled', true).addClass('disabled');
            return;
        }
        authCartItems.forEach(item => {
            const subtotal = item.product.price * item.quantity;
            const itemHtml = `<li class="list-group-item d-flex justify-content-between align-items-center px-0"><div>${item.product.name}<small class="d-block text-muted">SL: ${item.quantity}</small></div><span class="item-total" data-total="${subtotal}">${formatCurrency(subtotal)}</span></li>`;
            cartContainer.append(itemHtml);
        });
        updateSummaryTotal();
    }

    function renderGuestSummary() {
        const cart = JSON.parse(localStorage.getItem(STORAGE_KEY)) || [];
        cartContainer.empty(); 
        if (cart.length === 0) {
            cartContainer.html('<li class="list-group-item">Giỏ hàng trống</li>');
            $('#checkout-form button[type="submit"]').prop('disabled', true).addClass('disabled');
            return;
        }
        cart.forEach(item => {
            const subtotal = item.price * item.quantity;
            const itemHtml = `<li class="list-group-item d-flex justify-content-between align-items-center px-0"><div>${item.name}<small class="d-block text-muted">SL: ${item.quantity}</small></div><span class="item-total" data-total="${subtotal}">${formatCurrency(subtotal)}</span></li>`;
            cartContainer.append(itemHtml);
        });
        updateSummaryTotal();
    }

    // --- KHỞI TẠO BAN ĐẦU ---
    if (isGuest) {
        renderGuestSummary();
    } else {
        renderAuthSummary();
    }

    // --- LOGIC MÃ QR (ĐÃ ĐƠN GIẢN HÓA) ---
    const bankConfigEl = document.getElementById('bank-config-data');
    const bankInfo = {
        bankId: bankConfigEl.dataset.bankId,
        accountNo: bankConfigEl.dataset.accountNo,
        accountName: bankConfigEl.dataset.accountName,
        bankName: bankConfigEl.dataset.bankName,
    };

    $('input[name="payment_method"]').on('change', function() {
        if (this.value === 'bank_transfer') {
            $('#bank-info-box').slideDown();
            generateQrCode();
        } else {
            $('#bank-info-box').slideUp();
        }
    });

    function generateQrCode() {
        const totalText = $('#summary-total').text();
        const amount = parseInt(totalText.replace(/[^0-9]/g, ''), 10);
        const memo = "TT don hang " + Math.floor(Date.now() / 1000);

        if (amount > 0 && bankInfo.accountNo) {
            // Cập nhật thông tin chuyển khoản tay
            $('#bank-name-display').text(bankInfo.bankName);
            $('#account-no-display').text(bankInfo.accountNo);
            $('#account-name-display').text(bankInfo.accountName);
            $('#transfer-memo').text(memo);

            // Tự xây dựng URL trực tiếp, không cần gọi API trung gian
            const params = $.param({
                amount: amount,
                addInfo: memo,
                accountName: bankInfo.accountName
            });
            const qrUrl = `https://img.vietqr.io/image/${bankInfo.bankId}-${bankInfo.accountNo}-compact.png?${params}`;

            // Hiển thị ảnh
            $('#qr-code-image').attr('src', qrUrl);
        }
    }

    // --- VALIDATION FORM ---
    $.validator.addMethod("phoneVN", (value, element) => /^(0[3|5|7|8|9])[0-9]{8}$/.test(value), "Số điện thoại không hợp lệ.");
    $('#checkout-form').validate({
        rules: {
            customer_name: { required: true, minlength: 2 },
            customer_phone: { required: true, phoneVN: true },
            customer_address: { required: true, minlength: 10 }
        },
        messages: {
            customer_name: { required: "Vui lòng nhập họ tên.", minlength: "Họ tên quá ngắn." },
            customer_phone: { required: "Vui lòng nhập số điện thoại hợp lệ." },
            customer_address: { required: "Vui lòng nhập địa chỉ.", minlength: "Địa chỉ quá ngắn." }
        },
        errorElement: 'small',
        errorClass: 'text-danger',
        highlight: (element) => $(element).addClass('is-invalid'),
        unhighlight: (element) => $(element).removeClass('is-invalid'),
        submitHandler: function(form) {
            if (isGuest) {
                $('#cart_data_input').val(localStorage.getItem(STORAGE_KEY));
            }
            form.submit();
        }
    });
});
</script>
@endpush