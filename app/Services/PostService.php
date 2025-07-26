<?php

namespace App\Services;

use App\Models\Post;
use App\Models\PostCategory;
use Illuminate\Http\Request;
use App\Traits\UploadImageTrait;

class PostService
{
    use UploadImageTrait;

    public function getAll()
    {
        return Post::with('category')->latest()->get();
    }

    public function getParentOptions()
    {
        return PostCategory::select('id', 'name', 'parent_id')->get()->toArray();
    }

    public function create(Request $request): Post
    {
        $data = $request->validate([
            'post_category_id' => 'required|exists:post_categories,id',
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:posts,slug',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'banner' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:4096',
            'description' => 'nullable|string',
            'content' => 'nullable|string',
            'is_featured' => 'boolean',
            'status' => 'boolean',
        ]);

        if ($request->hasFile('image')) {
            $data['image'] = $this->uploadImage($request->file('image'), 'posts/image', 600,600, true);
        }

        if ($request->hasFile('banner')) {
            $data['banner'] = $this->uploadImage($request->file('banner'), 'posts/banner');
        }

        return Post::create($data);
    }

    public function update(Request $request, Post $post): Post
    {
        $data = $request->validate([
            'post_category_id' => 'required|exists:post_categories,id',
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:posts,slug,' . $post->id,
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'banner' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:4096',
            'description' => 'nullable|string',
            'content' => 'nullable|string',
            'is_featured' => 'boolean',
            'status' => 'boolean',
        ]);

        if ($request->hasFile('image')) {
            $data['image'] = $this->uploadImage($request->file('image'), 'posts/image', 600,600, true);
            $this->deleteImage($post->image);
        }

        if ($request->hasFile('banner')) {
            $data['banner'] = $this->uploadImage($request->file('banner'), 'posts/banner');
            $this->deleteImage($post->banner);
        }

        $post->update($data);
        return $post;
    }

    public function delete(Post $post): void
    {
        $this->deleteImage($post->image);
        $this->deleteImage($post->banner);
        $post->delete();
    }
}
