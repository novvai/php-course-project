<?php

namespace Migrations;

use Novvai\Schema\Base;
use Novvai\QueryBuilders\Interfaces\QueryBuilderInterface;

class ApplicationMigration extends Base
{
    public function handle()
    {
        $this->up('applications', function (QueryBuilderInterface $builder) {
            $builder->autoIncrement();
            $builder->addCollumn("name")->string(64)->notNull();
            $builder->addCollumn("token")->string(64)->notNull();
            $builder->addTimeStamps();
        });
    }
}
