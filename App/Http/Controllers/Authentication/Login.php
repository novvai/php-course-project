<?php

namespace App\Http\Controllers\Authentication;

use App\Models\User;
use Novvai\Container;
use App\Http\Controllers\Base;
use App\Services\Authenticator;
use Novvai\Response\JsonResponse;

class Login extends Base
{
    public function process()
    {
        $credentials = $this->request->all();
        $authUser = Authenticator::make()->attempt($credentials);
        // Authenticator::make()->check('tok');
        return JsonResponse::make()->payload(["user"=>$authUser])->success([
            "code"=>2002,
            "message"=>"Welcome, Authentication success"
        ]);
    }    
}
