<?php

namespace App\Services;

use Novvai\Session;
use Novvai\Container;
use App\Models\LoginToken;
use App\Repositories\LoginTokenRepository;

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
        $session = Session::make();
        if ($session->has("user_session")) {
            $loginTokenRepo = new LoginTokenRepository();
            $token = $loginTokenRepo->findByToken($session->get("user_session"));
            $this->user = $token->user();
            unset($this->user->password);
        }
    }
}
