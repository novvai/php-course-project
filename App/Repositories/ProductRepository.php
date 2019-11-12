<?php

namespace App\Repositories;

use App\Models\Product;

final class ProductRepository extends Base
{
    protected $modelClass = Product::class;

    public function create($data)
    {
        $this->modelInstance->name = $data['name'];
        $this->modelInstance->short_desc = $data['short_desc'];
        $this->modelInstance->description = $data['description'];
        $this->modelInstance->price = $data['price'];
        $this->modelInstance->quantity = $data['quantity'];
        $this->modelInstance->category_id =  (int) $data['category_id'];
        $this->modelInstance->is_featured =  (int) isset($data['is_featured']);
        $this->modelInstance->thumbnail = $this->processFile($data['files'] ?? []);
        $createdRecord = $this->modelInstance->create();

        return $createdRecord;
    }

    public function updateById($id, $data)
    {
        $record = $this->findById($id);
        $record->name = $data['name'];
        $record->short_desc = $data['short_desc'];
        $record->description = $data['description'];
        $record->price = $data['price'];
        $record->quantity = $data['quantity'];
        $record->category_id =  $data['category_id'];
        $record->is_featured =  (int) isset($data['is_featured']);

        if (!empty($data['files'])) {
            $record->thumbnail = $this->processFile($data['files']);
        }

        return $record->update();
    }

    /**
     * 
     */
    protected function featured($value)
    {
        $this->modelInstance->where('is_featured', $value);
    }

    protected function category($id)
    {
        $id ? $this->modelInstance->where("category_id", $id) : null;
    }

    protected function name($name)
    {
        $name ? $this->modelInstance->whereLike("name", $name) : null;
    }

    public function manageProductDetails($id, $productDetails)
    {
        $product = $this->findById($id);
        $currentProductDetails = $product->details();
        foreach ($currentProductDetails as $productDetail) {
            if (array_key_exists($productDetail->id, $productDetails)) {
                if ($productDetails[$productDetail->id]['value'] != $productDetail->value || $productDetails[$productDetail->id]['name'] != $productDetail->name) {
                    $productDetail->value = $productDetails[$productDetail->id]['value'];
                    $productDetail->name = $productDetails[$productDetail->id]['name'];
                    $productDetail->update();
                    dd("TEST _ 1", $productDetails);
                }
                dd("TEST _ 2", $productDetails);
                unset($productDetails[$productDetail->id]);
            } else {
                $productDetail->delete();
            };
        }
        $this->attachProductDetails($id, $productDetails);
    }
    public function attachProductDetails($id, $productDetails)
    {
        $productDetailRepo = new ProductDetailRepository();

        foreach ($productDetails ?? [] as $detail) {
            $detail['product_id'] = $id;
            $productDetailRepo->create($detail);
        }
    }
}
