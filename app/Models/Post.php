<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
class Post extends Model
{
    /** @use HasFactory<\Database\Factories\PostFactory> */
    use HasFactory;
    protected $fillable = [
        'post_category_id',
        'title',
        'slug',
        'image',
        'banner',
        'description',
        'content',
        'is_featured',
        'status',
        'is_home',
    ];

    protected $casts = [
        'is_featured' => 'boolean',
        'status' => 'boolean',
        'is_home' => 'boolean',
    ];

    protected static function booted(): void
    {
        static::creating(function ($post) {
            if (empty($post->slug)) {
                $post->slug = Str::slug($post->title);
            }
        });
    }

    public function category()
    {
        return $this->belongsTo(PostCategory::class, 'post_category_id');
    }
    public function slug()
    {
        return $this->morphOne(Slug::class, 'sluggable');
    }

    public function getSlugUrlAttribute()
    {
        return url($this->slug->slug ?? '#');
    }
}
