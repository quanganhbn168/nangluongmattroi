@props([
    'name',
    'label' => '',
    'options' => [],
    'selected' => '',
    'required' => false,
    'placeholder' => '-- Chọn danh mục --',
])

@php
    $selected = old($name, $selected);
    $grouped = collect($options)->groupBy('parent_id');
    $treeOptions = buildTreeOptions($grouped[0] ?? [], $grouped, $selected);
@endphp

<x-form.select
    :name="$name"
    :label="$label"
    :options="$treeOptions"
    :selected="$selected"
    :required="$required"
    :placeholder="$placeholder"
/>
