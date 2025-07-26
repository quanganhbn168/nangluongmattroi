@extends('layouts.admin')

@section('title', 'Thêm danh mục bài viết')
@section('content_header', 'Thêm danh mục bài viết')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Thêm danh mục bài viết</h3>
    </div>

    <form action="{{ route('admin.post-categories.store') }}" method="POST" class="form-horizontal" enctype="multipart/form-data">
        @csrf
        <div class="card-body">
            <x-form.input type="text" name="name" label="Tên danh mục" :value="old('name')" />
            <x-form.input type="text" name="slug" label="Slug (URL thân thiện)" :value="old('slug')" placeholder="Tự động tạo nếu để trống" />
            <x-form.select name="parent_id" label="Danh mục cha" :options="$categories" :selected="old('parent_id', 0)" placeholder="-- Không có danh mục cha --" />
            <x-form.image-input name="image" label="Ảnh đại diện" />
            <x-form.image-input name="banner" label="Banner (tuỳ chọn)" />
            <x-form.switch name="status" label="Trạng thái" :checked="old('status', true)" />
        </div>

        <div class="card-footer">
            <button type="submit" name="action" value="save" class="btn btn-primary">Lưu</button>
            <button type="submit" name="action" value="save_new" class="btn btn-secondary">Lưu và thêm mới</button>
            <a href="{{ route('admin.post-categories.index') }}" class="btn btn-outline-dark">Quay lại</a>
        </div>
    </form>
</div>
@endsection
