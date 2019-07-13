<?php

namespace App\Middlewares;

use Novvai\Middlewares\Interfaces\MiddlewareInterface;
use Novvai\Response\JsonResponse;

class AuthToken implements MiddlewareInterface{
    public function handle($request, callable $next)
    {
        if ($request->headers()->get('nv-application') != 99) {
            return JsonResponse::make()->error([
                'code'=>'5000',
                'message'=>'Unrecognized application'
            ]);
        }

        return $next();
    }
}