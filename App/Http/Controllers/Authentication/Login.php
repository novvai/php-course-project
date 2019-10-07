<?php

namespace App\Http\Controllers\Authentication;

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

        $response = JsonResponse::make();
        if (is_null($authUser)) {
            return $response->error([
                "code" => 4003,
                "message" => "User Not Found"
            ]);
        }

        return $response->payload(["user" => $authUser])->success([
            "code" => 2002,
            "message" => "Welcome, Authentication success"
        ]);
    }
}
