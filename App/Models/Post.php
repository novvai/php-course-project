<?php

namespace App\Models;

use Novvai\Model\Base as BaseModel;

class Post extends BaseModel
{
    public function comments()
    {
        // return $this->hasMany(Comment::class);
    }
}