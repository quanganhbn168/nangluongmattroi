@extends('layouts.admin')



@section('title', 'Thêm slide mới')

@section('content_header', 'Thêm slide mới')



@section('content')

<div class="card">

    <div class="card-header">

        <h3 class="card-title">Thêm slide mới</h3>

    </div>



    <form action="{{ route('admin.slides.store') }}" method="POST" class="form-horizontal" enctype="multipart/form-data">

        @csrf

        <div class="card-body">



            {{-- Tiêu đề --}}

            <x-form.input type="text" name="title" label="Tiêu đề slide" :value="old('title')" />



            {{-- Link --}}

            <x-form.input type="text" name="link" label="Link" :value="old('link')" />



            {{-- Thứ tự --}}

            <x-form.input type="number" name="position" label="Thứ tự hiển thị" :value="old('position', 0)" />



            {{-- Type --}}

            <x-form.select 

                name="type" 

                label="Loại slide" 

                :options="\App\Models\Slide::getTypeOptions()" 

                :selected="old('type')" 

                placeholder="-- Chọn loại slide --"

                required

            />





            {{-- Trạng thái --}}

            <x-form.switch name="status" label="Trạng thái" :checked="old('status', true)" />



            {{-- Ảnh thường --}}

            <div id="image-group">

                <x-form.image-input

                    name="image"

                    label="Ảnh slide"

                />

            </div>



            {{-- Ảnh before/after --}}

            <div id="before-after-group" style="display: none;">

                <x-form.image-input

                    name="before_image"

                    label="Ảnh Trước"

                />

                <x-form.image-input

                    name="after_image"

                    label="Ảnh Sau"

                />

            </div>

        </div>



        <div class="card-footer">

            <button type="submit" name="action" value="save" class="btn btn-primary">Lưu</button>

            <button type="submit" name="action" value="save_new" class="btn btn-secondary">Lưu và thêm mới</button>

            <a href="{{ route('admin.slides.index') }}" class="btn btn-outline-dark">Quay lại</a>

        </div>

    </form>

</div>

@endsection



@push('js')
@endpush

