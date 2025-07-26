<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Intro extends Model
{
    /** @use HasFactory<\Database\Factories\IntroFactory> */
    use HasFactory;
    protected $fillable = [
        'title',
        'description',
        'content',
        'image',
        'banner',
        'status',
    ];

    protected $casts = [
        'status' => 'boolean',
    ];
}
