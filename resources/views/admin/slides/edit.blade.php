@extends('layouts.admin')
@section('title', 'Chỉnh sửa slide')
@section('content_header', 'Chỉnh sửa slide')
@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Chỉnh sửa slide</h3>
    </div>
    <form action="{{ route('admin.slides.update', $slide) }}" method="POST" class="form-horizontal" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="card-body">
            <x-form.input type="text" name="title" label="Tiêu đề slide" :value="old('title', $slide->title)" />
            <x-form.input type="text" name="link" label="Link" :value="old('link', $slide->link)" />
            <x-form.input type="number" name="position" label="Thứ tự hiển thị" :value="old('position', $slide->position)" />
            <x-form.select 
                name="type" 
                label="Loại slide" 
                :options="\App\Models\Slide::getTypeOptions()" 
                :selected="old('type', $slide->type)" 
                placeholder="-- Chọn loại slide --"
                required
            />
            <x-form.switch name="status" label="Trạng thái" :checked="old('status', $slide->status)" />
            {{-- Phân biệt ảnh thường vs before/after --}}
            @php
                $type = old('type', $slide->type);
                $image = is_array($slide->image) ? $slide->image : json_decode($slide->image, true);
            @endphp
            <div id="image-group">
                <x-form.image-input
                    name="image"
                    label="Ảnh slide"
                    :value="is_string($slide->image) ? $slide->image : ''"
                />
            </div>
        </div>
        <div class="card-footer">
            <button type="submit" class="btn btn-primary">Cập nhật</button>
            <a href="{{ route('admin.slides.index') }}" class="btn btn-outline-dark">Quay lại</a>
        </div>
    </form>
</div>
@endsection
@push('js')
@endpush
