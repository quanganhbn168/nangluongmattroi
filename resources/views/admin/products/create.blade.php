@extends('layouts.admin')

@section('title', isset($product) ? 'Chỉnh sửa sản phẩm' : 'Thêm sản phẩm mới')
@section('content_header', isset($product) ? 'Chỉnh sửa sản phẩm' : 'Thêm sản phẩm mới')

@section('content')
<form action="{{ isset($product) ? route('admin.products.update', $product) : route('admin.products.store') }}" 
      method="POST" enctype="multipart/form-data">
    @csrf
    @if(isset($product)) @method('PUT') @endif

    <div class="card card-widget">
        <div class="card-header">
            <h3 class="card-title">Quản lý sản phẩm</h3>
        </div>

        <div class="card-body">

            {{-- Nav tabs --}}
            <ul class="nav nav-pills mb-3" id="product-tabs" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="tab-general-tab" data-toggle="pill" href="#tab-general" role="tab">1. Thông tin chung</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="tab-attributes-tab" data-toggle="pill" href="#tab-attributes" role="tab">2. Thuộc tính phụ</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="tab-variants-tab" data-toggle="pill" href="#tab-variants" role="tab">3. Biến thể</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="tab-seo-tab" data-toggle="pill" href="#tab-seo" role="tab">4. SEO & Hiển thị</a>
                </li>
            </ul>

            <div class="tab-content" id="product-tabs-content">

                {{-- Tab 1: Thông tin chung --}}
                <div class="tab-pane fade show active" id="tab-general" role="tabpanel">
                    <x-form.input name="name" label="Tên sản phẩm" :value="old('name', $product->name ?? '')" required />
                    <x-form.input name="code" label="Mã sản phẩm" :value="old('code', $product->code ?? '')" />
                    <div class="row">
                        <div class="col-6">
                            <x-form.select-ajax
                                name="brand_id"
                                label="Thương hiệu"
                                :selected="old('brand_id', $product->brand_id ?? '') ? [['id' => old('brand_id', $product->brand_id ?? ''), 'text' => $product->brand->name ?? '']] : []"
                                url="{{ route('admin.ajax.brands') }}"
                                placeholder="Chọn hoặc thêm thương hiệu"
                            />
                        </div>
                        <div class="col-6">
                            <x-form.category-select 
                                name="category_id" 
                                label="Danh mục sản phẩm" 
                                :options="$categories" 
                                :selected="old('category_id', $product->category_id ?? 0)"
                                placeholder="-- Chọn danh mục --" 
                            />
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-6">
                            <x-form.money-input name="price_discount" label="Giá bán" 
                                :value="old('price_discount', $product->price_discount ?? 0)" />
                        </div>
                        <div class="col-6">
                            <x-form.money-input name="price" label="Giá so sánh" 
                                :value="old('price', $product->price ?? 0)" />
                        </div>
                    </div>

                    <x-form.textarea name="description" label="Mô tả" :value="old('description', $product->description ?? '')" />
                    <x-form.ckeditor name="content" label="Nội dung chi tiết" :value="old('content', $product->content ?? '')" />
                    <x-form.image-multi-input name="gallery" label="Ảnh chi tiết" :images="$product->gallery ?? []" />
                </div>

                {{-- Tab 2: Thuộc tính phụ --}}
                <div class="tab-pane fade" id="tab-attributes" role="tabpanel">
                    {{-- Ở đây anh sẽ render các thuộc tính phụ như màu sắc, chất liệu... --}}
                    {{-- Có thể để sau khi xong variant logic --}}
                    <p>Chọn hoặc nhập các thuộc tính phụ tại đây...</p>
                </div>

                {{-- Tab 3: Biến thể --}}
                <div class="tab-pane fade" id="tab-variants" role="tabpanel">
                    <x-product._variants :product="$product ?? null" :attribute="$attribute" />
                </div>

                {{-- Tab 4: SEO & Hiển thị --}}
                <div class="tab-pane fade" id="tab-seo" role="tabpanel">
                    <x-form.image-input name="image" label="Ảnh đại diện" :value="$product->image ?? ''" />
                    <x-form.image-input name="banner" label="Banner (tuỳ chọn)" :value="$product->banner ?? ''" />
                    <x-form.switch name="status" label="Trạng thái" :checked="old('status', $product->status ?? true)" />
                    <hr>
                    <x-form.textarea name="meta_des" label="Meta Description" :value="old('meta_des', $product->meta_des ?? '')" />
                    <x-form.textarea name="meta_key" label="Meta Keywords" :value="old('meta_key', $product->meta_key ?? '')" />
                </div>

            </div>
        </div>

        <div class="card-footer">
            <button type="submit" class="btn btn-primary">Lưu</button>
            <button type="submit" name="save_new" value="1" class="btn btn-success">Lưu & Thêm mới</button>
            <a href="{{ route('admin.products.index') }}" class="btn btn-outline-dark">Quay lại</a>
        </div>
    </div>
</form>
@endsection
