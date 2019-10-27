<?php

namespace App\Models;

use Novvai\Model\Base as BaseModel;

class Product extends BaseModel
{
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function details()
    {
        return $this->hasMany(ProductDetail::class);
    }
}
