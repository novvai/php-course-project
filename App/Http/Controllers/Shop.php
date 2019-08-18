<?php

namespace App\Http\Controllers;

use Novvai\Container;
use App\Models\Shop as ShopModel;
use Novvai\Response\JsonResponse;
use Novvai\Model\Traits\Filterable;

class Shop extends Base
{
    use Filterable;
    public function index()
    {
        $shops = Container::make(ShopModel::class);
        $this->handleFilters($shops, $this->request->get('filters',[]));

        return JsonResponse::make()->data([
            "shops" => $shops->all()
        ]);
    }

    /**
     * Creates record
     * 
     * @return JsonResponse containing status and data object
     */
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
    /**
     * Attempts to delete shop
     * 
     * @param Integer $id
     * 
     * @return JsonResponse
     */
    public function delete($id)
    {
        $shopModel = Container::make(ShopModel::class);
        $response = JsonResponse::make();

        if ($shopModel->delete(["id" => $id])) {
            return $response->success([
                "code" => "2000",
                "message" => "Shop deleted"
            ]);
        }
        return $response->error([
            "code" => "4000",
            "message" => "Shop was not found"
        ]);
    }
}
