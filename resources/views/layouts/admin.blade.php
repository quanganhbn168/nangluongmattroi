<!DOCTYPE html>
<html lang="vi">
    <head>
        <meta charset="UTF-8">
        <title>@yield('title', config('app.name'))</title>
        <link rel="icon" href="{{ asset('favicon.png') }}" type="image/x-icon" />

        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        {{-- AdminLTE Assets --}}
        <link rel="stylesheet" href="{{ asset('vendor/adminlte/plugins/fontawesome-free/css/all.min.css') }}">
        <link rel="stylesheet" href="{{ asset('vendor/adminlte/plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">
        <link rel="stylesheet" href="{{ asset('vendor/adminlte/dist/css/adminlte.min.css') }}">
        <link rel="stylesheet" href="{{ asset('vendor/bootstrap-icons/bootstrap-icons.min.css') }}">

        <link rel="stylesheet" href="{{asset('plugins/sweetalert2/bootstrap-4.min.css')}}">
        <link rel="stylesheet" href="{{asset('plugins/toastr/toastr.min.css')}}">
        <link rel="apple-touch-icon" sizes="180x180" href="/favicon/apple-touch-icon.png">
        <link rel="icon" type="image/png" sizes="32x32" href="/favicon/favicon-32x32.png">
        <link rel="icon" type="image/png" sizes="16x16" href="/favicon/favicon-16x16.png">
        <link rel="shortcut icon" href="/favicon/favicon.ico">
        {{-- <link rel="manifest" href="/manifest.json"> --}}
        @stack('css')
    </head>
    <body class="hold-transition sidebar-mini layout-fixed">
        <div class="wrapper">
            @include('partials.admin.navbar')
            @include('partials.admin.sidebar')
            <!-- Content Wrapper. Contains page content -->
            <div class="content-wrapper">
                <!-- Content Header (Page header) -->
                <section class="content-header">
                    <div class="container-fluid">
                        <div class="row mb-2">
                            <div class="col-sm-6">
                                <h2>@yield('content_header')</h2>
                            </div>
                            <div class="col-sm-6">
                                @include('components.breadcrumb')
                            </div>
                        </div>
                    </div><!-- /.container-fluid -->
                </section>

                <!-- Main content -->
                <section class="content">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-12">
                            <!-- Default box -->
                                @yield('content')
                            <!-- /.card -->
                            </div>
                        </div>
                    </div>
                </section>
            <!-- /.content -->
            </div>
            @include('partials.admin.footer')
        </div>

        <script src="{{asset('js/jquery-3.7.1.min.js')}}"></script>
        {{-- AdminLTE Scripts --}}
        <script src="{{ asset('vendor/adminlte/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
        <script src="{{ asset('vendor/adminlte/dist/js/adminlte.min.js') }}"></script>

        <!-- toast and  sweetalert2 -->
        <script src="{{asset('plugins/toastr/toastr.min.js')}}"></script>
        <script src="{{asset('plugins/sweetalert2/sweetalert2.min.js')}}"></script>
        <script>
    const Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3000
    });
</script>

@if(session('success'))
    <script>
        Toast.fire({
            icon: 'success',
            title: @json(session('success'))
        });
    </script>
@endif

@if(session('error'))
    <script>
        Toast.fire({
            icon: 'error',
            title: @json(session('error'))
        });
    </script>
@endif
<script>
document.addEventListener('DOMContentLoaded', function () {
    const forms = document.querySelectorAll('form');

    forms.forEach(form => {
        form.addEventListener('submit', function (e) {
            if (form.classList.contains('form-delete')) {
                return; 
            }
            // Chặn submit nếu đã xử lý rồi
            if (form.classList.contains('submitting')) {
                e.preventDefault();
                return false;
            }

            // Gắn cờ để không cho submit 2 lần
            form.classList.add('submitting');

            // Khóa tất cả các nút submit trong form
            form.querySelectorAll('button[type="submit"]').forEach(btn => {
                btn.disabled = true;
            });

            // Lấy nút được bấm
            const clickedBtn = document.activeElement;
            let action = clickedBtn?.value || 'save';

            let message = 'Đang xử lý...';
            if (action === 'update') message = 'Đang cập nhật...';
            if (action === 'save') message = 'Đang tạo mới...';
            if (action === 'save_new') message = 'Đang tạo mới...';

            Swal.fire({
                title: message,
                text: 'Vui lòng chờ trong giây lát...',
                icon: 'info',
                allowOutsideClick: false,
                allowEscapeKey: false,
                showConfirmButton: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            // Cho phép submit tiếp (không prevent)
        });
    });
});
</script>

<script>
    $('.btn-toggle').on('click', function () {
        const btn = $(this);
        const id = btn.data('id');
        const model = btn.data('model');
        const field = btn.data('field');

        $.ajax({
            url: '{{ route('admin.toggle') }}',
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                id: id,
                model: model,
                field: field
            },
            success: function (res) {
                btn.text(res.value ? '✓' : '✗');

                Toast.fire({
                    icon: 'success',
                    title: res.message || 'Đã cập nhật thành công'
                });
            },
            error: function (xhr) {
                let message = 'Đã xảy ra lỗi';

                if (xhr.responseJSON && xhr.responseJSON.error) {
                    message = xhr.responseJSON.error;
                }

                Toast.fire({
                    icon: 'error',
                    title: message
                });
            }
        });
    });
</script>

        
        @stack('js')
    </body>
</html>
