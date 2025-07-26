@extends('layouts.admin')

@section('title', 'Chỉnh sửa danh mục bài viết')
@section('content_header', 'Chỉnh sửa danh mục bài viết')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Chỉnh sửa danh mục bài viết</h3>
    </div>

    <form action="{{ route('admin.post-categories.update', $postCategory) }}" method="POST" class="form-horizontal" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="card-body">
            <x-form.input type="text" name="name" label="Tên danh mục" :value="old('name', $postCategory->name)" />
            <x-form.input type="text" name="slug" label="Slug (URL thân thiện)" :value="old('slug', $postCategory->slug)" />
            <x-form.select name="parent_id" label="Danh mục cha" :options="$categories" :selected="old('parent_id', $postCategory->parent_id)" placeholder="-- Không có danh mục cha --" />
            <x-form.image-input name="image" label="Ảnh đại diện" :value="$postCategory->image" />
            <x-form.image-input name="banner" label="Banner (tuỳ chọn)" :value="$postCategory->banner" />
            <x-form.switch name="status" label="Trạng thái" :checked="old('status', $postCategory->status)" />
        </div>

        <div class="card-footer">
            <button type="submit" name="action" value="update" class="btn btn-primary">Cập nhật</button>
            <a href="{{ route('admin.post-categories.index') }}" class="btn btn-outline-dark">Quay lại</a>
        </div>
    </form>
</div>
@endsection
