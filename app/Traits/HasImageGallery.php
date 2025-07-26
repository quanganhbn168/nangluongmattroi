<?php


namespace App\Traits;

use App\Models\Image;

trait HasImageGallery
{
    public function images()
    {
        return $this->hasMany(Image::class, 'item_id')
            ->where('type', static::class);
    }

    public function addImage(string $imagePath): void
    {
        Image::create([
            'type' => static::class,
            'item_id' => $this->id,
            'image' => $imagePath,
        ]);
    }

    public function clearImages(): void
    {
        Image::where('type', static::class)
            ->where('item_id', $this->id)
            ->delete();
    }
}
