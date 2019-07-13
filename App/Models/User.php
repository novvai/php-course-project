<?php
namespace App\Models;

use Novvai\Model\Base as BaseModel;

class User extends BaseModel{
    protected $visible = ['id','username', 'email'];   
}