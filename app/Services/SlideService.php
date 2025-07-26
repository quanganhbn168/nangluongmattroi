<?php
namespace App\Services;
use App\Models\Slide;
use App\Traits\UploadImageTrait;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
class SlideService
{
    use UploadImageTrait;
    /* ---------- CREATE ---------- */
    public function create(Request $request): Slide
    {
        $data = $request->validate($this->rules());
        // Upload theo từng type
        $type = (int) $data['type'];
        if ($type === Slide::TYPE_PARTNER) {
            $data['image'] = $this->uploadImage(
                $request->file('image'),
                'uploads/slides', 350, 60, true
            );
        } elseif ($request->hasFile('image')) {
            $data['image'] = $this->uploadImage(
                $request->file('image'),
                'uploads/slides', 1920, 500, true
            );
        }
        return Slide::create($data);
    }
    /* ---------- UPDATE ---------- */
    public function update(Request $request, Slide $slide): Slide
    {
        $data = $request->validate($this->rules());
        // ----- xoá ảnh cũ nếu có -----
        $this->deleteSlideImages($slide);
        $type = (int) $data['type'];
        if ($type === Slide::TYPE_PARTNER) {
            $data['image'] = $this->uploadImage(
                $request->file('image'),
                'uploads/slides', 350, 60, true
            );
        } elseif ($request->hasFile('image')) {
            $data['image'] = $this->uploadImage(
                $request->file('image'),
                'uploads/slides', 1920, 500, true
            );
        } else {
            // nếu không up ảnh mới — giữ nguyên ảnh cũ
            unset($data['image']);
        }
        $slide->update($data);
        return $slide;
    }
    /* ---------- DELETE ---------- */
    public function delete(Slide $slide): void
    {
        $this->deleteSlideImages($slide);
        $slide->delete();
    }
    /* ---------- RULES ---------- */
    private function rules(): array
    {
        $imgRule = 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048';
        return [
            'title'         => 'required|string|max:255',
            'link'          => 'nullable|string|max:255',
            'position'      => 'nullable|integer',
            'status'        => 'nullable|boolean',
            'type'          => ['required', 'integer', Rule::in([
                Slide::TYPE_SLIDE,
                Slide::TYPE_PARTNER,
                Slide::TYPE_POPUP,
                Slide::TYPE_ADVERTISEMENT,
            ])],
            // slide thường
            'image'         => $imgRule,
            // before / after
            'before_image'  => $imgRule,
            'after_image'   => $imgRule,
        ];
    }
    /* ---------- HELPER ---------- */
    private function deleteSlideImages(Slide $slide): void
    {
        if (!$slide->image) {
            return;
        }
        // Nếu dạng json => xoá 2 file
        $img = json_decode($slide->image, true);
        if (is_array($img)) {
            $this->deleteImage($img['before'] ?? null);
            $this->deleteImage($img['after']  ?? null);
        } else {
            $this->deleteImage($slide->image);
        }
    }
}
