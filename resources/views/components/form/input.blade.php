{{-- resources/views/components/form/input.blade.php --}}
@props(['name', 'label', 'value' => '', 'required' => false, 'type' => 'text'])

@php $inputValue = old($name, $value); @endphp

<div class="form-group row">
    <label for="{{ $name }}" class="col-sm-2 col-form-label">
        {{ $label }} @if($required)<span class="text-danger">*</span>@endif
    </label>
    <div class="col-sm-10">
        <input
            type="{{ $type }}"
            name="{{ $name }}"
            id="{{ $name }}"
            value="{{ $inputValue }}"
            {{ $attributes->merge(['class' => 'form-control' . ($errors->has($name) ? ' is-invalid' : '')]) }}
        >
        @error($name)
            <div class="invalid-feedback d-block">{{ $message }}</div>
        @enderror
    </div>
</div>