<?php

namespace App\Services;

use App\Models\Project;
use App\Traits\UploadImageTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProjectService
{
    use UploadImageTrait;

    public function create(Request $request): Project
    {
        $data = $request->validate([
            'name'        => 'required|string|max:255',
            'description' => 'required|string',
            'content'     => 'nullable|string',
            'parent_id'   => 'nullable|integer|min:0',
            'status'      => 'nullable|boolean',
            'image'       => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'banner'      => 'nullable|image|mimes:jpg,jpeg,png,webp|max:4096',
        ]);

        $data['parent_id'] = (int) ($data['parent_id'] ?? 0);

        if ($request->hasFile('image')) {
            $data['image'] = $this->uploadImage($request->file('image'), 'uploads/projects', 1200, true);
        }

        if ($request->hasFile('banner')) {
            $data['banner'] = $this->uploadImage($request->file('banner'), 'uploads/projects', 1920, true);
        }

        return Project::create($data);
    }

    public function update(Request $request, Project $project): Project
    {
        $data = $request->validate([
            'name'        => 'required|string|max:255',
            'description' => 'required|string',
            'content'     => 'nullable|string',
            'parent_id'   => 'nullable|integer|min:0',
            'status'      => 'nullable|boolean',
            'image'       => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'banner'      => 'nullable|image|mimes:jpg,jpeg,png,webp|max:4096',
        ]);

        $data['parent_id'] = (int) ($data['parent_id'] ?? 0);

        if ($request->hasFile('image')) {
            $this->deleteImage($project->image);
            $data['image'] = $this->uploadImage($request->file('image'), 'uploads/projects', 1200, true);
        }

        if ($request->hasFile('banner')) {
            $this->deleteImage($project->banner);
            $data['banner'] = $this->uploadImage($request->file('banner'), 'uploads/projects', 1920, true);
        }

        $project->update($data);
        return $project;
    }

    public function delete(Project $project): void
    {
        $this->deleteImage($project->image);
        $this->deleteImage($project->banner);
        $project->delete();
    }
}
