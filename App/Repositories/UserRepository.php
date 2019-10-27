<?php

namespace App\Repositories;

use App\Models\User;

final class UserRepository extends Base
{
    protected $modelClass = User::class;
}
