<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Unit extends Model
{
    protected $fillable = ['name', 'slug', 'status'];

    public function services()
    {
        return $this->hasMany(Service::class);
    }
}
