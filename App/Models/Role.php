<?php
namespace App\Models;

use Novvai\Model\Base as BaseModel;

class Role extends BaseModel{
    public function users()
    {
        return $this->hasMany(User::class);
    }
}