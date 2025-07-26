{{-- resources/views/admin/product/edit.blade.php --}}
@extends('layouts.admin')
@section('title', 'Chỉnh sửa sản phẩm')
@section('content_header', 'Chỉnh sửa sản phẩm')
@section('content')
<div class="card">
    <div class="card-header"><h3 class="card-title">Chỉnh sửa sản phẩm</h3></div>
    <form action="{{ route('admin.products.update', $product) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="card-body">
            <x-form.input name="name" label="Tên sản phẩm" :value="old('name', $product->name)" />
            <x-form.input name="code" label="Mã sản phẩm" :value="old('code', $product->code)" />
            <x-form.input name="sm" label="Công xuất" :value="old('sm', $product->sm)" />
            <x-form.input name="ll" label="Lượng khí xả" :value="old('ll', $product->ll)" />
            <x-form.select name="category_id" label="Danh mục cha" :options="$categories" :selected="old('category_id', $product->category_id)" placeholder="-- Không có danh mục gốc --" />
            <x-form.image-multi-input
                name="gallery"
                label="Ảnh chi tiết"
                :images="$images"
            />
            <x-form.textarea name="description" label="Mô tả" :value="old('description', $product->description)" />
            <x-form.ckeditor name="content" label="Nội dung" :value="old('content', $product->content)" />
            <x-form.ckeditor name="specifications" label="Thông số kỹ thuật" :value="old('specifications', $product->specifications)" />
            
            <x-form.image-input name="image" label="Ảnh đại diện" :value="$product->image" />
            <x-form.image-input name="banner" label="Banner (tuỳ chọn)" :value="$product->banner" />
            <x-form.switch name="status" label="Trạng thái" :checked="old('status', $product->status)" />
        </div>
        <div class="card-footer">
            <button type="submit" name="action" value="update" class="btn btn-primary">Cập nhật</button>
            <a href="{{ route('admin.products.index') }}" class="btn btn-outline-dark">Quay lại</a>
        </div>
    </form>
</div>
@endsection
