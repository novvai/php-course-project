<?php

namespace App\Services;

use App\Models\User;
use Novvai\Container;
use Novvai\Stacks\Stack;
use App\Models\LoginToken;
use Novvai\Stacks\Interfaces\Stackable;
use Novvai\Utilities\Validator\Validator;

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
     * @var Stackable
     */
    private $errors;

    /**
     * @return self
     */
    private function __construct()
    {
        $this->userModel =  Container::make(User::class);
        $this->tokenModel =  Container::make(LoginToken::class);
        $this->errors = Stack::make();
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


        $response = $this->userModel->where("email", $cred['username'])->get()->first();

        if (is_null($response)) {
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
        if (!$this->validate($cred)) {
            return $this->errors;
        }

        $this->userModel->username = $cred['username'];
        $this->userModel->email = $cred['email'];
        $this->userModel->password = password_hash($cred['password'], PASSWORD_BCRYPT);

        return $this->userModel->create();
    }

    private function validate(array $cred): bool
    {
        $validator = new Validator($cred);
        $validator->validate('username', ['min' => 3]);
        $validator->validate('email', ['min' => 3, "pattern" => ['email']]);
        $validator->validate('password', ['min' => 8]);

        if ($validator->failed()) {
            $this->errors->add($validator->errors());
            return false;
        }

        return true;
    }

    /**
     * Creates token record that is going to be used for later authentications
     * @param User $user
     * @return LoginToken
     */
    private function generateAuthentication(User $user)
    {
        $this->tokenModel->expires_at = date('Y-m-d h:i:s', strtotime(date('Y-m-d h:i:s') . " + 1 year"));
        $this->tokenModel->token = generate_rand_string(60);
        $this->tokenModel->user_id = $user->id;
        $this->tokenModel->create();
        return $this->tokenModel;
    }
}
