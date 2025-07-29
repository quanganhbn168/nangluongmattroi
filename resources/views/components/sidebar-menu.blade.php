{{-- resources/views/components/sidebar-menu.blade.php (Đã sửa lỗi cú pháp) --}}

<ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
    @foreach ($menu as $item)
        @php
            $hasSub = !empty($item['submenu']);
            $isMenuActive = $hasSub ? $component->isOpen($item) : $component->isActive($item);
            $url = $hasSub ? '#' : (isset($item['route']) ? route($item['route'], $item['params'] ?? []) : '#');
        @endphp

        <li class="nav-item {{ $hasSub && $isMenuActive ? 'menu-is-opening menu-open' : '' }}">
            <a href="{{ $url }}" class="nav-link {{ $isMenuActive ? 'active' : '' }}">
                <i class="nav-icon {{ $item['icon'] }}"></i>
                <p>
                    {{ $item['title'] }}
                    @if ($hasSub)
                        <i class="right fas fa-angle-left"></i>
                    @endif
                </p>
            </a>

            @if ($hasSub)
                <ul class="nav nav-treeview">
                    @foreach ($item['submenu'] as $sub)
                        <li class="nav-item">
                            <a href="{{ isset($sub['route']) ? route($sub['route'], $sub['params'] ?? []) : '#' }}"
                               {{-- SỬA LẠI: Gọi phương thức qua biến $component --}}
                               class="nav-link {{ $component->isActive($sub) ? 'active' : '' }}">
                                <i class="{{ $sub['icon'] ?? 'far fa-circle' }} nav-icon"></i>
                                <p>{{ $sub['title'] }}</p>
                            </a>
                        </li>
                    @endforeach
                </ul>
            @endif
        </li>
    @endforeach
</ul>
