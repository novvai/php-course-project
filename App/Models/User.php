<?php

namespace App\Models;

use Novvai\Model\Base as BaseModel;

class User extends BaseModel
{
    protected $retrievable = ['id', 'username', 'email', 'password'];
    protected $private = ['password'];


    public function tokens()
    {
        return $this->hasMany(LoginToken::class);
    }
}
