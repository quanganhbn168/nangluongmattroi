@props([
    'name',
    'label',
    'images' => [],
    'required' => false,
    'defaultImage' => asset('images/setting/no-image.png'),
])

@php
    $inputId = 'input_' . $name;
    $wrapperId = 'wrapper_' . $name;
@endphp

<div class="form-group row">
    <label for="{{ $inputId }}" class="col-sm-2 col-form-label">
        {{ $label }} @if($required)<span class="text-danger">*</span>@endif
    </label>

    <div class="col-sm-10">
        {{-- Input upload ảnh mới --}}
        <input
            type="file"
            name="{{ $name }}[]"
            id="{{ $inputId }}"
            multiple
            accept="image/*"
            {{ $attributes->merge(['class' => 'form-control' . ($errors->has($name) ? ' is-invalid' : '')]) }}
            onchange="previewMultiImage('{{ $inputId }}', '{{ $wrapperId }}')"
        >

        @error($name)
            <div class="invalid-feedback d-block">{{ $message }}</div>
        @enderror

        {{-- Hiển thị ảnh cũ & ảnh mới --}}
        <div id="{{ $wrapperId }}" class="d-flex flex-wrap mt-3 gap-2">
            @foreach($images as $index => $image)
                <div class="preview-image-item position-relative" style="width: 120px; border: 2px dashed #ccc; padding: 5px; border-radius: 6px;">
                    <img src="{{ asset($image) }}" class="img-thumbnail" style="width: 100%; height: 100px; object-fit: cover;">
                    <input type="hidden" name="gallery_old[]" value="{{ $image }}">
                    <button type="button" class="btn btn-sm btn-danger position-absolute" style="top: 3px; right: 3px; padding: 0.15rem 0.4rem;"
                        onclick="this.parentElement.remove()">×</button>
                </div>
            @endforeach
        </div>
    </div>
</div>

@push('js')
<script>
    const fileListMap = {};

    function previewMultiImage(inputId, wrapperId) {
        const input = document.getElementById(inputId);
        const wrapper = document.getElementById(wrapperId);

        if (!fileListMap[inputId]) {
            fileListMap[inputId] = [];
        }

        const dt = new DataTransfer();

        Array.from(input.files).forEach((file) => {
            fileListMap[inputId].push(file);

            const reader = new FileReader();
            reader.onload = function (e) {
                const div = document.createElement('div');
                div.classList.add('preview-image-item', 'position-relative');
                div.style.width = '120px';
                div.style.border = '2px dashed #ccc';
                div.style.padding = '5px';
                div.style.borderRadius = '6px';
                div.style.marginRight = '8px';
                div.style.marginBottom = '8px';

                div.innerHTML = `
                <img src="${e.target.result}" class="img-thumbnail" style="width: 100%; height: 100px; object-fit: cover;">
                <button type="button" class="btn btn-sm btn-danger position-absolute"
                    style="top: 3px; right: 3px; padding: 0.15rem 0.4rem;"
                    onclick="removeNewImage('${inputId}', this)">×</button>
                `;
                wrapper.appendChild(div);
            };
            reader.readAsDataURL(file);
        });

    // Gộp lại toàn bộ file (cũ + mới)
        fileListMap[inputId].forEach(file => dt.items.add(file));
        input.files = dt.files;
    }


    function removeNewImage(inputId, button) {
        const input = document.getElementById(inputId);
        const wrapper = button.closest('.form-group').querySelector(`#wrapper_${inputId.replace('input_', '')}`);

        // Xác định vị trí ảnh trong danh sách
        const index = Array.from(wrapper.children).indexOf(button.parentElement);

        if (index >= 0 && fileListMap[inputId]) {
            fileListMap[inputId].splice(index, 1); // xoá file khỏi list
            const dt = new DataTransfer();

            fileListMap[inputId].forEach(file => dt.items.add(file));
            input.files = dt.files;
        }

        button.parentElement.remove(); // xoá ảnh DOM
    }
</script>
@endpush

