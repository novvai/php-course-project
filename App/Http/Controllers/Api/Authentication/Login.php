<?php

namespace App\Http\Controllers\Api\Authentication;

use App\Http\Controllers\Base;
use App\Services\Authenticator;
use Novvai\Response\JsonResponse;

class Login extends Base
{
    /** 
     * @return JsonResponse;
     */
    public function process()
    {
        $credentials = $this->request->all();
        $authUser = Authenticator::make()->attempt($credentials);
        $jsonResponse = JsonResponse::make();

        if (is_null($authUser)) {
            return $jsonResponse->error([
                    "code" => 4003,
                    "message" => "Invalid username or password"
                ]);
        }

        return $jsonResponse->payload(["user" => $authUser])
            ->success([
                "code" => 2002,
                "message" => "Welcome, Authentication success"
            ]);
    }
}
