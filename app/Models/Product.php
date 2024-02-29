<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $guarded = ['id'];

    public $timestamps = true;

    public function order()
    {
        return $this->hasOne(Order::class);
    }
}
