@extends('layouts.admin')
@section('title','Cài đặt chung')
@section('content_header','Cài đặt chung')
@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Cài đặt chung</h3>
    </div>
    <form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="card-body">
            <x-form.input name="name" label="Tên Công ty" :value="$setting->name ?? ''" />
            <x-form.input name="email" label="Email" :value="$setting->email ?? ''" />
            <x-form.input name="phone" label="Số điện thoại" :value="$setting->phone ?? ''" />
            <x-form.input name="zalo" label="Zalo" :value="$setting->zalo ?? ''" />
            <x-form.input name="mess" label="Mess" :value="$setting->mess ?? ''" />
            <x-form.input name="tiktok" label="Tiktok" :value="$setting->tiktok ?? ''" />
            <x-form.input name="youtube" label="Youtube" :value="$setting->youtube ?? ''" />
            <x-form.input name="address" label="Địa chỉ" :value="$setting->address ?? ''" />
            <x-form.ckeditor name="map" label="Iframe Google Map" :value="$setting->map ?? ''" />
            <x-form.image-input
                name="logo"
                label="Logo"
                :value="$setting->logo ?? ''"
            />
            <x-form.image-input
                name="favicon"
                label="Favicon"
                :value="$setting->favicon ?? ''"
            />
            {{-- CKEditor for script fields --}}
            <x-form.textarea name="schema_script" label="Schema JSON-LD" :value="$setting->schema_script ?? ''" />
            <x-form.textarea name="head_script" label="Code trước </head>" :value="$setting->head_script ?? ''" />
            <x-form.textarea name="body_script" label="Code trước </body>" :value="$setting->body_script ?? ''" />
                <hr>
            <x-form.textarea name="meta_description" label="Meta Description" :value="$setting->meta_description ?? ''" />
            <x-form.textarea name="meta_keywords" label="Meta Keyword" :value="$setting->meta_keywords ?? ''" />
            <x-form.image-input
                name="meta_image"
                label="Ảnh chia sẻ"
                :value="$setting->meta_image ?? ''"
            />
       </div>
        <div class="card-footer text-right">
            <button type="submit" class="btn btn-primary">Lưu cài đặt</button>
        </div>
    </form>
</div>
@endsection
