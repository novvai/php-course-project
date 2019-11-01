<?php

namespace App\Middlewares;

use Novvai\Middlewares\Interfaces\MiddlewareInterface;
use Novvai\Response\Response;
use Novvai\Session;

class WebAuth implements MiddlewareInterface
{
    public function handle($request, callable $next)
    {
        $session = Session::make();
        return (!$session->has("user_session")) ? Response::redirect('login') : $next();
    }
}
