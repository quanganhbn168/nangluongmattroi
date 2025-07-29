@extends('layouts.admin')

@section('title', 'Thêm thương hiệu')
@section('content_header', 'Thêm thương hiệu')

@section('content')
<form action="{{ route('admin.brands.store') }}" method="POST">
    @csrf
    <div class="card">
        <div class="card-body">
            <x-form.input name="name" label="Tên thương hiệu" required />
            <x-form.input name="slug" label="Slug (nếu không nhập sẽ tự tạo)" />
            <x-form.image-input name="image" label="Logo" />
            <x-form.switch name="status" label="Hiển thị" :checked="true" />
        </div>
    </div>
</form>
@endsection
