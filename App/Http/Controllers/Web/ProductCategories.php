<?php

namespace App\Http\Controllers\Web;

use Novvai\Response\Response;
use App\Http\Controllers\Base;
use Novvai\Response\JsonResponse;
use App\Repositories\CategoryRepository;
use App\Validators\CategoryRequestValidator;
use Novvai\Utilities\Translations\ErrorTranslator;

class ProductCategories extends Base
{
    /**
     * Lists all parent categories
     */
    public function index()
    {
        Response::withTemplate(
            "product-categories/index",
            [
                "productCategories" => (new CategoryRepository())->parents()
            ]
        );
    }

    /**
     * Process Create request
     */
    public function getSubCategories($cat_id)
    {
        return JsonResponse::make()->success([
            "code" => 2000
        ])->payload([
            "sub_categories" => (new CategoryRepository())->getSubCategories($cat_id)
        ]);
    }

    /**
     * Process Create request
     */
    public function processCreate()
    {
        $data = $this->request->all();
        $validation = new CategoryRequestValidator($data);

        if ($validation->failed()) {
            return  Response::make()
                ->withErrors(ErrorTranslator::map($validation->errors()))
                ->withInputs($data)->back();
        }

        (new CategoryRepository())->create($data);
        return Response::make()->back();
    }

    /**
     * Process Edit request
     * @param $cat_id
     */
    public function processEdit($cat_id)
    {
        $data = $this->request->all();
        $validation = new CategoryRequestValidator($data);

        if ($validation->failed()) {
            return  Response::make()
                ->withErrors(ErrorTranslator::map($validation->errors()))
                ->withInputs($data)->back();
        }

        (new CategoryRepository())->updateById($cat_id, $data);

        return Response::make()->back();
    }

    /**
     * Process delete request
     * @param $cat_id
     */
    public function delete($cat_id)
    {
        (new CategoryRepository())->deleteById((int) $cat_id);
        return Response::make()->back();
    }
}
