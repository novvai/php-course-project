<?php

namespace Migrations;

use Novvai\Schema\Base;
use Novvai\QueryBuilders\Interfaces\QueryBuilderInterface;

class LoginTokenMigration extends Base
{
    public function handle()
    {
        $this->up('login_tokens', function (QueryBuilderInterface $builder) {
            $builder->autoIncrement();
            $builder->addCollumn("token")->string(64)->notNull();
            $builder->addCollumn("user_id")->integer(11)->notNull();
            $builder->addCollumn("expires_at")->dateTime()->notNull();
            $builder->addTimeStamps();
            
            $builder->indexed(['user_id']);

            $builder->foreignCascade("user_id", "users","id");
        });
    }

    public function rollback()
    {
        $this->down(function (QueryBuilderInterface $builder) {
            $builder->drop('login_tokens');
        });
    }
}
