@props([
    'name',
    'label' => '',
    'selected' => [],
    'url', // route trả về JSON
    'placeholder' => 'Nhập để tìm hoặc thêm mới',
    'multiple' => true,
    'required' => false,
])

<div class="form-group">
    @if($label)
        <label for="{{ $name }}">{{ $label }}</label>
    @endif

    <select 
        name="{{ $name }}{{ $multiple ? '[]' : '' }}" 
        id="select-{{ $name }}" 
        class="form-control tag-select"
        data-url="{{ $url }}"
        data-placeholder="{{ $placeholder }}"
        {{ $multiple ? 'multiple' : '' }}
        {{ $required ? 'required' : '' }}
    >
        @foreach ($selected as $item)
            <option value="{{ $item['id'] }}" selected>{{ $item['text'] }}</option>
        @endforeach
    </select>
</div>

@push('css')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
@endpush

@push('js')
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const el = $('#select-{{ $name }}');
            el.select2({
                tags: true,
                placeholder: el.data('placeholder'),
                allowClear: true,
                ajax: {
                    url: el.data('url'),
                    dataType: 'json',
                    delay: 250,
                    data: function (params) {
                        return { q: params.term };
                    },
                    processResults: function (data) {
                        return {
                            results: data.map(item => ({
                                id: item.id,
                                text: item.name
                            }))
                        };
                    },
                    cache: true
                },
                language: {
                    inputTooShort: () => 'Nhập để tìm...',
                    noResults: () => 'Không có dữ liệu',
                    searching: () => 'Đang tìm...',
                }
            });
        });
    </script>
@endpush
