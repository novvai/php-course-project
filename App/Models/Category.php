<?php

namespace App\Models;

use Novvai\Model\Base as BaseModel;

class Category extends BaseModel
{
    public function subCategories()
    {
        return $this->hasMany(Category::class, "id", "parent_id");
    }
    public function parent()
    {
        return $this->belongsTo(Category::class, "id", "parent_id");
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
