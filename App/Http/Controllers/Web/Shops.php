<?php

namespace App\Http\Controllers\Web;

use Novvai\Response\Response;
use App\Http\Controllers\Base;
use App\Repositories\ShopRepository;
use App\Validators\ShopRequestValidator;

class Shops extends Base
{
    public function index()
    {
        Response::withTemplate(
            'shops/index',
            ["shops" => (new ShopRepository())->all()]
        );
    }
    /**
     * @param int $id
     */
    public function create()
    {
        Response::withTemplate('shops/create');
    }
    /**
     * @return Response
     */
    public function processCreate()
    {
        $data = $this->request->all();
        $data['files'] = $this->request->files();
        $validator = new ShopRequestValidator($data);
        $validator->validate('files', ["required"]);

        if ($validator->failed()) {
            return Response::make()->withErrors($validator->errors())->withInputs($data)->back();
        }

        (new ShopRepository())->create($data);
        Response::redirect('shops');
    }
    /**
     * @param int $id
     */
    public function edit($id)
    {
        Response::withTemplate('shops/edit', [
            "shop" => (new ShopRepository())->findById($id)
        ]);
    }
    /**
     * @param int $id
     */
    public function processEdit($id)
    {
        $data = $this->request->all();
        $data["files"] = $this->request->files();
        $validator = new ShopRequestValidator($data);
        if ($validator->failed()) {
            return Response::make()
                ->withErrors($validator->errors())
                ->withInputs($data)
                ->back();
        }

        (new ShopRepository())->updateById($id, $data);

        Response::redirect("shops");
    }

    /**
     * @param int $id
     */
    public function delete($id)
    {
        (new ShopRepository())->deleteById($id);
        Response::redirect("shops");
    }
}
