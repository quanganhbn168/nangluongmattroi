@extends('layouts.admin')

@section('title','Chỉnh sửa sản phẩm')
@section('content_header', 'Chỉnh sửa sản phẩm')

@section('content')
{{-- Thay đổi action và thêm method PUT cho việc cập nhật --}}
<form action="{{ route('admin.products.update', $product) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    <div class="card card-widget">
        <div class="card-header">
            <h3 class="card-title">Chỉnh sửa sản phẩm: {{ $product->name }}</h3>
        </div>

        <div class="card-body">
            @if ($errors->any())
                <div class="alert alert-danger">
                    <h4 class="alert-heading"><i class="icon fas fa-ban"></i> Dữ liệu không hợp lệ!</h4>
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            {{-- Nav tabs (giữ nguyên cấu trúc) --}}
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
                    {{-- Các giá trị được điền từ $product --}}
                    <x-form.input name="name" label="Tên sản phẩm" :value="old('name', $product->name)" required placeholder="Tên sản phẩm"/>
                    <x-form.input name="code" label="Mã sản phẩm" :value="old('code', $product->code)" placeholder="Nhập mã sản phẩm" />
                    <div id="code-check-status" class="mt-1"></div>

                    <div class="row">
                        <div class="col-6">
                            <x-form.select-ajax
                                name="brand_id"
                                label="Thương hiệu"
                                {{-- Logic để hiển thị brand đã chọn --}}
                                :selected="old('brand_id', $product->brand_id) ? [['id' => old('brand_id', $product->brand_id), 'text' => $product->brand->name ?? '']] : []"
                                url="{{ route('admin.ajax.brands') }}"
                                placeholder="Chọn hoặc thêm thương hiệu"
                            />
                        </div>
                        <div class="col-6">
                            <x-form.category-select 
                                name="category_id" 
                                label="Danh mục sản phẩm" 
                                :options="$categories" 
                                :selected="old('category_id', $product->category_id)"
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
                    <x-form.image-input name="image" label="Ảnh đại diện" :value="$product->image" />
                    {{-- Giả sử product có quan hệ 'images' để lấy gallery --}}
                    <x-form.image-multi-input name="gallery" label="Ảnh chi tiết" :images="$product->images" />

                    <x-form.textarea name="description" label="Mô tả" :value="old('description', $product->description)" />
                    <x-form.ckeditor name="content" label="Nội dung chi tiết" :value="old('content', $product->content)" />
                </div>
                
                {{-- Tab 2: Thuộc tính phụ --}}
                <div class="tab-pane fade" id="tab-attributes" role="tabpanel">
                    <p>Chọn hoặc nhập các thuộc tính phụ tại đây...</p>
                </div>

                {{-- Tab 3: Biến thể --}}
                <div class="tab-pane fade" id="tab-variants" role="tabpanel">
                    @php
                        // Kiểm tra xem sản phẩm có thực sự có các biến thể hay không
                        // (nhiều hơn 1 biến thể, hoặc chỉ có 1 nhưng không phải là biến thể mặc định)
                        $hasVariants = $product->variants->count() > 1 || ($product->variants->first() && !$product->variants->first()->is_default);
                    @endphp
                    <x-form.switch name="has_variants" label="Sản phẩm có biến thể?" :checked="old('has_variants', $hasVariants)" />
                    <div id="variants-section" style="{{ old('has_variants', $hasVariants) ? '' : 'display: none;' }}">
                        {{-- Quan trọng: Truyền cả $product và $attributes vào partial --}}
                        @include('partials.admin.product.variants', [
                            'product' => $product,
                            'allAttributes' => $attributes
                        ])
                    </div>
                </div>

                {{-- Tab 4: SEO & Hiển thị --}}
                <div class="tab-pane fade" id="tab-seo" role="tabpanel">
                    <x-form.switch name="status" label="Trạng thái" :checked="old('status', $product->status)" />
                    <hr>
                    <x-form.textarea name="meta_des" label="Meta Description" :value="old('meta_des', $product->meta_des)" />
                    <x-form.textarea name="meta_key" label="Meta Keywords" :value="old('meta_key', $product->meta_key)" />
                </div>
            </div>
        </div>

        <div class="card-footer">
            {{-- Sửa lại nút cho phù hợp với trang Cập nhật --}}
            <button type="submit" class="btn btn-primary">Cập nhật</button>
            <a href="{{ route('admin.products.index') }}" class="btn btn-outline-dark">Quay lại</a>
        </div>
    </div>
</form>
@endsection

@push('css')
    {{-- Nếu partial variants có push css, nó sẽ được nạp vào đây --}}
@endpush

@push('js')

@endpush