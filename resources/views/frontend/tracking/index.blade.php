@extends('layouts.master') {{-- Giả sử bạn có layout chung --}}
@section('title', 'Tra cứu thông tin đơn hàng')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="text-center">TRA CỨU THÔNG TIN BẢO HÀNH</h3>
                </div>
                <div class="card-body">
                    <p class="text-center text-muted">Vui lòng nhập số điện thoại bạn đã dùng để đặt hàng để xem thông tin chi tiết.</p>
                    
                    {{-- Form tìm kiếm --}}
                    <form action="{{ route('order.tracking') }}" method="GET" class="mb-4">
                        <div class="input-group">
                            <input type="text" class="form-control" name="phone" placeholder="Nhập số điện thoại..." value="{{ $phone_searched ?? '' }}" required>
                            <div class="input-group-append">
                                <button class="btn btn-primary" type="submit">Tra cứu</button>
                            </div>
                        </div>
                    </form>

                    <hr>

                    {{-- Phần hiển thị kết quả --}}
                    @if(isset($phone_searched))
                        @if($user && $user->orders->count() > 0)
                            <h4 class="mt-4">Kết quả cho số điện thoại: <strong>{{ $phone_searched }}</strong></h4>
                            <p>Khách hàng: <strong>{{ $user->name }}</strong></p>
                            
                            @foreach($user->orders->sortByDesc('created_at') as $order)
                                <div class="card mb-3">
                                    <div class="card-header bg-light d-flex justify-content-between">
                                        <span>Mã đơn hàng: <strong>#{{ $order->id }}</strong></span>
                                        <span>Ngày đặt: {{ $order->created_at->format('d/m/Y') }}</span>
                                    </div>
                                    <div class="card-body">
                                        <p><strong>Trạng thái:</strong> <span class="badge bg-success text-white">{{ $order->status }}</span></p>
                                        <p><strong>Ngày lắp đặt dự kiến:</strong> {{ $order->installation_date ? \Carbon\Carbon::parse($order->installation_date)->format('d/m/Y') : 'Chưa có' }}</p>
                                        
                                        <h6 class="mt-3">Sản phẩm đã mua:</h6>
                                        <ul class="list-group">
                                            @foreach($order->items as $item)
                                                <li class="list-group-item">
                                                    <strong>{{ $item->product->name ?? $item->product_name }}</strong> x {{ $item->quantity }}
                                                    <br>
                                                    <small class="text-muted">Hạn bảo hành: {{ $item->warranty_expires_at ? \Carbon\Carbon::parse($item->warranty_expires_at)->format('d/m/Y') : 'Không áp dụng' }}</small>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                            @endforeach

                        @elseif($user)
                             <div class="alert alert-warning text-center">
                                Khách hàng <strong>{{ $user->name }}</strong> chưa có đơn hàng nào.
                            </div>
                        @else
                            <div class="alert alert-danger text-center">
                                Không tìm thấy thông tin nào cho số điện thoại <strong>{{ $phone_searched }}</strong>.
                            </div>
                        @endif
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection