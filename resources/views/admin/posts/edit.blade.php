@extends('layouts.admin')
@section('title', 'Cập nhật bài viết')
@section('content_header', 'Cập nhật bài viết')

@section('content')
<form action="{{ route('admin.posts.update', $post) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    <div class="card">
        <div class="card-body">
            <x-form.input name="title" label="Tiêu đề" :value="$post->title" required />
            <x-form.category-select name="post_category_id" label="Danh mục" :options="$categories" :selected="$post->post_category_id" required />

            <x-form.image-input name="image" label="Ảnh đại diện" :value="$post->image" />
            <x-form.image-input name="banner" label="Banner" :value="$post->banner" />

            <x-form.textarea name="description" label="Mô tả ngắn" :value="$post->description" />
            <x-form.ckeditor name="content" label="Nội dung" :value="$post->content" />

            <x-form.switch name="is_featured" label="Nổi bật" :checked="$post->is_featured" />
            <x-form.switch name="status" label="Hiển thị" :checked="$post->status" />
        </div>
        <div class="card-footer">
            <button type="submit" name="action" value="update" class="btn btn-primary">Cập nhật</button>
            <a href="{{ route('admin.posts.index') }}" class="btn btn-secondary">Quay lại</a>
        </div>
    </div>
</form>
@endsection
