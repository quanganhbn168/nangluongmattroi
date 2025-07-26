<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use App\Traits\HasImageGallery;
use App\Traits\UploadImageTrait;

class Product extends Model
{
    /** @use HasFactory<\Database\Factories\ProductFactory> */
    use HasFactory, HasImageGallery, UploadImageTrait;
    protected $fillable = [
        'category_id',
        'name',
        'code',
        'slug',
        'image',
        'banner',
        'sm',
        'll',
        'price', 
        'price_discount',
        'description',
        'content',
        'specifications',
        'is_home',
        'is_featured',
        'is_on_sale',
        'meta_des',
        'meta_key',
        'meta_image',
        'status',
    ];

    protected $casts = [
        'price' => 'float',
        'price_discount' => 'float',
        'status' => 'boolean',
        'is_home' => 'boolean',
        'is_featured' => 'boolean',
        'is_on_sale' => 'boolean',
    ];


    public function category()
    {
        return $this->belongsTo(Category::class);
    }
    public function slug()
    {
        return $this->morphOne(Slug::class, 'sluggable');
    }

    public function getSlugUrlAttribute()
    {
        return url($this->slug->slug ?? '#');
    }

    public function attributes() {
        return $this->belongsToMany(Attribute::class)
                    ->withPivot('value') 
                    ->withTimestamps();
    }
    
    public function variants(): HasMany
    {
        return $this->hasMany(ProductVariant::class);
    }
    public function images()
    {
        return $this->morphMany(Image::class, 'item');
    }

}
