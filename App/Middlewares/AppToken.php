<?php

namespace App\Middlewares;

use Novvai\Middlewares\Interfaces\MiddlewareInterface;
use Novvai\Response\JsonResponse;
use Novvai\Container;
use App\Models\Application;

class AppToken implements MiddlewareInterface
{
    public function handle($request, callable $next)
    {
        $applications = Container::make(Application::class);
        $applicationToken = $request->headers()->get('nv-application');
        $application = $applications->where("token", $applicationToken)->get()->first();
        
        if (is_null($application)) {
            return JsonResponse::make()->error([
                [
                    'code' => '4003',
                    'message' => 'Invalid Application',
                ]
            ]);
        }

        return $next();
    }
}
