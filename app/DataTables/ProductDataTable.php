<?php

namespace App\DataTables;

use App\Models\Product;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Services\DataTable;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;

class ProductDataTable extends DataTable
{
    /**
     * Get the query source filtered by the given attributes.
     */
    public function query(Product $model): QueryBuilder
    {
        return $model->newQuery()->with('category')->latest();
    }

    /**
     * Show the Datatable.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->setRowId('id')
            ->addColumn('action', function ($product) {
                $editUrl = route('admin.products.edit', $product->id);
                $deleteUrl = route('admin.products.destroy', $product->id);
                return view('components.action-buttons', compact('editUrl', 'deleteUrl'))->render();
            })
            ->rawColumns(['image', 'status', 'is_featured', 'action']) 
            ->editColumn('image', function ($product) {
                $imageUrl = $product->image ? asset($product->image) : asset('images/setting/no-image.png');
                return "<img src='{$imageUrl}' alt='image' height='60' />";
            })
            ->editColumn('category.name', function ($product) {
                return $product->category->name ?? '---';
            })
            ->editColumn('status', function ($product) {
                return view('components.boolean-toggle', [
                    'model' => 'Product',
                    'record' => $product,
                    'field' => 'status',
                ])->render();
            })
            ->editColumn('is_featured', function ($product) {
                 return view('components.boolean-toggle', [
                    'model' => 'Product',
                    'record' => $product,
                    'field' => 'is_featured',
                ])->render();
            });
    }

    /**
     * Get the HTML builder object.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('product-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->dom('Bfrtip')
            ->orderBy(1)
            ->selectStyleSingle()
            ->buttons([
                Button::make('export'),
                Button::make('print'),
                Button::make('reset'),
                Button::make('reload'),
            ]);
    }

    /**
     * Get the dataTable columns definition.
     */
    protected function getColumns(): array
    {
        return [
            Column::make('id')->title('#')->width(50),
            Column::make('name')->title('Tên sản phẩm'),
            Column::make('image')->title('Ảnh')->width(80)->searchable(false)->orderable(false),
            Column::make('category.name')->title('Danh mục'),
            Column::make('status')->title('Trạng thái')->width(80),
            Column::make('is_featured')->title('Nổi bật')->width(80),
            Column::computed('action')
                ->exportable(false)
                ->printable(false)
                ->width(120)
                ->addClass('text-center')
                ->title('Hành động'),
        ];
    }
}