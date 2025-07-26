@extends('layouts.admin')
@section('title', 'Thêm cảm nhận học viên')
@section('content_header', 'Thêm cảm nhận học viên')

@section('content')
<form action="{{ route('admin.testimonials.store') }}" method="POST" enctype="multipart/form-data">
  @csrf
  <div class="card">
    <div class="card-body">
      <x-form.input name="name" label="Họ tên" required />
      <x-form.input name="position" label="Vị trí / Ngành" />
      <x-form.image-input name="image" label="Ảnh học viên" />
      <x-form.textarea name="content" label="Nội dung cảm nhận" required />
      <x-form.switch name="status" label="Hiển thị" :checked="true" />
    </div>
    <div class="card-footer">
      <button class="btn btn-primary">Lưu</button>
      <button class="btn btn-success" name="save_new" value="1">Lưu & thêm mới</button>
      <a href="{{ route('admin.testimonials.index') }}" class="btn btn-secondary">Quay lại</a>
    </div>
  </div>
</form>
@endsection
