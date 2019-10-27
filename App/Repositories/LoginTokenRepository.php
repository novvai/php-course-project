<?php

namespace App\Repositories;

use App\Models\LoginToken;

final class LoginTokenRepository extends Base
{
    protected $modelClass = LoginToken::class;

    /**
     * Attempts to find a login token in the DB
     * @param string $token
     * 
     * @return Model|null
     */
    public function findByToken(string $token)
    {
        return $this->modelInstance->where("token", $token)->get()->first();
    }

    /**
     * Check if the user with a given token is admin
     * @return bool
     */
    public function hasPermissions(string $token): bool
    {
        $loginTok = $this->findByToken($token);
        return $loginTok ? (bool) $loginTok->user()->isAdmin() : false;
    }
}
