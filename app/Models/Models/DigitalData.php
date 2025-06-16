<?php

namespace App\Models\Models;

use Illuminate\Database\Eloquent\Model;

class DigitalData extends Model
{
    protected $table= "digital_data";
    protected $fillable = ['id', 'filesize']; // Assuming 'id' is the product ID and 'filesize' is the digital data field
    public $timestamps = false;
}
