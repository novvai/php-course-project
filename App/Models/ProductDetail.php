<?php

namespace App\Models;

use Novvai\Model\Base as BaseModel;

class ProductDetail extends BaseModel
{
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
