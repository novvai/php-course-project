<?php

namespace Migrations;

use Novvai\Schema\Base;
use Novvai\QueryBuilders\Interfaces\QueryBuilderInterface;

class UserMigration extends Base
{
    public function handle()
    {
        $this->up('users', function (QueryBuilderInterface $builder) {
            $builder->autoIncrement();
            $builder->addCollumn("username")->string(64)->notNull();
            $builder->addCollumn("email")->string(64)->notNull();
            $builder->addCollumn("password")->string(255)->notNull();
            $builder->addCollumn("role_id")->integer(11)->notNull();

            $builder->addTimeStamps();
            $builder->unique('email');

            $builder->foreignCascade("role_id", "roles", "id");
        });
    }

    public function rollback()
    {
        $this->down(function (QueryBuilderInterface $builder) {
            $builder->drop('users');
        });
    }
}
