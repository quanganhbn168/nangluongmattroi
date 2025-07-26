<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Post;
use App\Models\Image;
use App\Models\Attribute;
use Illuminate\Http\Request;
use App\Services\ProductService;
use App\DataTables\ProductDataTable;

class ProductController extends Controller
{
    public function __construct(protected ProductService $productService) {}

    public function index(ProductDataTable $dataTable)
    {
        return view('admin.products.index', compact('dataTable'));
    }

    public function create()
    {
        $categories = $this->productService->getCategoryOptions();
        $attributes = Attribute::all();
        return view('admin.products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $this->productService->create($request);

        $route = $request->has('save_new')
            ? route('admin.products.create')
            : route('admin.products.index');

        return redirect($route)->with('success', 'Thêm sản phẩm thành công.');
    }

    public function edit(Product $product)
    {
        $categories = $this->productService->getCategoryOptions();
        $images = Image::where('item_type','product')->where('item_id',$product->id)->pluck('image')->toArray();
        return view('admin.products.edit', compact('product', 'categories','images'));
    }

    public function update(Request $request, Product $product)
    {
        $this->productService->update($request, $product);
        return redirect()->route('admin.products.index')->with('success', 'Cập nhật sản phẩm thành công.');
    }

    public function destroy(Product $product)
    {
        $this->productService->delete($product);
        return redirect()->route('admin.products.index')->with('success', 'Xoá sản phẩm thành công.');
    }

    public function show(Product $product)
    {
        $category = $product->category()->select('id', 'name', 'slug', 'parent_id')->firstOrFail();
        $relatedProducts = Product::whereNot("id",$product->id)->where("category_id",$category->id)->get();
        $posts = Post::where('status', 1)->inRandomOrder()->take(3)->get();

        $images = Image::where('item_type','product')->where('item_id',$product->id)->get();
        return view('frontend.products.detail', compact('product','category','images','relatedProducts','posts'));
    }

}
