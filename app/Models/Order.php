<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Models\Product;

class Order extends Model
{
    protected $guarded = ['id'];

    public $timestamps = true;

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
