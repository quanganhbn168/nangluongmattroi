@php
    $segments = Request::segments();
    $url = '';
@endphp

<ol class="breadcrumb float-sm-right">
    <li class="breadcrumb-item">
        <a href="{{ url('/admin/dashboard') }}">Trang chủ</a>
    </li>

    @foreach($segments as $index => $segment)
        @php
            $url .= '/' . $segment;
            $is_last = $index === array_key_last($segments);
            $label = ucfirst(str_replace('-', ' ', $segment));
        @endphp

        <li class="breadcrumb-item {{ $is_last ? 'active' : '' }}" {{ $is_last ? 'aria-current=page' : '' }}>
            @if (!$is_last)
                <a href="{{ url($url) }}">{{ $label }}</a>
            @else
                {{ $label }}
            @endif
        </li>
    @endforeach
</ol>
