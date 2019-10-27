<?php

namespace Migrations;

use Novvai\Schema\Base;
use Novvai\QueryBuilders\Interfaces\QueryBuilderInterface;

class RolesMigration extends Base
{
    public function handle()
    {
        $this->up('roles', function (QueryBuilderInterface $builder) {
            $builder->autoIncrement();
            $builder->addCollumn("type")->string(255)->notNull();
            $builder->addTimeStamps();
        });
    }

    public function rollback()
    {
        $this->down(function (QueryBuilderInterface $builder) {
            $builder->drop('roles');
        });
    }
}
