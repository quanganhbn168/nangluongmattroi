<?php

namespace App\Services;

use App\Models\PostCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Traits\UploadImageTrait;

class PostCategoryService
{
    use UploadImageTrait;

    public function getAll()
    {
        return PostCategory::with('parent')->latest()->get();
    }

    public function getParentOptions(?int $exceptId = null)
    {
        return PostCategory::when($exceptId, fn($q) => $q->where('id', '!=', $exceptId))
        ->pluck('name', 'id')
        ->prepend('Danh mục gốc', 0)
        ->toArray();
    }


    public function create(Request $request): PostCategory
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:post_categories,slug',
            'parent_id' => 'nullable|integer|min:0',
            'image' => 'required|image|mimes:jpg,jpeg,png,webp|max:2048',
            'banner' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:4096',
            'status' => 'nullable|boolean',
        ]);

        $data['slug'] = $data['slug'] ?? Str::slug($data['name']);
        $data['image'] = $this->uploadImage($request->file('image'), 'uploads/post_categories');
        if ($request->hasFile('banner')) {
            $data['banner'] = $this->uploadImage($request->file('banner'), 'uploads/post_categories');
        }

        return PostCategory::create($data);
    }

    public function update(PostCategory $postCategory, Request $request): PostCategory
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:post_categories,slug,' . $postCategory->id,
            'parent_id' => 'nullable|integer|min:0',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'banner' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:4096',
            'status' => 'nullable|boolean',
        ]);

        $data['slug'] = $data['slug'] ?? Str::slug($data['name']);

        if ($request->hasFile('image')) {
            $this->deleteImage($postCategory->image);
            $data['image'] = $this->uploadImage($request->file('image'), 'uploads/post_categories');
        }

        if ($request->hasFile('banner')) {
            $this->deleteImage($postCategory->banner);
            $data['banner'] = $this->uploadImage($request->file('banner'), 'uploads/post_categories');
        }

        $postCategory->update($data);
        return $postCategory;
    }

    public function delete(PostCategory $postCategory): bool
    {
        $this->deleteImage($postCategory->image);
        $this->deleteImage($postCategory->banner);
        return $postCategory->delete();
    }
}
