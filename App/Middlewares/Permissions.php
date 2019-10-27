<?php

namespace App\Middlewares;

use App\Models\LoginToken;
use App\Repositories\LoginTokenRepository;
use Novvai\Container;
use Novvai\Middlewares\Interfaces\MiddlewareInterface;
use Novvai\Response\Response;

class Permissions implements MiddlewareInterface
{
    public function handle($request, callable $next)
    {
        $loginToken = new LoginTokenRepository();
        return (!$loginToken->hasPermissions($_SESSION["user_session"])) ? Response::redirect('logout') : $next();
    }
}
