{{-- resources/views/admin/products/index.blade.php --}}
@extends('layouts.admin')

@section('title', 'Danh sách sản phẩm')
@section('content_header', 'Danh sách sản phẩm')

@section('card_header')
    <div class="card-tools">
        <a href="{{ route('admin.products.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Thêm sản phẩm
        </a>
    </div>
@endsection

@section('content')
    {{-- Gọi component datatable chung và truyền đối tượng $dataTable vào --}}
    <x-datatable :dataTable="$dataTable" />
@endsection