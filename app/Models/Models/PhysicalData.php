<?php

namespace App\Models\Models;

use Illuminate\Database\Eloquent\Model;

class PhysicalData extends Model
{
    protected $table = "physical_data";
    protected $fillable = ['id', 'weight']; // Assuming 'id' is the product ID and 'weight' is the physical data field
    public $timestamps = false;
}
