<?php

namespace App\Repositories;

use App\Models\Category;

final class CategoryRepository extends Base
{ 
    protected $modelClass = Category::class;

    public function parents()
    {
        return $this->modelInstance->whereIsNull("parent_id")->get();
    }

    public function getSubCategories($id)
    {
        $category = $this->findById($id);

        return $category->subCategories();
    }

    public function create($data)
    {
        if($data["parent_id"] == "none"){unset($data["parent_id"]);}
        return $this->modelInstance->create($data);
    }
}
