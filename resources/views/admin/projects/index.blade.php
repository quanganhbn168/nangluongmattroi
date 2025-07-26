{{-- resources/views/admin/projects/index.blade.php --}}
@extends('layouts.admin')

@section('title','Danh sách dự án')
@section('content_header','Danh sách dự án')
@section('content')
<div class="card">
    <div class="card-header">
        <div class="card-tools">
            <a href="{{ route('admin.projects.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Thêm Dự án
            </a>
        </div>
    </div>
    <div class="card-body table-responsive p-0">
        <table class="table table-hover text-nowrap">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Tên</th>
                    <th>Ảnh</th>
                    <th>Danh mục cha</th>
                    <th>Trạng thái</th>
                    <th>Ngày tạo</th>
                    <th>Thao tác</th>
                </tr>
            </thead>
            <tbody>
                @foreach($projects as $key => $project)
                <tr>
                    <td>{{ $key + 1 }}</td>
                    <td>{{ $project->name }}</td>
                    <td><img src="{{ asset($project->image) }}" alt="image" height="60"></td>
                    <td>{{ $project->parent?->name ?? '---' }}</td>
                    <td><span class="badge badge-{{ $project->status ? 'success' : 'secondary' }}">{{ $project->status ? 'Hiện' : 'Ẩn' }}</span></td>
                    <td>{{ $project->created_at->format('d/m/Y') }}</td>
                    <td>
                        <a href="{{ route('admin.projects.edit', $project) }}" class="btn btn-warning"><i class="far fa-edit"></i></a>
                        <form action="{{ route('admin.projects.destroy', $project) }}" method="POST" style="display:inline-block" class="form-delete">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger delete"><i class="far fa-trash-alt"></i></button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.querySelectorAll('.form-delete').forEach(form => {
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        Swal.fire({
            title: 'Bạn chắc chắn?',
            text: 'Hành động này không thể hoàn tác!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Xoá',
            cancelButtonText: 'Huỷ'
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit();
            }
        });
    });
});
</script>
@endpush
