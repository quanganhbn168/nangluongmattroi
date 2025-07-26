{{-- resources/views/admin/components/datatable.blade.php --}}
@props(['dataTable'])

@php
    $columns = $dataTable->columns();
    $actions = $dataTable->actions();
    $results = $dataTable->getResults();
@endphp

<div class="card">
    <div class="card-header">
        {{-- Nút Thêm mới có thể được đặt ở đây hoặc ngoài view này --}}
        @hasSection('card_header')
            @yield('card_header')
        @endif
    </div>
    <div class="card-body table-responsive p-0">
        <table class="table table-bordered table-hover table-fixed no-wrap">
            <thead>
                <tr>
                    @foreach ($columns as $column)
                        <th>{{ $column['title'] }}</th>
                    @endforeach
                    @if (!empty($actions))
                        <th>Thao tác</th>
                    @endif
                </tr>
            </thead>
            <tbody>
                @forelse ($results as $index => $record)
                    <tr>
                        @foreach ($columns as $column)
                            <td>
                                @if (isset($column['render']) && is_callable($column['render']))
                                    {{-- Dùng hàm render nếu có --}}
                                    {!! $column['render']($record, $results->firstItem() + $index - 1) !!}
                                @else
                                    {{-- Nếu không thì hiển thị dữ liệu thô --}}
                                    {!! \Illuminate\Support\Arr::get($record, $column['data']) !!}
                                @endif
                            </td>
                        @endforeach
                        
                        @if (!empty($actions))
                            <td>
                                @if (isset($actions['edit']))
                                    <a href="{{ route($actions['edit']['route'], $record) }}" 
                                       class="btn {{ $actions['edit']['class'] }}">
                                       <i class="{{ $actions['edit']['icon'] }}"></i>
                                    </a>
                                @endif

                                @if (isset($actions['delete']))
                                    <form action="{{ route($actions['delete']['route'], $record) }}" method="POST" style="display:inline-block" class="form-delete">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn {{ $actions['delete']['class'] }}">
                                            <i class="{{ $actions['delete']['icon'] }}"></i>
                                        </button>
                                    </form>
                                @endif
                            </td>
                        @endif
                    </tr>
                @empty
                    <tr>
                        <td colspan="{{ count($columns) + (!empty($actions) ? 1 : 0) }}" class="text-center">
                            Không có dữ liệu
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if ($results->hasPages())
        <div class="card-footer clearfix">
            {{ $results->links() }}
        </div>
    @endif
</div>

@push('js')
{{-- Script xoá vẫn giữ nguyên vì nó đã hoạt động tốt --}}
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