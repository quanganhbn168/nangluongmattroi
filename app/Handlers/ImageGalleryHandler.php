<?php

namespace App\Handlers;

use Illuminate\Http\Request;

class ImageGalleryHandler
{
    public function sync(object $model, Request $request, string $field = 'gallery', string $folder = 'uploads/gallery', int $resize = 800): void
    {
        $pathsToKeep  = $request->input("{$field}_old", []);
        $currentPaths = $model->images()->pluck('image')->toArray();
        $newImages    = $request->file($field, []);

        $nothingChanged = empty($newImages)
            && count($pathsToKeep) === count($currentPaths)
            && empty(array_diff($currentPaths, $pathsToKeep));

        if ($nothingChanged) {
            return;
        }

        // Xoá ảnh cũ không giữ lại
        $deletedPaths = array_diff($currentPaths, $pathsToKeep);
        foreach ($deletedPaths as $oldPath) {
            if (method_exists($model, 'deleteImage')) {
                $model->deleteImage($oldPath);
            }
            $model->images()->where('image', $oldPath)->delete();
        }

        // Thêm ảnh mới
        foreach ($newImages as $image) {
            if (method_exists($model, 'uploadImage')) {
                $path = $model->uploadImage($image, $folder, $resize, true);
                $model->addImage($path);
            }
        }
    }

    public function deleteAll(object $model): void
    {
        // Lấy tất cả các bản ghi ảnh của model
        $galleryImages = $model->images()->get();

        foreach ($galleryImages as $image) {
            // 1. Xóa file vật lý khỏi storage
            if (method_exists($model, 'deleteImage')) {
                $model->deleteImage($image->image);
            }
        }

        // 2. Xóa tất cả các bản ghi trong database bằng một câu lệnh
        $model->images()->delete();
    }
}
