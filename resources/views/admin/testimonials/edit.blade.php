@extends('layouts.admin')
@section('title', 'Sửa cảm nhận học viên')
@section('content_header', 'Sửa cảm nhận học viên')

@section('content')
<form action="{{ route('admin.testimonials.update', $testimonial) }}" method="POST" enctype="multipart/form-data">
  @csrf @method('PUT')
  <div class="card">
    <div class="card-body">
      <x-form.input name="name" label="Họ tên" :value="$testimonial->name" required />
      <x-form.input name="position" label="Vị trí / Ngành" :value="$testimonial->position" />
      <x-form.image-input name="image" label="Ảnh học viên" :value="$testimonial->image" />
      <x-form.textarea name="content" label="Nội dung cảm nhận" :value="$testimonial->content" required />
      <x-form.switch name="status" label="Hiển thị" :checked="$testimonial->status" />
    </div>
    <div class="card-footer">
      <button class="btn btn-primary">Cập nhật</button>
      <a href="{{ route('admin.testimonials.index') }}" class="btn btn-secondary">Quay lại</a>
    </div>
  </div>
</form>
@endsection
