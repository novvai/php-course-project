<?php

namespace Migrations;

use Novvai\Schema\Base;
use Novvai\QueryBuilders\Interfaces\QueryBuilderInterface;

class PostsMigration extends Base
{
    public function handle()
    {
        $this->up('posts', function (QueryBuilderInterface $builder) {
            $builder->autoIncrement();
            $builder->addCollumn("thumbnail")->string(255)->notNull();
            $builder->addCollumn("title")->string(64)->notNull();
            $builder->addCollumn("author")->string(64)->notNull();
            $builder->addCollumn("content")->text()->notNull();
            $builder->addCollumn("is_featured")->boolean()->default(0);
            $builder->addTimeStamps();
        });
    }

    public function rollback()
    {
        $this->down(function (QueryBuilderInterface $builder) {
            $builder->drop('posts');
        });
    }
}
