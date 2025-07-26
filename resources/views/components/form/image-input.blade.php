@props(['name', 'label', 'value' => '', 'required' => false, 'defaultImage' => 'images/setting/no-image.png'])

@php
    $imageUrl = old($name, $value) ?: $defaultImage;
    $inputId = 'input_' . $name;
    $previewId = 'preview_' . $name;
@endphp

<div class="form-group row">
    <label for="{{ $inputId }}" class="col-sm-2 col-form-label">
        {{ $label }} @if($required)<span class="text-danger">*</span>@endif
    </label>
    <div class="col-sm-10">
        <input
            type="file"
            name="{{ $name }}"
            id="{{ $inputId }}"
            accept="image/*"
            {{ $attributes->merge(['class' => 'form-control' . ($errors->has($name) ? ' is-invalid' : '')]) }}
            onchange="previewImage('{{ $inputId }}', '{{ $previewId }}')"
        >
        @error($name)
            <div class="invalid-feedback d-block">{{ $message }}</div>
        @enderror

        <div class="mt-2">
            <img
                id="{{ $previewId }}"
                src="{{ asset($imageUrl) }}"
                alt="Preview"
                style="max-height: 150px; border: 1px solid #ddd; padding: 4px; background-color: #f8f8f8;"
            >
        </div>
    </div>
</div>

@push('js')
<script>
    function previewImage(inputId, previewId) {
        const input = document.getElementById(inputId);
        const preview = document.getElementById(previewId);

        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function (e) {
                preview.src = e.target.result;
            };
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
@endpush
