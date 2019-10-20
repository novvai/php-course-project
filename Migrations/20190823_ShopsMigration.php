<?php

namespace Migrations;

use Novvai\Schema\Base;
use Novvai\QueryBuilders\Interfaces\QueryBuilderInterface;

class ShopsMigration extends Base
{
    public function handle()
    {
        $this->up('shops', function (QueryBuilderInterface $builder) {
            $builder->autoIncrement();
            $builder->addCollumn("title")->string(64)->notNull();
            $builder->addCollumn("thumbnail")->string(64);
            $builder->addCollumn("phone")->string(16);
            $builder->addCollumn("work_time")->string(64)->notNull();
            $builder->addTimeStamps();
        });
    }

    public function rollback()
    {
        $this->down(function (QueryBuilderInterface $builder) {
            $builder->drop('shops');
        });
    }
}
