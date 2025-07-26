@extends('layouts.admin')
@section('title', 'Cảm nhận học viên')
@section('content_header', 'Cảm nhận học viên')

@section('content')
<div class="card">
  <div class="card-header">
    <a href="{{ route('admin.testimonials.create') }}" class="btn btn-primary">Thêm mới</a>
  </div>

  <div class="card-body table-responsive">
    <table class="table table-bordered">
      <thead>
        <tr>
          <th>Ảnh</th>
          <th>Họ tên</th>
          <th>Vị trí</th>
          <th>Trạng thái</th>
          <th>Ngày tạo</th>
          <th width="120">Thao tác</th>
        </tr>
      </thead>
      <tbody>
        @foreach($testimonials as $item)
        <tr>
          <td><img src="{{ asset($item->image ?? 'images/setting/no-image.png') }}" width="50" height="50" class="rounded-circle" /></td>
          <td>{{ $item->name }}</td>
          <td>{{ $item->position }}</td>
          <td>
            @if($item->status)
              <span class="badge badge-success">Hiển thị</span>
            @else
              <span class="badge badge-secondary">Ẩn</span>
            @endif
          </td>
          <td>{{ $item->created_at->format('d/m/Y') }}</td>
          <td>
            <a href="{{ route('admin.testimonials.edit', $item) }}" class="btn btn-sm btn-warning"><i class="bi bi-pencil"></i></a>
            <form action="{{ route('admin.testimonials.destroy', $item) }}" method="POST" class="d-inline" onsubmit="return confirm('Xoá cảm nhận này?')">
              @csrf @method('DELETE')
              <button class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></button>
            </form>
          </td>
        </tr>
        @endforeach
      </tbody>
    </table>

    {{ $testimonials->links() }}
  </div>
</div>
@endsection
