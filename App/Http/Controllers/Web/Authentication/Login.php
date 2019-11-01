<?php

namespace App\Http\Controllers\Web\Authentication;

use App\Http\Controllers\Base;
use App\Services\Authenticator;
use Novvai\Response\JsonResponse;
use Novvai\Response\Response;
use Novvai\Session;
use Novvai\Utilities\Translations\ErrorTranslator;

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
        if (is_null($result)) {
            return Response::make()
                ->withErrors(ErrorTranslator::fromCode(5000))->back();
        }
        Session::make()->add("user_session", $result->token);
        header("location: /");
    }

    /**
     * 
     */
    public function logout()
    {
        Session::make()->destroy();
        header("location: /login");
    }
}
