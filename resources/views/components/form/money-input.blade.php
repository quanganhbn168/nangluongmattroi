@props([
    'name',
    'label' => '',
    'value' => '',
    'required' => false,
])

@php
    $rawValue = old($name, $value);
@endphp

<div class="form-group">
    <label for="{{ $name }}">
        {{ $label }} @if($required)<span class="text-danger">*</span>@endif
    </label>
        <input
            type="text"
            id="{{ $name }}_formatted"
            class="form-control money-input{{ $errors->has($name) ? ' is-invalid' : '' }}"
            value="{{ $rawValue ? number_format($rawValue, 0, ',', '.') : '' }}"
            placeholder="0 đ"
            autocomplete="off"
        >
        <input
            type="hidden"
            name="{{ $name }}"
            id="{{ $name }}"
            value="{{ $rawValue }}"
        >
        @error($name)
            <div class="invalid-feedback d-block">{{ $message }}</div>
        @enderror
</div>

@once
    @push('js')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            document.querySelectorAll('.money-input').forEach(input => {
                input.addEventListener('input', function () {
                    let value = this.value.replace(/[^\d]/g, '');
                    if (value.length === 0) {
                        this.value = '';
                        document.getElementById(this.id.replace('_formatted', '')).value = '';
                        return;
                    }

                    const formatted = Number(value).toLocaleString('vi-VN');
                    this.value = formatted + ' đ';
                    document.getElementById(this.id.replace('_formatted', '')).value = value;
                });

                // Format lại nếu copy/paste số
                input.addEventListener('blur', function () {
                    let value = this.value.replace(/[^\d]/g, '');
                    if (value) {
                        const formatted = Number(value).toLocaleString('vi-VN');
                        this.value = formatted + ' đ';
                        document.getElementById(this.id.replace('_formatted', '')).value = value;
                    }
                });
            });
        });
    </script>
    @endpush
@endonce
