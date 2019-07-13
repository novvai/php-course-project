<?php

namespace App\Http\Controllers;

use Novvai\Request\Request;
use Novvai\Response\JsonResponse;
use Novvai\Container;
use App\Models\User;

class Home extends Base
{
    public function index()
    {
        $user = Container::make(User::class);
        return JsonResponse::make()->data(['users'=>$user->all()]);
    }

    public function show($user_id)
    {
        $userModel = Container::make(User::class);

        $user = $userModel->where("id", $user_id)->get();

        return JsonResponse::make()->data(['user'=>$user]);
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
