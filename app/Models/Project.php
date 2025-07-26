<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'parent_id',
        'description',
        'content',
        'status',
        'is_home',
        'image',
        'banner',
    ];

    public function parent()
    {
        return $this->belongsTo(Project::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Project::class, 'parent_id');
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
