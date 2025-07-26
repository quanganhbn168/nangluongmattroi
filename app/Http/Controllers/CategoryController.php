<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use App\Services\CategoryService;

class CategoryController extends Controller
{
    protected $service;

    public function __construct(CategoryService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        $categories = Category::with('parent')->orderBy('id', 'desc')->get();
        return view('admin.categories.index', compact('categories'));
    }

    public function create()
    {
        $categories = Category::get();
        return view('admin.categories.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $this->service->create($request);
        return redirect()->route('admin.categories.index')->with('success', 'Tạo danh mục thành công');
    }

    public function edit(Category $category)
    {
        $categories = Category::where('id', '!=', $category->id)->pluck('name', 'id');
        return view('admin.categories.edit', compact('category', 'categories'));
    }

    public function update(Request $request, Category $category)
    {
        $this->service->update($request, $category);
        return redirect()->route('admin.categories.index')->with('success', 'Cập nhật danh mục thành công');
    }

    public function destroy(Category $category)
    {
        $this->service->delete($category);
        return redirect()->route('admin.categories.index')->with('success', 'Xóa danh mục thành công');
    }
}
