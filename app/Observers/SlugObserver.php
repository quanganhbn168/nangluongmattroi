<?php

namespace App\Observers;

use Illuminate\Support\Str;
use App\Models\Slug;

class SlugObserver
{
    /**
     * Xử lý khi model được tạo mới (created)
     */
    public function created($model)
    {
        if ($this->shouldHandleSlug($model)) {
            $this->generateUniqueSlug($model);
        }
    }

    /**
     * Xử lý khi model được cập nhật (updated)
     */
    public function updated($model)
    {
        if ($this->shouldHandleSlug($model)) {
            $this->handleSlugUpdate($model);
        }
    }

    /**
     * Xử lý xóa slug khi model bị xóa
     */
    public function deleting($model)
    {
        if ($model->slug()->exists()) {
            $model->slug()->delete();
        }
    }

    /**
     * Kiểm tra model có cần xử lý slug không
     */
    protected function shouldHandleSlug($model): bool
    {
        // Chỉ xử lý nếu model có trường name hoặc title
        return isset($model->name) || isset($model->title);
    }

    /**
     * Xử lý cập nhật slug khi model thay đổi
     */
    protected function handleSlugUpdate($model)
    {
        $currentName = $model->name ?? $model->title;
        $originalName = $model->getOriginal('name') ?? $model->getOriginal('title');

        // Nếu name thay đổi hoặc chưa có slug
        if ($currentName !== $originalName || !$model->slug()->exists()) {
            $this->generateUniqueSlug($model);
        }
    }

    /**
     * Tạo slug duy nhất
     */
    protected function generateUniqueSlug($model)
    {
        $slugBase = Str::slug($model->name ?? $model->title);
        $slug = $slugBase;
        $i = 1;

        while ($this->slugExists($slug, $model)) {
            $slug = $slugBase . '-' . $i++;
        }

        $model->slug()->updateOrCreate([], ['slug' => $slug]);
    }

    /**
     * Kiểm tra slug đã tồn tại
     */
    protected function slugExists($slug, $model): bool
    {
        return Slug::where('slug', $slug)
            ->where(function ($query) use ($model) {
                $query->where('sluggable_type', '!=', get_class($model))
                    ->orWhere('sluggable_id', '!=', $model->id);
            })
            ->exists();
    }
}