<?php

return [
    [
        'title' => 'Dashboard',
        'icon' => 'bi bi-speedometer',
        'route' => 'admin.dashboard',
        'permission' => 'view-dashboard',
    ],
    [
        'title' => 'Quản lý sản phẩm',
        'icon' => 'bi bi-box-seam',
        'permission' => 'manage-products',
        'active_pattern' => 'admin.products.*,admin.categories.*',
        'submenu' => [
            [
                'title' => 'Danh mục sản phẩm',
                'route' => 'admin.categories.index',
                'icon' => 'bi bi-folder2-open',
            ],
            [
                'title' => 'Sản phẩm',
                'route' => 'admin.products.index',
                'icon' => 'bi bi-bag',
            ],
        ],
    ],

    // ================= PHẦN MỚI BỔ SUNG =================
    [
        'title' => 'Quản lý Đơn hàng',
        'icon' => 'bi bi-receipt',
        'permission' => 'manage-orders', // Quyền để xem mục này
        'active_pattern' => 'admin.orders.*',
        'submenu' => [
            [
                'title' => 'Danh sách Đơn hàng',
                'route' => 'admin.orders.index',
                'icon' => 'bi bi-list-ul',
            ],
            [
                'title' => 'Tạo Đơn hàng mới',
                'route' => 'admin.orders.create',
                'icon' => 'bi bi-cart-plus',
            ],
        ],
    ],
    [
        'title' => 'Tra cứu Bảo hành',
        'icon' => 'bi bi-shield-check',
        'route' => 'admin.warranty.index',
        'permission' => 'manage-orders', // Dùng chung quyền với quản lý đơn hàng
        'active_pattern' => 'admin.warranty.*',
    ],
    // =======================================================

    [
        'title' => 'Quản lý dịch vụ',
        'icon' => 'bi bi-journal-bookmark-fill',
        // 'permission' => 'manage-services', // Thêm quyền nếu cần
        'active_pattern' => 'admin.service_categories.*,admin.services.*',
        'submenu' => [
            [
                'title' => 'Danh mục dịch vụ',
                'route' => 'admin.service_categories.index',
                'icon' => 'bi bi-journal',
            ],
            [
                'title' => 'Dịch vụ',
                'route' => 'admin.services.index',
                'icon' => 'bi bi-journal-medical',
            ],
        ],
    ],
    [
        'title' => 'Quản lý bài viết',
        'icon' => 'bi bi-file-text',
        'permission' => 'manage-posts',
        'active_pattern' => 'admin.post-categories.*,admin.posts.*',
        'submenu' => [
            [
                'title' => 'Danh mục bài viết',
                'route' => 'admin.post-categories.index',
                'icon' => 'bi bi-folder2',
            ],
            [
                'title' => 'Bài viết',
                'route' => 'admin.posts.index',
                'icon' => 'bi bi-file-earmark',
            ],
        ],
    ],
    [
        'title' => 'Quản lý slide',
        'icon' => 'bi bi-images',
        'route' => 'admin.slides.index',
        'active_pattern' => 'admin.slides.*',
        'permission' => 'manage-settings',
    ],
    [
        'title' => 'Quản lý giới thiệu',
        'icon' => 'bi bi-images',
        'route' => 'admin.intros.index',
        'active_pattern' => 'admin.intros.*',
        'permission' => 'manage-settings',
    ],
    
    [
        'title' => 'Quản lý người dùng',
        'icon' => 'bi bi-person',
        'permission' => 'manage-users',
        'active_pattern' => 'admin.users.*',
        'submenu' => [
            [
                'title' => 'Danh sách người dùng',
                'route' => 'admin.users.index',
                'icon' => 'bi bi-circle',
            ],
            [
                'title' => 'Thêm người dùng',
                'route' => 'admin.users.create',
                'icon' => 'bi bi-circle',
            ],
        ],
    ],
    [
        'title' => 'Phân quyền',
        'icon' => 'bi bi-shield-lock',
        'permission' => 'manage-roles',
        'active_pattern' => 'admin.roles.*',
        'submenu' => [
            [
                'title' => 'Vai trò & Quyền',
                'route' => 'admin.roles.index',
                'icon' => 'bi bi-person-check-fill',
            ],
        ],
    ],

    
    // ... Các mục menu còn lại giữ nguyên ...
    [
        'title' => 'Cấu hình',
        'icon' => 'bi bi-gear',
        'route' => 'admin.settings.index',
        'active_pattern' => 'admin.settings.*',
        'permission' => 'manage-settings',
    ],
];