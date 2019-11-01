<?php

namespace App\Repositories;

use App\Models\Shop;
use Novvai\Utilities\File;

final class ShopRepository extends Base
{
    protected $modelClass = Shop::class;


    public function create($data)
    {
        $this->modelInstance->title = $data['title'];
        $this->modelInstance->phone = $data['phone'];
        $this->modelInstance->work_time = $data['work_time'];
        $this->modelInstance->thumbnail = $this->processFile($data['files'] ?? []);

        return $this->modelInstance->create();
    }
    /** */
    public function updateById($id, $data)
    {
        $shop = $this->findById($id);
        $shop->title = $data['title'];
        $shop->phone = $data['phone'];
        $shop->work_time = $data['work_time'];
        if($data['files']){
            $shop->thumbnail = $this->processFile($data['files'] ?? []);
        }
        return $shop->update();
    }
}
