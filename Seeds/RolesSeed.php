<?php
namespace Seeds;

use App\Models\Role;
use Novvai\Container;

class RolesSeed{
    public function handle()
    {
        $roleModel = Container::make(Role::class);
        $roleModel->create(['type'=>'admin']);
        $roleModel->create(['type'=>'user']);
    }
}