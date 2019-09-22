<?php

namespace Novvai\Model\Traits;

use Novvai\Model\Base as BaseModel;

/**
 * 
 */
trait Filterable
{

    private function handleFilters(BaseModel $model, $filters = [])
    {
        if (array_key_exists('offset', $filters)) {
            $model->paginate($filters['offset'], isset($filters['limit']) ? $filters['limit'] : 10);
        }

        return $model;
    }
}
