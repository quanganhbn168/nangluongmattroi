{{-- resources/views/components/form/select.blade.php --}}
@props(['name', 'label', 'options' => [], 'selected' => '', 'required' => false, 'placeholder'])

@php
    $selected = old($name, $selected);
@endphp


<div class="form-group">
    <label for="{{ $name }}">
        {{ $label }} @if($required)<span class="text-danger">*</span>@endif
    </label>
        <select
            name="{{ $name }}"
            id="{{ $name }}"
            {{ $attributes->merge(['class' => 'form-control' . ($errors->has($name) ? ' is-invalid' : '')]) }}
        >
            <option value="0">{{$placeholder}}</option>
            @foreach($options as $key => $text)
                <option value="{{ $key }}" @selected($key == $selected)> {{ $text }} </option>
            @endforeach
        </select>
        @error($name)
            <div class="invalid-feedback d-block">{{ $message }}</div>
        @enderror
</div>