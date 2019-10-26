<?php

namespace App\Services;

use Novvai\Container;
use App\Models\LoginToken;

class Auth
{
    static private $instance = null;

    private $user = null;

    private function __construct()
    {
        $this->regenUser();
    }

    static public function getInstance()
    {
        return is_null(self::$instance) ?
            self::$instance = new static()
            : self::$instance;
    }

    public function user()
    {
        return $this->user;
    }

    private function regenUser()
    {
        if (isset($_SESSION["user_session"])) {
            $loginToken = Container::make(LoginToken::class);
            $token = $loginToken->where("token", $_SESSION["user_session"])->get()->first();
            $this->user = $token->user();
            unset($this->user->password);
        }
    }
}
