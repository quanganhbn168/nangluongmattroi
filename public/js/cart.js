/**
 * =================================================================================
 * FILE QUẢN LÝ GIỎ HÀNG TOÀN CỤC (DUY NHẤT) - PHIÊN BẢN NÂNG CẤP
 * - Cập nhật giỏ hàng AJAX cho cả Guest và Auth user.
 * - Tự động gộp giỏ hàng Guest vào tài khoản khi đăng nhập.
 * =================================================================================
 */
document.addEventListener('DOMContentLoaded', function() {
    // --- CÁC BIẾN VÀ CÀI ĐẶT CHUNG ---
    // Sửa lại cách lấy isGuest để linh hoạt hơn
    const isGuest = !document.body.classList.contains('logged-in'); 
    const csrf = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    const STORAGE_KEY = 'guest_cart';
    // --- CÁC DOM ELEMENTS ---
    const offcanvasBody = document.querySelector('.cart-offcanvas-wrapper .offcanvas-body');
    const offcanvasFooter = document.querySelector('.cart-offcanvas-wrapper .offcanvas-footer');
    const cartCountSpan = document.querySelector('.cart-action .cart-count');
    const cartTotalSpan = document.querySelector('.cart-offcanvas-wrapper .total-price');
    const itemTemplate = document.getElementById('guest-cart-item-template'); // Dùng chung template
    // =============================================================================
    // HÀM RENDER UI GIỎ HÀNG (DÙNG CHUNG CHO CẢ GUEST VÀ AUTH)
    // =============================================================================
    // =============================================================================
    // HÀM RENDER UI GIỎ HÀNG (DÙNG CHUNG CHO CẢ GUEST VÀ AUTH)
    // =============================================================================
    const renderOffCanvasCart = (cartData) => {
        if (!offcanvasBody || !itemTemplate) return;
        offcanvasBody.innerHTML = '';
        const { items = [], total_quantity = 0, total_price = 0 } = cartData;
        if (items.length === 0) {
            offcanvasBody.innerHTML = '<p class="text-center p-4">Giỏ hàng của bạn đang trống.</p>';
            if(offcanvasFooter) offcanvasFooter.style.display = 'none';
        } else {
            items.forEach(item => {
                        const product = item.product || item;
                        // Đã bỏ phần tạo onclickAction
                        const itemHtml = itemTemplate.innerHTML
                            .replace(/__ID__/g, isGuest ? product.id : item.id) // QUAN TRỌNG: Gán đúng ID cho data-item-id
                            .replace(/__NAME__/g, product.name)
                            .replace(/__PRICE__/g, Number(product.price_discount || product.price).toLocaleString('vi-VN'))
                            .replace(/__QUANTITY__/g, item.quantity)
                            .replace(/__IMAGE__/g, product.image)
                            .replace(/__URL__/g, `/san-pham/${product.slug}`);
                        offcanvasBody.innerHTML += itemHtml;
                    });
            if(offcanvasFooter) offcanvasFooter.style.display = 'block';
        }
        if (cartCountSpan) cartCountSpan.innerText = total_quantity;
        if (cartTotalSpan) cartTotalSpan.innerText = Number(total_price).toLocaleString('vi-VN') + 'đ';
    };
    // =============================================================================
    // LOGIC CHO KHÁCH (GUEST)
    // =============================================================================
    const getGuestCart = () => JSON.parse(localStorage.getItem(STORAGE_KEY)) || [];
    const saveGuestCart = (cart) => {
        localStorage.setItem(STORAGE_KEY, JSON.stringify(cart));
        updateAndRenderGuestCart();
    };
    // Hàm tính toán và render cho guest
    const updateAndRenderGuestCart = () => {
        const cart = getGuestCart();
        const total_quantity = cart.reduce((sum, item) => sum + item.quantity, 0);
        const total_price = cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);
        renderOffCanvasCart({ items: cart, total_quantity, total_price });
    };
    window.addGuestCartItem = (product) => {
        let cart = getGuestCart();
        let existingItem = cart.find(item => item.id == product.id);
        if (existingItem) {
            existingItem.quantity += product.quantity;
        } else {
            cart.push(product);
        }
        saveGuestCart(cart);
        Swal.fire({ icon: 'success', title: 'Thành công!', text: 'Sản phẩm đã được thêm vào giỏ.' });
        document.body.classList.add('show-cart-offcanvas');
    };
    window.removeGuestCartItemFromOffCanvas = (productId) => {
        let cart = getGuestCart();
        cart = cart.filter(item => item.id != productId);
        saveGuestCart(cart);
    };
    // =============================================================================
    // LOGIC CHO NGƯỜI DÙNG ĐĂNG NHẬP (AUTH)
    // =============================================================================
    const addToCartAPI = (productId, quantity) => {
        $.post('/cart/add', {
            _token: csrf,
            product_id: productId,
            quantity: quantity
        })
        .done(function(res) {
            if(res.success) {
                Swal.fire({ icon: 'success', title: 'Thành công!', text: 'Sản phẩm đã được thêm vào giỏ.' });
                // **QUAN TRỌNG: Render lại giỏ hàng với dữ liệu từ server, KHÔNG reload trang**
                renderOffCanvasCart(res.cart); 
                document.body.classList.add('show-cart-offcanvas');
            }
        })
        .fail(function() {
            Swal.fire({ icon: 'error', title: 'Lỗi!', text: 'Đã xảy ra lỗi, vui lòng thử lại.' });
        });
    };
    window.removeAuthCartItem = (cartItemId) => {
    // Dùng $.post để gửi request DELETE, vì form HTML không hỗ trợ DELETE trực tiếp
        $.post(`/cart/remove/${cartItemId}`, {
            _token: csrf,
        _method: 'DELETE' // Giả lập method DELETE
    })
        .done(function(res) {
            if (res.success) {
                console.log(res.message);
            // Quan trọng: Vẽ lại giỏ hàng với dữ liệu mới từ server
                renderOffCanvasCart(res.cart);
            }
        })
        .fail(function() {
            Swal.fire({ icon: 'error', title: 'Lỗi!', text: 'Không thể xóa sản phẩm, vui lòng thử lại.' });
        });
    };
    // =============================================================================
    // LOGIC GỘP GIỎ HÀNG (MERGE CART)
    // =============================================================================
    const mergeCartOnLogin = () => {
        const guestCart = getGuestCart();
        if (!isGuest && guestCart.length > 0) {
            console.log('Phát hiện giỏ hàng của khách, tiến hành gộp...');
            $.post('/cart/merge', {
                _token: csrf,
                guest_cart: guestCart
            })
            .done(function(res) {
                if(res.success) {
                    console.log('Gộp giỏ hàng thành công!');
                    localStorage.removeItem(STORAGE_KEY); // Xóa giỏ hàng của khách
                    location.reload(); // Tải lại trang để hiển thị giỏ hàng đã gộp
                }
            })
            .fail(function() {
                console.error('Lỗi khi gộp giỏ hàng.');
            });
        }
    };
    // =============================================================================
    // GẮN SỰ KIỆN VÀ KHỞI TẠO
    // =============================================================================
    $(document).on('click', '.btn-add-to-cart', function(e) {
        e.preventDefault();
        const button = $(this);
        const quantity = parseInt(button.data('quantity'), 10) || 1;
        const productId = button.data('id');
        if (isGuest) {
            const product = {
                id:       productId,
                name:     button.data('name'),
                price:    parseFloat(button.data('price')),
                image:    button.data('image'),
                slug:     button.data('slug'),
                quantity: quantity
            };
            addGuestCartItem(product);
        } else {
            addToCartAPI(productId, quantity);
        }
    });
    $(document).on('click', '.cart-offcanvas-wrapper .item-remove', function(e) {
        e.preventDefault();

        const itemId = $(this).data('item-id');
        if (!itemId) return;

        if (isGuest) {
        // Gọi hàm xóa của khách
            let cart = getGuestCart();
            cart = cart.filter(item => item.id != itemId);
            saveGuestCart(cart);
        } else {
        // Gọi hàm xóa của người dùng đã đăng nhập
            removeAuthCartItem(itemId);
        }
    });
    // --- KHỞI TẠO BAN ĐẦU ---
    if (isGuest) {
        updateAndRenderGuestCart(); // Render cho khách
    }
    // Chạy hàm gộp giỏ hàng ngay khi trang tải xong
    mergeCartOnLogin();
});