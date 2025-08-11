<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\DataTables\ProductDataTable;
use App\Http\Requests\ProductRequest;
use App\Services\ProductService;
use App\Services\AttributeService;

class ProductController extends Controller
{
    /**
     * @param ProductService $productService
     */
    public function __construct(
        protected ProductService $productService,
        protected AttributeService $attributeService
    ) {}

    /**
     * Hiển thị danh sách sản phẩm.
     *
     * @param ProductDataTable $dataTable
     * @return mixed
     */
    public function index(ProductDataTable $dataTable)
    {
        return $dataTable->render('admin.products.index');
    }

    /**
     * Hiển thị form tạo sản phẩm mới.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function create()
    {
        $categories = $this->productService->getCategoryOptions();
        $attributes = $this->attributeService->getAttributesWithValues();
        return view('admin.products.create', compact('categories','attributes'));
    }

    /**
     * Lưu sản phẩm mới vào cơ sở dữ liệu.
     *
     * @param ProductRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(ProductRequest $request)
    {
        $this->productService->store($request); 

        $route = $request->has('save_new')
            ? route('admin.products.create')
            : route('admin.products.index');

        return redirect($route)->with('success', 'Thêm sản phẩm thành công.');
    }

    /**
     * Hiển thị form chỉnh sửa sản phẩm.
     *
     * @param Product $product
     * @return \Illuminate\Contracts\View\View
     */
    public function edit(Product $product)
    {
        $product->load([
            'variants.attributeValues.attribute',
            'category',
            'brand',
            'images',
        ]);
        $categories = $this->productService->getCategoryOptions();
        $attributes = $this->productService->getAttribute();
        return view('admin.products.edit', compact('product', 'categories', 'attributes'));
    }

    /**
     * Cập nhật thông tin sản phẩm.
     *
     * @param ProductRequest $request
     * @param Product $product
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(ProductRequest $request, Product $product)
    {
        $this->productService->update($request, $product); 
        return redirect()->route('admin.products.index')->with('success', 'Cập nhật sản phẩm thành công.');
    }

    /**
     * Xóa sản phẩm.
     *
     * @param Product $product
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Product $product)
    {
        $this->productService->delete($product);
        return redirect()->route('admin.products.index')->with('success', 'Xóa sản phẩm thành công.');
    }

    /**
     * Hiển thị chi tiết sản phẩm ở frontend.
     *
     * @param Product $product
     * @return \Illuminate\Contracts\View\View
     */
    public function show(Product $product)
    {
        $product->load('category', 'images', 'tags', 'variants.attributeValues');
        $relatedProducts = Product::where('id', '!=', $product->id)
            ->where('category_id', $product->category_id)
            ->get();

        return view('frontend.products.detail', compact('product', 'relatedProducts'));
    }
    public function allProduct()
    {
        return view('frontend.products.allProduct');
    }
    public function checkCodeUniqueness(Request $request)
    {
        $code = $request->input('code');
        $productIdToIgnore = $request->input('ignore_id');

        $query = Product::where('code', $code);

        if ($productIdToIgnore) {
            $query->where('id', '!=', $productIdToIgnore);
        }

        $isAvailable = !$query->exists();

        return response()->json(['available' => $isAvailable]);
    }
}