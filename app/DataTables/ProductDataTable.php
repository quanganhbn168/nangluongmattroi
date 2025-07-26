<?php

namespace App\DataTables;

use App\Models\Product;
use Illuminate\Database\Eloquent\Builder;

class ProductDataTable
{
    /**
     * Truy vấn cơ sở dữ liệu để lấy dữ liệu.
     *
     * @return Builder
     */
    public function query(): Builder
    {
        return Product::query()->with('category')->latest();
    }

    /**
     * Lấy dữ liệu đã phân trang.
     *
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function getResults()
    {
        return $this->query()->paginate(10);
    }

    /**
     * Định nghĩa các cột của bảng.
     *
     * @return array
     */
    public function columns(): array
    {
        return [
            [
                'title' => '#',
                'data' => 'id',
                'render' => fn($record, $index) => $index + 1, // Hàm render cho cột STT
            ],
            [
                'title' => 'Tên',
                'data' => 'name',
            ],
            [
                'title' => 'Ảnh',
                'data' => 'image',
                'render' => function ($record) {
                    // Render cột ảnh
                    $imageUrl = $record->image ? asset($record->image) : asset('images/setting/no-image.png');
                    return "<img src='{$imageUrl}' alt='image' height='60' />";
                },
            ],
            [
                'title' => 'Danh mục',
                'data' => 'category.name', // Lấy từ relationship
                'render' => fn($record) => $record->category?->name ?? '---',
            ],
            [
                'title' => 'Trạng thái',
                'data' => 'status',
                'render' => function ($record) {
                    // **Sử dụng component boolean-toggle của bạn ở đây**
                    return view('components.boolean-toggle', [
                        'model' => 'Product',
                        'record' => $record,
                        'field' => 'status',
                    ])->render();
                },
            ],
            [
                'title' => 'Nổi bật',
                'data' => 'is_featured',
                'render' => function ($record) {
                    // **Sử dụng component boolean-toggle của bạn ở đây**
                    return view('components.boolean-toggle', [
                        'model' => 'Product',
                        'record' => $record,
                        'field' => 'is_featured',
                    ])->render();
                },
            ],
        ];
    }

    /**
     * Định nghĩa các nút hành động.
     *
     * @return array
     */
    public function actions(): array
    {
        return [
            'edit' => [
                'route' => 'admin.products.edit',
                'icon' => 'far fa-edit',
                'class' => 'btn-warning',
            ],
            'delete' => [
                'route' => 'admin.products.destroy',
                'icon' => 'far fa-trash-alt',
                'class' => 'btn-danger',
            ],
        ];
    }
}