$(document).ready(function () {
    const csrf = $('meta[name="csrf-token"]').attr('content');

    // Thêm sản phẩm vào giỏ
    $(document).on('click', '.btn-add-to-cart', function (e) {
        e.preventDefault();
        const productId = $(this).data('id');
        const quantity = $(this).data('quantity') || 1;

        $.post('/cart/add', {
            product_id: productId,
            quantity: quantity,
            _token: csrf
        }).done(function (res) {
            alert(res.message);
            // Cập nhật UI nếu cần
        });
    });

    // Cập nhật số lượng sản phẩm trong giỏ
    $(document).on('change', '.cart-item .quantity', function () {
        const productId = $(this).closest('.cart-item').data('id');
        const quantity = $(this).val();

        $.post('/cart/update', {
            product_id: productId,
            quantity: quantity,
            _token: csrf
        }).done(function (res) {
            alert(res.message);
            // Cập nhật tổng tiền nếu cần
        });
    });

    // Xoá sản phẩm khỏi giỏ
    $(document).on('click', '.cart-item .remove-btn', function () {
        const productId = $(this).closest('.cart-item').data('id');

        $.post('/cart/remove', {
            product_id: productId,
            _token: csrf
        }).done(function (res) {
            alert(res.message);
            // Có thể reload hoặc xoá node khỏi DOM
            location.reload();
        });
    });
});
