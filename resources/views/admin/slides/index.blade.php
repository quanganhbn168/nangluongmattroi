@extends('layouts.admin')

@section('title','Danh sách slide')

@section('content_header','Danh sách slide')

@section('content')



<div class="card">

	<div class="card-header">

		<h3 class="card-title"></h3>

		<div class="card-tools">

			<a href="{{ route('admin.slides.create') }}" class="btn btn-primary">

				<i class="fas fa-plus"></i> Thêm Slide

			</a>

		</div>

	</div>

	<div class="card-body table-responsive p-0">

		<table class="table table-hover text-nowrap">

			<thead>

				<tr>

					<th>#</th>

					<th>Tiêu đề</th>

					<th>Ảnh</th>

					<th>Trạng thái</th>

					<th>Ngày tạo</th>

					<th>Cập nhật</th>

					<th>Thao tác</th>

				</tr>

			</thead>

			<tbody>

				@foreach($slides as $key => $slide)

				<tr>

					<td>{{ $key + 1 }}</td>

					<td>{{ $slide->title }}</td>

					<td>

						



						<img src="{{ asset($slide->image) }}" alt="Slide" style="height: 80px;">

					</td>



					<td>

						<x-boolean-toggle model="slide" :record="$slide" field="status" />

					</td>

					<td>{{ $slide->created_at->format('d/m/Y') }}</td>

					<td>{{ $slide->updated_at->format('d/m/Y') }}</td>

					<td>

						<a href="{{ route('admin.slides.edit', $slide) }}" class="btn btn-warning">

							<i class="far fa-edit"></i>

						</a>



						<form action="{{ route('admin.slides.destroy', $slide) }}" method="POST" style="display:inline-block;" class="form-delete">

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

