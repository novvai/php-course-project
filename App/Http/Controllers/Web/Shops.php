<?php

namespace App\Http\Controllers\Web;

use App\Models\Shop;
use Novvai\Container;
use App\Http\Controllers\Base;
use Novvai\Response\Response;
use Novvai\Utilities\File;
use Novvai\Utilities\Validator\Validator;

class Shops extends Base
{
    public function index()
    {
        $shops = Container::make(Shop::class);
        Response::withTemplate('shops/index', ["shops"=>$shops->all()]);
    }
    /**
     * @param int $id
     */
    public function create()
    {
        Response::withTemplate('shops/create');
    }
    /**
     * @param int $id
     */
    public function create_process()
    {
        $info = $this->request->all();
        $validator = new Validator($info);
        $validator->validate('title', ["min" => 3]);
        $validator->validate('phone', ["min" => 9]);
        $validator->validate('work_time', ["min" => 3]);
        if($validator->failed()){
            return Response::make()->withErrors($validator->errors())->withInputs($info)->back();
        }

        $shopModel = Container::make(Shop::class);
        $shopModel->title = $info['title'];
        $shopModel->phone = $info['phone'];
        $shopModel->work_time = $info['work_time'];

        if ($files = $this->request->files()) {
            $file = reset($files);
            $fileService = File::make($file);
            $fileService->as(generate_rand_string(12))
                ->to("uploads/img/")
                ->save();
            $shopModel->thumbnail = config("app.url") . $fileService->getFilePath();
        }
        $shop = $shopModel->create();

        Response::redirect('shops');
    }
    /**
     * @param int $id
     */
    public function edit($id)
    {
        $shops = Container::make(Shop::class);
        $shop = $shops->where("id", $id)->get()->first();

        Response::withTemplate('shops/edit', [
            "shop"=>$shop
        ]);
    }
    /**
     * @param int $id
     */
    public function edit_process($id)
    {
        $shops = Container::make(Shop::class);
        $shops->where("id", $id)->get()->first()->update($this->request->all());
        Response::redirect("shops");
    }

    /**
     * @param int $id
     */
    public function delete($id)
    {
        $shops = Container::make(Shop::class);
        $shop = $shops->where("id", $id)->get()->first()->delete();

        Response::redirect("shops");
    }
}
