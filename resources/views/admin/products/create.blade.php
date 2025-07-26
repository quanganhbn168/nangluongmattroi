{{-- resources/views/admin/products/create.blade.php --}}
@extends('layouts.admin')
@section('title', 'Thêm sản phẩm mới')
@section('content_header', 'Thêm sản phẩm mới')
@section('content')
<div class="card">
    <div class="card-header"><h3 class="card-title">Thêm sản phẩm mới</h3></div>
    <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="card-body">
            <x-form.input name="name" label="Tên sản phẩm" :value="old('name')" />
            <x-form.input name="code" label="Mã sản phẩm" :value="old('code')" />
            <x-form.input name="sm" label="Công xuất" :value="old('sm')" />
            <x-form.input name="ll" label="Lưu lượng khí xả" :value="old('ll')" />
            <x-form.select name="category_id" label="Danh mục phẩm cha" :options="$categories" :selected="old('category_id', 0)" placeholder="-- Danh mục sản phẩm --" />
            <x-form.textarea name="description" label="Mô tả" :value="old('description')" />
            <x-form.image-multi-input
                name="gallery"
                label="Ảnh chi tiết"
            />
            <x-form.ckeditor name="content" label="Nội dung" :value="old('content')" />
            <x-form.image-input name="image" label="Ảnh đại diện" />
            <x-form.image-input name="banner" label="Banner (tuỳ chọn)" />
            <x-form.switch name="status" label="Trạng thái" :checked="old('status', true)" />
            <hr>
            <x-form.textarea name="meta_des" label="Tên sản phẩm" :value="old('meta_des')" />
            <x-form.textarea name="meta_key" label="Tên sản phẩm" :value="old('meta_key')" />
        </div>
        <div class="card-footer">
            <button type="submit" class="btn btn-primary">Lưu</button>
            <a href="{{ route('admin.products.index') }}" class="btn btn-outline-dark">Quay lại</a>
        </div>
    </form>
</div>
@endsection
