@extends('layouts.admin')

@section('title', 'Tạo Đơn hàng mới')

@section('content')
<div class="card">
    <div class="card-header">
        <h5 class="card-title mb-0">Tạo Đơn hàng mới</h5>
    </div>
    <div class="card-body">
        <x-alert-errors />
        <form action="{{ route('admin.orders.store') }}" method="POST">
            @include('admin.orders._form')
        </form>
    </div>
</div>
@endsection