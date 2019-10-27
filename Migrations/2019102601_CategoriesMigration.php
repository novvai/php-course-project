<?php

namespace Migrations;

use Novvai\Schema\Base;
use Novvai\QueryBuilders\Interfaces\QueryBuilderInterface;

class CategoriesMigration extends Base
{
    public function handle()
    {
        $this->up('categories', function (QueryBuilderInterface $builder) {
            $builder->autoIncrement();
            $builder->addCollumn("name")->string(64)->notNull();
            $builder->addCollumn("parent_id")->integer(11);
            $builder->addTimeStamps();
            
            $builder->indexed(['parent_id']);

            $builder->foreignCascade("parent_id", "categories","id");
        });
    }

    public function rollback()
    {
        $this->down(function (QueryBuilderInterface $builder) {
            $builder->drop('categories');
        });
    }
}
