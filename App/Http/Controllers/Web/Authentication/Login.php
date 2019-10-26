<?php

namespace App\Http\Controllers\Web\Authentication;

use App\Http\Controllers\Base;
use App\Services\Authenticator;
use Novvai\Response\JsonResponse;

class Login extends Base
{
    /** 
     * 
     */
    public function index()
    {
        include_once load_template("login/index");
    }

    /** 
     * @return Redirect;
     */
    public function process()
    {
        $authenticator = Authenticator::make();
        $result = $authenticator->attempt($this->request->all());
        if(is_null($result)){
            return header("location: /login");
        }
        $_SESSION["user_session"] = $result->token;
        
        header("location: /");
    }

    /**
     * 
     */
    public function logout()
    {
        session_destroy();
        unset($_SESSION);
        header("location: /login");
    }
}
