@extends('layouts.admin')
@section('title', 'Đơn hàng')

@section('content')
<h2 class="mb-3">Danh sách đơn hàng</h2>

<form method="GET" class="mb-3">
    <select name="status" onchange="this.form.submit()">
        <option value="">-- Lọc trạng thái --</option>
        <option value="pending">Chờ xử lý</option>
        <option value="confirmed">Đã xác nhận</option>
        <option value="shipped">Đã giao</option>
        <option value="completed">Hoàn thành</option>
        <option value="cancelled">Huỷ</option>
    </select>
</form>

<table class="table">
    <thead>
        <tr>
            <th>#</th>
            <th>Khách</th>
            <th>Tổng tiền</th>
            <th>Trạng thái</th>
            <th>Ngày đặt</th>
            <th>Hành động</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($orders as $order)
        <tr>
            <td>#{{ $order->id }}</td>
            <td>{{ $order->customer_name }}</td>
            <td>{{ number_format($order->total_price) }}₫</td>
            <td>{{ $order->status }}</td>
            <td>{{ $order->created_at->format('d/m/Y') }}</td>
            <td>
                <a href="{{ route('orders.show', $order) }}">Chi tiết</a> |
                <form method="POST" action="{{ route('orders.destroy', $order) }}" style="display:inline;">
                    @csrf @method('DELETE')
                    <button onclick="return confirm('Xoá đơn này?')">Xoá</button>
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

{{ $orders->links() }}
@endsection
