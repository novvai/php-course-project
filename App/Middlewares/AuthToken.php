<?php

namespace App\Middlewares;

use App\Services\Authenticator;
use Novvai\Middlewares\Interfaces\MiddlewareInterface;
use Novvai\Response\JsonResponse;

class AuthToken implements MiddlewareInterface
{
    public function handle($request, callable $next)
    {
        $authToken = $request->headers()->get("x-li-tok");
        if (is_null($authToken)) {
            return JsonResponse::make(403)->error([
                "code" => 4003,
                "message" => "Unauthorized!"
            ]);
        }

        // if the guard return false the given token is invalid
        if (!Authenticator::make()->guard($authToken)) {
            return JsonResponse::make(403)->error(
                [
                    "code" => 4005,
                    "message" => "Invalid authorization!"
                ]
            );
        }
        return $next();
    }
}
