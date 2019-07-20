<?php

namespace App\Http\Controllers;

use Novvai\Container;
use Novvai\Request\Request;
use App\Models\Shop as ShopModel;
use Novvai\Response\JsonResponse;

class Shop extends Base
{
    public function create()
    {
        $info = $this->request->all();
        $shopModel = Container::make(ShopModel::class);
        $shopModel->title = $info['title'];
        $shopModel->contact_phone = $info['contact_phone'];
        $shopModel->opened_time = $info['opened_time'];
        $shop = $shopModel->create();


        return JsonResponse::make()->data([
            "shop" => $shop
        ])->success([
            "code" => "200",
            "message" => "shop created"
        ]);
    }

    public function delete($id)
    {
        $shopModel = Container::make(ShopModel::class);
        $shop = $shopModel->delete(["id"=>$id]);

        return JsonResponse::make()->success([
            "code" => "200",
            "message" => "shop deleted"
        ]);
    }
}
