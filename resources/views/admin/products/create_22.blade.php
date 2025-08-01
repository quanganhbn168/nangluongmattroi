@extends('layouts.admin')

@section('title', 'Thêm sản phẩm mới')
@section('content_header', 'Thêm sản phẩm mới')
@push('css')
<style>
    .product-properties {
    background-color: rgba(245,245,245,1);
    padding: 10px 10px;
    border-radius: 8px;
}
</style>
@endpush
@section('content')
<form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <div class="row">
        {{-- Cột chính --}}
        <div class="col-12 col-lg-9">
            <div class="card card-widget">
                <div class="card-header">
                    <h3 class="card-title">Thông tin chung</h3>
                </div>
                <div class="card-body">
                    <x-form.product-images :name="'images'" :images="[]" :limit="9" />
                    <x-form.input name="name" label="Tên sản phẩm" />
                    <div class="row">
                        <div class="col-12 col-md-6">
                            <x-form.select-ajax
                                name="brand_id"
                                label="Thương hiệu"
                                :selected="old('brand_id') ? [['id' => old('brand_id'), 'text' => '']] : []"
                                url="{{ route('admin.ajax.brands') }}"
                                placeholder="Chọn hoặc thêm thương hiệu"
                            />
                        </div>
                        <div class="col-12 col-md-6">
                            <x-form.select name="category_id" label="Danh mục sản phẩm" :options="$categories" :selected="old('category_id', 0)" placeholder="-- Chọn danh mục --" />
                        </div>
                    </div>
                        <div class="row">
                            <div class="col-6">
                                <x-form.money-input
                                name="price_discount"
                                label="Giá bán"
                                :value="old('price_discount', 0)"
                                />
                            </div>
                            <div class="col-6">
                                <x-form.money-input
                                name="price"
                                label="Giá so sánh"
                                :value="old('price', 0)"
                                />
                            </div>
                        </div>
                        <x-form.textarea name="description" label="Mô tả" :value="old('description')" />
                        <x-form.ckeditor name="content" label="Nội dung chi tiết" :value="old('content')" />
                    </div>
                </div>
                <div class="card card-widget">
                    <div class="card-header">
                        <h3 class="card-title">Thông tin bán hàng</h3>
                    </div>
                    <div class="card-body">
                        <x-product.variants />               
                    </div>
                </div>          
            </div>
        </div>

        {{-- Cột phụ --}}
        <div class="col-12 col-lg-3">
        </div>
    </div>

    <div class="card-footer">
        <button type="submit" class="btn btn-primary">Lưu</button>
        <button type="submit" name="save_new" value="1" class="btn btn-success">Lưu & Thêm mới</button>
        <a href="{{ route('admin.products.index') }}" class="btn btn-outline-dark">Quay lại</a>
    </div>
</form>
@endsection
