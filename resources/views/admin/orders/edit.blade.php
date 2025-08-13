@extends('layouts.admin')

@section('title', 'Chỉnh sửa Đơn hàng #' . $order->code)

@section('content')
<div class="card">
    <div class="card-header">
        <h5 class="card-title mb-0">Chỉnh sửa Đơn hàng #{{ $order->code }}</h5>
    </div>
    <div class="card-body">
        <x-alert-errors />
        <form action="{{ route('admin.orders.update', $order) }}" method="POST">
            @method('PUT')
            @include('admin.orders._form')
        </form>
    </div>
</div>
@endsection