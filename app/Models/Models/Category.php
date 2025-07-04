<?php

namespace App\Models\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $table ="categories";
    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
