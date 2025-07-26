<?php

namespace App\Http\Controllers;

use App\Models\Slide;
use Illuminate\Http\Request;
use App\Services\SlideService;

class SlideController extends Controller
{
    protected $slideService;

    public function __construct(SlideService $slideService)
    {
        $this->slideService = $slideService;
    }

    /**
     * Danh sách tất cả slide.
     */
    public function index()
    {
        $slides = Slide::orderBy('position')->get();
        return view('admin.slides.index', compact('slides'));
    }

    /**
     * Form tạo mới slide.
     */
    public function create()
    {
        return view('admin.slides.create');
    }

    /**
     * Lưu slide mới vào CSDL.
     */
    public function store(Request $request)
    {
        $this->slideService->create($request);

        return $request->input('action') === 'save_new'
            ? redirect()->route('admin.slides.create')->with('success', 'Đã thêm slide, tiếp tục thêm mới.')
            : redirect()->route('admin.slides.index')->with('success', 'Đã thêm slide.');
    }

    /**
     * Form chỉnh sửa slide.
     */
    public function edit(Slide $slide)
    {
        return view('admin.slides.edit', compact('slide'));
    }

    /**
     * Cập nhật slide đã tồn tại.
     */
    public function update(Request $request, Slide $slide)
    {
        $this->slideService->update($request, $slide);
        return redirect()->route('admin.slides.index')->with('success', 'Đã cập nhật slide.');
    }

    /**
     * Xoá slide.
     */
    public function destroy(Slide $slide)
    {
        $this->slideService->delete($slide);
        return redirect()->route('admin.slides.index')->with('success', 'Đã xóa slide.');
    }
}
