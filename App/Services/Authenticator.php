<?php

namespace App\Services;

use App\Models\User;
use Novvai\Container;
use Novvai\Stacks\Interfaces\Stackable;

class Authenticator
{

    /**
     * @return self
     */
    private function __construct()
    {
        $this->model =  Container::make(User::class);
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

        $response = $this->model->where("email", $cred['username'])->get()->first();
        
        if(is_null($response)){
            return null;
        }

        if (password_verify($cred['password'], $response->password)) {
            return $this->generateAuthentication($response);
        }
        
        return null;
    }

    /**
     * @param array $cred
     * 
     * @return Novvai\Stacks\Stack
     */
    public function create(array $cred): Stackable
    {
        $this->model->username = $cred['username'];
        $this->model->email = $cred['email'];
        $this->model->password = password_hash($cred['password'], PASSWORD_BCRYPT);

        return $this->model->create();
    }

    private function validate(array $cred)
    {
        return true;
    }
}
