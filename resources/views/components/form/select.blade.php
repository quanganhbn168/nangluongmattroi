{{-- resources/views/components/form/select.blade.php --}}
@props(['name', 'label', 'options' => [], 'selected' => '', 'required' => false, 'placeholder'])

@php
    $selected = old($name, $selected);
@endphp


<div class="form-group row">
    <label for="{{ $name }}" class="col-sm-2 col-form-label">
        {{ $label }} @if($required)<span class="text-danger">*</span>@endif
    </label>
    <div class="col-sm-10">
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
</div>