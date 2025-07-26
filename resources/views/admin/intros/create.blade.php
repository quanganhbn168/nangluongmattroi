@extends('layouts.admin')

@section('title', 'Thêm Intro mới')
@section('content_header', 'Thêm Intro mới')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Thêm Intro</h3>
    </div>

    <form action="{{ route('admin.intros.store') }}" method="POST" class="form-horizontal" enctype="multipart/form-data">
        @csrf
        <div class="card-body">
            <x-form.input type="text" name="title" label="Tiêu đề" :value="old('title')" />

            <x-form.ckeditor name="description" label="Mô tả ngắn" :value="old('description')" />

            <x-form.ckeditor name="content" label="Nội dung chi tiết" :value="old('content')" />

            <x-form.image-input name="image" label="Ảnh đại diện" required />

            <x-form.image-input name="banner" label="Banner" />

            <x-form.switch name="status" label="Trạng thái" :checked="old('status', true)" />
        </div>

        <div class="card-footer">
            <button type="submit" name="action" value="save" class="btn btn-primary">Lưu</button>
            <button type="submit" name="action" value="save_new" class="btn btn-secondary">Lưu và thêm mới</button>
            <a href="{{ route('admin.intros.index') }}" class="btn btn-outline-dark">Quay lại</a>
        </div>
    </form>
</div>
@endsection
