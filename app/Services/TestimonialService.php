<?php

namespace App\Services;

use App\Models\Testimonial;
use Illuminate\Http\Request;
use App\Traits\UploadImageTrait;

class TestimonialService
{
    use UploadImageTrait;

    public function getAll()
    {
        return Testimonial::latest()->paginate(20);
    }

    public function create(Request $request): Testimonial
    {
        $data = $request->validate([
            'name'      => 'required|string|max:255',
            'position'  => 'nullable|string|max:255',
            'content'   => 'required|string',
            'status'    => 'nullable|boolean',
            'image'     => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        if ($request->hasFile('image')) {
            $data['image'] = $this->uploadImage($request->file('image'), 'uploads/testimonials', 500, 500, true);
        }

        return Testimonial::create($data);
    }

    public function update(Request $request, Testimonial $testimonial): Testimonial
    {
        $data = $request->validate([
            'name'      => 'required|string|max:255',
            'position'  => 'nullable|string|max:255',
            'content'   => 'required|string',
            'status'    => 'nullable|boolean',
            'image'     => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        if ($request->hasFile('image')) {
            $this->deleteImage($testimonial->image);
            $data['image'] = $this->uploadImage($request->file('image'), 'uploads/testimonials', 500, 500, true);
        }

        $testimonial->update($data);
        return $testimonial;
    }

    public function delete(Testimonial $testimonial): bool
    {
        $this->deleteImage($testimonial->image);
        return $testimonial->delete();
    }
}
