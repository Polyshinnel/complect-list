<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class SetProduct extends Model
{
    use HasFactory;

    protected $guarded = false;

    public function getProductInfo(): HasOne
    {
        return $this->hasOne(Product::class, 'product_id', 'id');
    }
}
