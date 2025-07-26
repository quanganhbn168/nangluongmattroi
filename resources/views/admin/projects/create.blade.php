{{-- resources/views/admin/projects/create.blade.php --}}
@extends('layouts.admin')
@section('title', 'Thêm dự án mới')
@section('content_header', 'Thêm dự án mới')
@section('content')
<div class="card">
    <div class="card-header"><h3 class="card-title">Thêm dự án mới</h3></div>
    <form action="{{ route('admin.projects.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="card-body">
            <x-form.input name="name" label="Tên dự án" :value="old('name')" />
            <x-form.select name="parent_id" label="Dự án cha" :options="$projects" :selected="old('parent_id', 0)" placeholder="-- Không có dự án cha --" />
            <x-form.input name="description" label="Mô tả" :value="old('description')" />
            <x-form.ckeditor name="content" label="Nội dung" :value="old('content')" />
            <x-form.image-input name="image" label="Ảnh đại diện" />
            <x-form.image-input name="banner" label="Banner (tuỳ chọn)" />
            <x-form.switch name="status" label="Trạng thái" :checked="old('status', true)" />
        </div>
        <div class="card-footer">
            <button type="submit" class="btn btn-primary">Lưu</button>
            <a href="{{ route('admin.projects.index') }}" class="btn btn-outline-dark">Quay lại</a>
        </div>
    </form>
</div>
@endsection
