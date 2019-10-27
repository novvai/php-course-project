<?php

namespace Migrations;

use Novvai\Schema\Base;
use Novvai\QueryBuilders\Interfaces\QueryBuilderInterface;

class ProductsMigration extends Base
{
    public function handle()
    {
        $this->up('products', function (QueryBuilderInterface $builder) {
            $builder->autoIncrement();
            $builder->addCollumn("name")->string(64)->notNull();
            $builder->addCollumn("thumbnail")->string(64)->notNull();
            $builder->addCollumn("short_desc")->string(255)->notNull();
            $builder->addCollumn("description")->text()->notNull();
            $builder->addCollumn("price")->decimal(5,2)->notNull();
            $builder->addCollumn("quantity")->integer(11)->default(0);
            $builder->addCollumn("category_id")->integer(11)->notNull();
            $builder->addCollumn("is_featured")->boolean()->default(0);
            $builder->addTimeStamps();
            
            $builder->indexed(['category_id']);

            $builder->foreignCascade("category_id", "categories","id");
        });
    }

    public function rollback()
    {
        $this->down(function (QueryBuilderInterface $builder) {
            $builder->drop('products');
        });
    }
}
