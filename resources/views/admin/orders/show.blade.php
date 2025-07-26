@extends('layouts.admin')
@section('title', 'Chi tiết đơn hàng')

@section('content')
<h2>Chi tiết đơn hàng #{{ $order->id }}</h2>

<p><strong>Khách:</strong> {{ $order->customer_name }} - {{ $order->customer_phone }}</p>
<p><strong>Email:</strong> {{ $order->customer_email }}</p>
<p><strong>Địa chỉ:</strong> {{ $order->customer_address }}</p>
<p><strong>Ghi chú:</strong> {{ $order->note }}</p>
<p><strong>Trạng thái:</strong> {{ $order->status }}</p>

<form method="POST" action="{{ route('orders.updateStatus', $order) }}">
    @csrf
    <select name="status" onchange="this.form.submit()">
        @foreach(['pending', 'confirmed', 'shipped', 'completed', 'cancelled'] as $status)
        <option value="{{ $status }}" @selected($order->status === $status)>
            {{ ucfirst($status) }}
        </option>
        @endforeach
    </select>
</form>

<h3 class="mt-4">Sản phẩm đã mua:</h3>
<table class="table">
    <thead>
        <tr>
            <th>Tên SP</th>
            <th>SL</th>
            <th>Đơn giá</th>
            <th>Thành tiền</th>
        </tr>
    </thead>
    <tbody>
        @foreach($order->details as $detail)
        <tr>
            <td>{{ $detail->product_name }}</td>
            <td>{{ $detail->quantity }}</td>
            <td>{{ number_format($detail->product_price) }}₫</td>
            <td>{{ number_format($detail->subtotal) }}₫</td>
        </tr>
        @endforeach
    </tbody>
</table>

<p><strong>Tổng tiền:</strong> {{ number_format($order->total_price) }}₫</p>
@endsection
