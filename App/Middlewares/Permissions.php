<?php

namespace App\Middlewares;

use Novvai\Session;
use Novvai\Response\Response;
use App\Repositories\LoginTokenRepository;
use Novvai\Middlewares\Interfaces\MiddlewareInterface;

class Permissions implements MiddlewareInterface
{
    public function handle($request, callable $next)
    {
        $loginToken = new LoginTokenRepository();
        $session = Session::make();
        return (!$loginToken->hasPermissions($session->get("user_session"))) ? Response::redirect('logout') : $next();
    }
}
