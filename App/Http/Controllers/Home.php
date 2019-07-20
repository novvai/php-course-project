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
        $user = Container::make(User::class);
        $users = $user->all();
        
        return JsonResponse::make()->data(['users' => $users]);
    }

    public function show($user_id)
    {
        $userModel = Container::make(User::class);

        $user = $userModel->where("id", $user_id)->get()->first();


        return JsonResponse::make()->data(['user' => $user]);
    }   

    public function create()
    {
        $request = Request::getInstance();
        $username = $request->get('username');
        $response = JsonResponse::make();
        $response->data([$username]);

        return $response;
    }
}
