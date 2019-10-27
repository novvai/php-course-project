<?php

namespace Migrations;

use Novvai\Schema\Base;
use Novvai\QueryBuilders\Interfaces\QueryBuilderInterface;

class CommentsMigration extends Base
{
    public function handle()
    {
        $this->up('comments', function (QueryBuilderInterface $builder) {
            $builder->autoIncrement();
            $builder->addCollumn("name")->string(64)->notNull();
            $builder->addCollumn("email")->string(64)->notNull();
            $builder->addCollumn("commsent")->text()->notNull();
            $builder->addCollumn("post_id")->integer(11)->notNull();
            $builder->addTimeStamps();

            $builder->indexed(['post_id']);

            $builder->foreignCascade("post_id", "posts", "id");
        });
    }

    public function rollback()
    {
        $this->down(function (QueryBuilderInterface $builder) {
            $builder->drop('comments');
        });
    }
}
