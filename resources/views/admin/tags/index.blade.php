@extends('layouts.admin')

@section('title', 'Danh sách tag')
@section('content_header', 'Danh sách tag')

@section('content')
<div class="card">
    <div class="card-header">
        <a href="{{ route('admin.tags.create') }}" class="btn btn-primary"><i class="fas fa-plus"></i> Thêm tag</a>
    </div>
    <div class="card-body table-responsive p-0">
        <table class="table table-hover table-bordered">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Tên</th>
                    <th>Slug</th>
                    <th>Ngày tạo</th>
                    <th width="120">Thao tác</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($tags as $item)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $item->name }}</td>
                    <td>{{ $item->slug }}</td>
                    <td>{{ $item->created_at->format('d/m/Y') }}</td>
                    <td>
                        <a href="{{ route('admin.tags.edit', $item) }}" class="btn btn-sm btn-info"><i class="fas fa-edit"></i></a>
                        <x-form.delete :action="route('admin.tags.destroy', $item)" />
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="card-footer">
        {{ $tags->links() }}
    </div>
</div>
@endsection
