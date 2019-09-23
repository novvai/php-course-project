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
            $builder->addCollumn("contact_phone")->string(16);
            $builder->addCollumn("opened_time")->string(64)->notNull();
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
