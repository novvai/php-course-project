<?php

namespace App\Http\Controllers;

use App\Models\User;
use Novvai\Container;
use Novvai\Request\Request;
use Novvai\Response\JsonResponse;

class Home extends Base
{
    public function index()
    {
        $tok = Container::make(User::class);
        $users = $tok->get()->first();
        $tokens = $users->tokens()->first();
        
        return JsonResponse::make()->payload(['tokens' => $tokens]);
    }

    public function show($user_id)
    {
        $userModel = Container::make(User::class);

        $user = $userModel->where("id", $user_id)->get()->first();
        

        return JsonResponse::make()->payload(['user' => $user]);
    }   

    public function create()
    {
        $request = Request::getInstance();
        $username = $request->get('username');
        $response = JsonResponse::make();
        $response->payload([$username]);

        return $response;
    }
}
