<?php

namespace App\Services;

use App\Models\User;
use Novvai\Container;
use App\Models\LoginToken;
use Novvai\Stacks\Interfaces\Stackable;

class Authenticator
{
    /**
     * @var User
     */
    private $userModel;
    /**
     * @var LoginToken
     */
    private $tokenModel;
    /**
     * @return self
     */
    private function __construct()
    {
        $this->userModel =  Container::make(User::class);
        $this->tokenModel =  Container::make(LoginToken::class);
    }

    /**
     * @return Authenticator
     */
    public static function make()
    {
        return new self;
    }

    /**
     * @return 
     */
    public function attempt(array $cred)
    {
        if (!$this->validate($cred)) {
            return null;
        }

        $response = $this->userModel->where("email", $cred['username'])->get()->first();
        
        if(is_null($response)){
            return null;
        }

        if (password_verify($cred['password'], $response->password)) {
            return $this->generateAuthentication($response);
        }
        
        return null;
    }

    public function checkToken(string $token)
    {
        $token = $this->tokenModel->where('token', $token)->get()->first();
        return !is_null($token);    
    }

    /**
     * @param array $cred
     * 
     * @return Novvai\Stacks\Stack
     */
    public function create(array $cred): Stackable
    {
        $this->userModel->username = $cred['username'];
        $this->userModel->email = $cred['email'];
        $this->userModel->password = password_hash($cred['password'], PASSWORD_BCRYPT);

        return $this->userModel->create();
    }

    private function validate(array $cred)
    {
        return true;
    }

    /**
     * Creates token record that is going to be used for later authentications
     * @param User $user
     * @return LoginToken
     */
    private function generateAuthentication(User $user)
    {
        $loginTok = Container::make(LoginToken::class);
        $loginTok->expires_at = date('Y-m-d h:i:s', strtotime(date('Y-m-d h:i:s')." + 1 year"));
        $loginTok->token = generate_rand_string(60);
        $loginTok->user_id = $user->id;
        $loginTok->create();
        return $loginTok;
    }
}
