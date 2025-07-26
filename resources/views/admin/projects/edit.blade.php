{{-- resources/views/admin/projects/edit.blade.php --}}
@extends('layouts.admin')
@section('title', 'Chỉnh sửa dự án')
@section('content_header', 'Chỉnh sửa dự án')
@section('content')
<div class="card">
    <div class="card-header"><h3 class="card-title">Chỉnh sửa dự án</h3></div>
    <form action="{{ route('admin.projects.update', $project) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="card-body">
            <x-form.input name="name" label="Tên dự án" :value="old('name', $project->name)" />
            <x-form.select name="parent_id" label="Dự án cha" :options="$projects" :selected="old('parent_id', $project->parent_id)" placeholder="-- Không có dự án cha --" />
            <x-form.input name="description" label="Mô tả" :value="old('description', $project->description)" />
            <x-form.ckeditor name="content" label="Nội dung" :value="old('content', $project->content)" />
            <x-form.image-input name="image" label="Ảnh đại diện" :value="$project->image" />
            <x-form.image-input name="banner" label="Banner (tuỳ chọn)" :value="$project->banner" />
            <x-form.switch name="status" label="Trạng thái" :checked="old('status', $project->status)" />
        </div>
        <div class="card-footer">
            <button type="submit" name="action" value="update" class="btn btn-primary">Cập nhật</button>
            <a href="{{ route('admin.projects.index') }}" class="btn btn-outline-dark">Quay lại</a>
        </div>
    </form>
</div>
@endsection
