<?php

namespace App\Http\Controllers;

use Novvai\Container;
use Novvai\Utilities\File;
use App\Models\Shop as ShopModel;
use Novvai\Response\JsonResponse;
use Novvai\Model\Traits\Filterable;

class Shop extends Base
{
    use Filterable;

    public function index()
    {
        $shops = Container::make(ShopModel::class);
        $this->handleFilters($shops, $this->request->get('filters', []));

        return JsonResponse::make()->payload([
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
        $shopModel->phone = $info['phone'];
        $shopModel->work_time = $info['work_time'];

        if ($files = $this->request->files()) {
            $file = reset($files);
            $fileService = File::make($file);
            $fileService->as(generate_rand_string(12))
                ->to("uploads/img/")
                ->save();
            $shopModel->thumbnail =config("app.url").$fileService->getFilePath();
        }
        $shop = $shopModel->create();
        
        $response = JsonResponse::make();

        if($shop->has("errors")){
            return $response->errors($shop->get('errors'));
        }

        return $response->payload([
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
