<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    /** @use HasFactory<\Database\Factories\ImageFactory> */
    
    use HasFactory;

    protected $fillable = [
        'item_type', 
        'item_id',
        'image',
    ];

    public function item()
    {
        return $this->morphTo();
    }
}
