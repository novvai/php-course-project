<?php

namespace App\Http\Controllers\Authentication;

use App\Models\User;
use Novvai\Container;
use App\Http\Controllers\Base;
use App\Services\Authenticator;
use Novvai\Response\JsonResponse;

class Register extends Base
{
    public function process()
    {
        $authService = Authenticator::make();
        $credentials = $this->request->all();

        $response = $authService->create($credentials);
        $jsonResponse = JsonResponse::make();

        if ($errors = $response->get('errors')){
            return $jsonResponse->error($errors);
        }
        
        return $jsonResponse->success([
            "code" => 2001,
            "message" => "User : {$response->first()->username} has been registered"
        ]);
    }
}
