@extends('layouts.admin')
@section('title', 'Thêm bài viết')
@section('content_header', 'Thêm bài viết')

@section('content')
<form action="{{ route('admin.posts.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <div class="card">
        <div class="card-body">
            <x-form.input name="title" label="Tiêu đề" required />
            <x-form.category-select name="post_category_id" label="Danh mục" :options="$categories" required />

            <x-form.image-input name="image" label="Ảnh đại diện" required />
            <x-form.image-input name="banner" label="Banner" />

            <x-form.textarea name="description" label="Mô tả ngắn" />
            <x-form.ckeditor name="content" label="Nội dung" />

            <x-form.switch name="is_featured" label="Nổi bật" :checked="false" />
            <x-form.switch name="status" label="Hiển thị" :checked="true" />
        </div>
        <div class="card-footer">
            <button type="submit" name="save" class="btn btn-primary">Lưu</button>
            <button type="submit" name="save_new" class="btn btn-success">Lưu & Thêm mới</button>
            <a href="{{ route('admin.posts.index') }}" class="btn btn-secondary">Quay lại</a>
        </div>
    </div>
</form>
@endsection
