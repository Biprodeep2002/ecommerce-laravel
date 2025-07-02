<?php

namespace App\Models\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $table="products";

    public $timestamps = false; 

    protected $fillable = ['name', 'price', 'category_id', 'type'];

    public function category()
    {
        return $this->belongsTo(Category::class); 
    }

    public function physicalData()
    {
        return $this->hasOne(PhysicalData::class,'id','id');
    }

    public function digitalData()
    {
        return $this->hasOne(DigitalData::class,'id','id');
    }
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class, 'product_id');
    }

}
