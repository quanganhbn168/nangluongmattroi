<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
class Category extends Model
{
    /** @use HasFactory<\Database\Factories\CategoryFactory> */
    use HasFactory;
    protected $fillable = [
        'parent_id',
        'name',
        'slug',
        'image',
        'banner',
        'status',
        'is_home',
        'is_menu',
        'is_footer',
        'meta_des',
        'meta_key',
        'meta_image'
    ];

    protected $casts = [
        'status' => 'boolean',
        'is_home' => 'boolean',
        'is_menu' => 'boolean',
        'is_footer' => 'boolean',
        'parent_id' => 'integer',
    ];

    // Quan hệ: Danh mục cha
    public function parent()
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    // Quan hệ: Danh mục con
    public function children()
    {
        return $this->hasMany(self::class, 'parent_id');
    }

    public function slug()
    {
        return $this->morphOne(\App\Models\Slug::class, 'sluggable');
    }

    public function getSlugUrlAttribute()
    {
        return url($this->slug->slug ?? '#');
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
