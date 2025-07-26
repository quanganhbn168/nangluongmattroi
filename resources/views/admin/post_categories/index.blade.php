@extends('layouts.admin')
@section('title','Danh sách danh mục bài viết')
@section('content_header','Danh sách danh mục bài viết')
@section('content')

<div class="card">
	<div class="card-header">
		<h3 class="card-title"></h3>
		<div class="card-tools">
			<a href="{{ route('admin.post-categories.create') }}" class="btn btn-primary">
				<i class="fas fa-plus"></i> Thêm Danh mục
			</a>
		</div>
	</div>
	<div class="card-body table-responsive p-0">
		<table class="table table-hover text-nowrap">
			<thead>
				<tr>
					<th>#</th>
					<th>Tên danh mục</th>
					<th>Slug</th>
					<th>Ảnh</th>
					<th>Danh mục cha</th>
					<th>Trạng thái</th>
					<th>Ngày tạo</th>
					<th>Thao tác</th>
				</tr>
			</thead>
			<tbody>
				@foreach($categories as $key => $category)
				<tr>
					<td>{{ $key + 1 }}</td>
					<td>{{ $category->name }}</td>
					<td>{{ $category->slug }}</td>
					<td>
						<img src="{{ asset($category->image) }}" alt="Image" style="height: 60px;">
					</td>
					<td>{{ $category->parent?->name ?? '---' }}</td>
					<td>
						<span class="badge badge-{{ $category->status ? 'success' : 'secondary' }}">
							{{ $category->status ? 'Hiện' : 'Ẩn' }}
						</span>
					</td>
					<td>{{ $category->created_at->format('d/m/Y') }}</td>
					<td>
						<a href="{{ route('admin.post-categories.edit', $category) }}" class="btn btn-warning">
							<i class="far fa-edit"></i>
						</a>

						<form action="{{ route('admin.post-categories.destroy', $category) }}" method="POST" class="d-inline form-delete">
							@csrf
							@method('DELETE')
							<button type="submit" class="btn btn-danger delete">
								<i class="far fa-trash-alt"></i>
							</button>
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
document.addEventListener('DOMContentLoaded', function () {
	const forms = document.querySelectorAll('.form-delete');
	forms.forEach(function(form) {
		form.addEventListener('submit', function (e) {
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
});
</script>
@endpush
