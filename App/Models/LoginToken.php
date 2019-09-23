<?php

namespace App\Models;

use Novvai\Model\Base as BaseModel;

class LoginToken extends BaseModel
{

    protected $private = ['id', 'created_at','updated_at','user_id'];
    /**
     * Foreign key
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
