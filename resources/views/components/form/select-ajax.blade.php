@props([
    'name',
    'label' => '',
    'selected' => [],
    'url',
    'placeholder' => 'Nhập để tìm hoặc thêm mới',
    'multiple' => false,
    'required' => false,
])

<div class="form-group">
    @if($label)
        <label for="{{ $name }}">{{ $label }}</label>
    @endif

    <select 
        name="{{ $name }}{{ $multiple ? '[]' : '' }}" 
        id="select-{{ $name }}" 
        class="form-control select2-ajax"
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

@once
    @push('css')
        <link rel="stylesheet" href="{{ asset('vendor/adminlte/plugins/select2/css/select2.min.css') }}">
        <link rel="stylesheet" href="{{ asset('vendor/adminlte/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
        <style>
            .select2-container--bootstrap4 .select2-selection--single,
            .select2-container--bootstrap4 .select2-selection--multiple {
                border: 1px solid #ced4da;
                border-radius: 0.25rem;
                height: calc(2.25rem + 2px);
            }
        </style>
    @endpush

    @push('js')
        <script src="{{ asset('vendor/adminlte/plugins/select2/js/select2.full.min.js') }}"></script>
        <script>
            document.addEventListener("DOMContentLoaded", function () {
                $('.select2-ajax').each(function () {
                    let el = $(this);
                    el.select2({
                        theme: 'bootstrap4',
                        tags: true,
                        placeholder: el.data('placeholder'),
                        allowClear: true,
                        ajax: {
                            url: el.data('url'),
                            dataType: 'json',
                            delay: 250,
                            data: params => ({ q: params.term }),
                            processResults: data => ({
                                results: data.map(item => ({ id: item.id, text: item.name }))
                            }),
                            cache: true
                        },
                        language: {
                            inputTooShort: () => 'Nhập để tìm...',
                            noResults: () => 'Không có dữ liệu',
                            searching: () => 'Đang tìm...',
                        }
                    });
                });
            });
        </script>
    @endpush
@endonce
