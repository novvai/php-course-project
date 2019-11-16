<?php

namespace App\Http\Controllers\Web;

use Novvai\Response\Response;
use App\Http\Controllers\Base;
use App\Repositories\CategoryRepository;
use App\Repositories\ProductRepository;
use App\Validators\ProductRequestValidation;
use Novvai\Utilities\Translations\ErrorTranslator;

class Product extends Base
{
    /**
     * Lists all posts
     */
    public function index()
    {
        $products = (new ProductRepository())->applyFilters($this->request->get('filters', []))->paginate($this->request->get('page'), 10);
        $categories = (new CategoryRepository())->allWithSubCategories();

        Response::withTemplate("products/index", ["products" => $products->get(),"productsPagination"=>$products, "categories" => $categories]);
    }
    /**
     * Lists all posts
     */
    public function view($id)
    {
        Response::withTemplate("products/detail", ["product" => (new ProductRepository())->findById($id)]);
    }

    /**
     * Lists all posts
     */
    public function create()
    {
        $categories = (new CategoryRepository())->parents();
        Response::withTemplate("products/create", [
            "categories" => $categories
        ]);
    }

    /**
     * Lists all posts
     */
    public function processCreate()
    {
        $data = $this->request->all();
        $data['files'] = $this->request->files();

        $validation = new ProductRequestValidation($data);

        if ($validation->failed()) {
            return  Response::make()
            ->withErrors(ErrorTranslator::map($validation->errors()))
            ->withInputs($data)->back();
        }

        $productRepo = new ProductRepository();

        $product = $productRepo->create($data);
        
        $productRepo->manageProductDetails($product->first()->id, $data['additional']);

        return Response::make()->withSuccessMsg("Продукта беше добавен успешно.")->redirect("products");
    }

    /**
     * Creates edit page
     * @param $id
     * @return Response 
     */
    public function edit($id)
    {
        return Response::withTemplate('products/edit', [
            "product" => (new ProductRepository())->findById($id),
            "categories" => (new CategoryRepository())->parents()
        ]);
    }
    /**
     * Process update request
     * 
     * @param int $id
     * 
     * @return Response
     */
    public function processEdit($id)
    {
        $data = $this->request->all();
        $data['files'] = $this->request->files();

        $validation = new ProductRequestValidation($data);
        
        if ($validation->failed()) {
            return  Response::make()
            ->withErrors(ErrorTranslator::map($validation->errors()))
            ->withInputs($data)->back();
        }

        $productRepo = new ProductRepository();

        $productRepo->updateById($id, $data);
        $productRepo->manageProductDetails($id, $data['additional']);
        
        return Response::make()->withSuccessMsg("Продукта беше редактиран успешно.")->redirect("products");
    }

    /**
     * Deletes post from DB
     * @param $id
     * 
     * @return Response
     */
    public function delete($id)
    {
        (new ProductRepository())->deleteById($id);
        return Response::make()->withSuccessMsg("Продукта беше изтрит успешно.")->redirect("products");
    }
}
