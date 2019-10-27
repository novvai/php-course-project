<?php

namespace Migrations;

use Novvai\Schema\Base;
use Novvai\QueryBuilders\Interfaces\QueryBuilderInterface;

class ProductDetailsMigration extends Base
{
    public function handle()
    {
        $this->up('product_details', function (QueryBuilderInterface $builder) {
            $builder->autoIncrement();
            $builder->addCollumn("name")->string(64)->notNull();
            $builder->addCollumn("value")->string(64)->notNull();
            $builder->addCollumn("product_id")->integer(11)->notNull();
            $builder->addTimeStamps();

            $builder->indexed(['product_id']);

            $builder->foreignCascade("product_id", "products", "id");
        });
    }

    public function rollback()
    {
        $this->down(function (QueryBuilderInterface $builder) {
            $builder->drop('product_details');
        });
    }
}
