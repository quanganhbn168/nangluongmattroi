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
    public function parent()
    {
        return $this->belongsTo(self::class, 'parent_id');
    }
    /**
     * Hàm đệ quy để lấy ID của TẤT CẢ các danh mục con (con, cháu, chắt...).
     * @return array Mảng chứa ID của các danh mục con.
     */
    public function getAllDescendantIds()
    {
        $descendantIds = $this->children()->pluck('id')->toArray();
        foreach ($this->children as $child) {
            $descendantIds = array_merge($descendantIds, $child->getAllDescendantIds());
        }
        return $descendantIds;
    }
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