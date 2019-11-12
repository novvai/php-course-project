<?php

namespace App;

use Novvai\Response\Response;

class Handler
{
    static public function handle($ex)
    {
        if ($ex instanceof \Novvai\Router\Exceptions\NotFound) {
            Response::make(404)->withTemplate('errors/404');
            return;
        }
        Response::make(500)->withTemplate('errors/500');

    }
}
