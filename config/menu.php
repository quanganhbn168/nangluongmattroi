<?php

return [
    [
        'title' => 'Dashboard',
        'icon' => 'bi bi-speedometer',
        'route' => 'admin.dashboard',
    ],
    [
        'title' => 'Quản lý sản phẩm',
        'icon' => 'bi bi-box-seam',
        'submenu' => [
            [
                'title' => 'Danh mục sản phẩm',
                'route' => 'admin.categories.index',
                'active_pattern' => 'admin.categories.*',
                'icon' => 'bi bi-folder2-open',
            ],
            [
                'title' => 'Sản phẩm',
                'route' => 'admin.products.index',
                'active_pattern' => 'admin.products.*',
                'icon' => 'bi bi-bag',
            ],
        ],
    ],
    [
        'title' => 'Quản lý dịch vụ',
        'icon' => 'bi bi-journal-bookmark-fill',
        'submenu' => [
            [
                'title' => 'Danh mục dịch vụ',
                'route' => 'admin.service_categories.index',
                'active_pattern' => 'admin.service_categories.*',
                'icon' => 'bi bi-journal',
            ],
            [
                'title' => 'Dịch vụ',
                'route' => 'admin.services.index',
                'active_pattern' => 'admin.services.*',
                'icon' => 'bi bi-journal-medical',
            ],
        ],
    ],
    [
        'title' => 'Quản lý bài viết',
        'icon' => 'bi bi-file-text',
        'submenu' => [
            [
                'title' => 'Danh mục bài viết',
                'route' => 'admin.post-categories.index',
                'active_pattern' => 'admin.post-categories.*',
                'icon' => 'bi bi-folder2',
            ],
            [
                'title' => 'Bài viết',
                'route' => 'admin.posts.index',
                'active_pattern' => 'admin.posts.*',
                'icon' => 'bi bi-file-earmark',
            ],
        ],
    ],
    [
        'title' => 'Slide',
        'icon' => 'bi bi-images',
        'route' => 'admin.slides.index',
        'active_pattern' => 'admin.slides.*',
    ],
    [
        'title' => 'Quản lý chi nhánh',
        'icon' => 'bi bi-images',
        'route' => 'admin.branches.index',
        'active_pattern' => 'admin.branches.*',
    ],
    [
        'title' => 'Quản lý giới thiệu',
        'icon' => 'bi bi-file-earmark-person',
        'route' => 'admin.intros.index',
        'active_pattern' => 'admin.intros.*',
    ],
    [
        'title' => 'Liên hệ',
        'icon' => 'bi bi-mailbox-flag',
        'route' => 'admin.contacts.index',
        'active_pattern' => 'admin.contacts.*',
    ],
    [
        'title' => 'Cấu hình',
        'icon' => 'bi bi-gear',
        'route' => 'admin.settings.index',
        'active_pattern' => 'admin.settings.*',
    ],
];
