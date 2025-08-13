@extends('layouts.admin')

@section('title', 'Quản lý Đơn hàng')

@section('content')
<div class="card">
    <div class="card-header">
        <h5 class="card-title mb-0">Danh sách Đơn hàng</h5>
    </div>
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <a href="{{ route('admin.orders.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle"></i> Tạo đơn hàng mới
            </a>
            <form action="{{ route('admin.orders.index') }}" method="GET" class="d-flex">
                <input type="text" class="form-control me-2" name="search" placeholder="Tìm kiếm khách hàng..." value="{{ request('search') }}">
                <button type="submit" class="btn btn-secondary">Tìm</button>
            </form>
        </div>

        @if (session('success'))
            <div class="alert alert-success" role="alert">{{ session('success') }}</div>
        @endif

        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead class="table-light">
                    <tr>
                        <th>Mã ĐH</th>
                        <th>Khách hàng</th>
                        <th>Tổng tiền</th>
                        <th>Trạng thái</th>
                        <th>Ngày tạo</th>
                        <th>Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($orders as $order)
                        <tr>
                            <td><strong>#{{ $order->id }}</strong></td>
                            <td>
                                {{ $order->customer_name ?? $order->user->name }}
                                <br>
                                <small class="text-muted">{{ $order->customer_phone ?? $order->user->phone }}</small>
                            </td>
                            <td>{{ number_format($order->total_price, 0, ',', '.') }} đ</td>
                            <td>
                                @php
                                    $statusClass = [
                                        'pending' => 'bg-warning text-dark',
                                        'processing' => 'bg-info text-dark',
                                        'completed' => 'bg-success',
                                        'cancelled' => 'bg-danger',
                                    ][$order->status] ?? 'bg-secondary';
                                @endphp
                                <span class="badge {{ $statusClass }}">{{ ucfirst($order->status) }}</span>
                            </td>
                            <td>{{ $order->created_at->format('d/m/Y H:i') }}</td>
                            <td>
                                <a href="{{ route('admin.orders.show', $order) }}" class="btn btn-sm btn-light" title="Xem"><i class="bi bi-eye"></i></a>
                                <a href="{{ route('admin.orders.edit', $order) }}" class="btn btn-sm btn-warning" title="Sửa"><i class="bi bi-pencil"></i></a>
                                <form action="{{ route('admin.orders.destroy', $order) }}" method="POST" class="d-inline" onsubmit="return confirm('Anh có chắc muốn xóa đơn hàng này?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" title="Xóa"><i class="bi bi-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center">Không có đơn hàng nào.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-3">{{ $orders->appends(request()->query())->links() }}</div>
    </div>
</div>
@endsection