<?php

namespace App\Services;

use App\Models\Intro;
use App\Traits\UploadImageTrait;
use Illuminate\Http\Request;

class IntroService
{
    use UploadImageTrait;

    public function create(Request $request): Intro
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'content' => 'nullable|string',
            'image' => 'required|image|max:2048',
            'banner' => 'nullable|image|max:4096',
            'status' => 'nullable|boolean',
        ]);

        $data['image'] = $this->uploadImage($request->file('image'), 'uploads/intro');

        if ($request->hasFile('banner')) {
            $data['banner'] = $this->uploadImage($request->file('banner'), 'uploads/intro');
        }

        return Intro::create($data);
    }

    public function update(Request $request, Intro $intro): Intro
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'content' => 'nullable|string',
            'image' => 'nullable|image|max:2048',
            'banner' => 'nullable|image|max:4096',
            'status' => 'nullable|boolean',
        ]);

        if ($request->hasFile('image')) {
            $this->deleteImage($intro->image);
            $data['image'] = $this->uploadImage($request->file('image'), 'uploads/intro');
        }

        if ($request->hasFile('banner')) {
            $this->deleteImage($intro->banner);
            $data['banner'] = $this->uploadImage($request->file('banner'), 'uploads/intro');
        }

        $intro->update($data);

        return $intro;
    }

    public function delete(Intro $intro): void
    {
        $this->deleteImage($intro->image);
        $this->deleteImage($intro->banner);
        $intro->delete();
    }
}
