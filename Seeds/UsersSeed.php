<?php

namespace Seeds;

use App\Models\User;
use Novvai\Container;

class UsersSeed
{
    public function handle()
    {
        $roleModel = Container::make(User::class);
        $roleModel->create([
            "username" => "Admin Doe",
            "email" => "admin@ue-varna.bg",
            "password" => password_hash("test123456", PASSWORD_BCRYPT),
            "role_id" => 1
        ]);
        $roleModel->create([
            "username" => "User Doe",
            "email" => "user@ue-varna.bg",
            "password" => password_hash("test123456", PASSWORD_BCRYPT),
            "role_id" => 2
        ]);
    }
}
