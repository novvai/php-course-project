<?php

namespace App\Models;

use Novvai\Model\Base as BaseModel;

class User extends BaseModel
{
    protected $retrievable = ['id', 'username', 'email', 'password','role_id'];
    protected $private = ['password'];

    public function tokens()
    {
        return $this->hasMany(LoginToken::class);
    }

    public function isAdmin()
    {
        return $this->role()->type == "admin";
    }
    public function role()
    {
        return $this->belongsTo(Role::class);
    }
}
