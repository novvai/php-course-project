<?php

namespace App\Middlewares;

use App\Services\Authenticator;
use Novvai\Middlewares\Interfaces\MiddlewareInterface;
use Novvai\Response\JsonResponse;

class AuthToken implements MiddlewareInterface{
    public function handle($request, callable $next)
    {
        $authToken = $request->headers()->get("x-li-tok");
        if(is_null($authToken)){
            return JsonResponse::make()->error(['brada']);
        }
        if(!Authenticator::make()->checkToken($authToken)){
            return JsonResponse::make()->error(['brada^2']);
        }
        return $next();
    }
}