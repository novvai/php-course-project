<?php

namespace App\Repositories;

use Novvai\Container;
use Novvai\Model\Base as NovvaiBase;
use Novvai\Utilities\File;

abstract class Base
{
    /** 
     * @var NovvaiBase
     */
    protected $modelInstance;
    protected $modelClass;

    public function __construct()
    {
        $this->modelInstance = Container::make($this->modelClass);
    }

    public function all()
    {
        return $this->modelInstance->all();
    }

    public function create($data)
    {
        return $this->modelInstance->create($data);
    }

    /**
     * @param int $id
     * @return Model
     */
    public function findById($id): NovvaiBase
    {
        return $this->modelInstance->where("id", $id)->get()->first();
    }

    /**
     * @param int $id
     * @return Model
     */
    public function updateById($id, $data)
    {
        return $this->findById($id)->update($data);
    }

    /**
     * @param int $id
     * @return Model
     */
    public function deleteById($id)
    {
        return $this->findById($id)->delete();
    }

    /**
     * Uploads file on the server and add the path to the file
     * @param array $files
     * 
     * @return array
     */
    protected function processFile($files)
    {
        $path = null;
        if (isset($files) && !empty($files)) {
            $fileService = File::make(reset($files));
            $fileService->as(generate_rand_string(12))->to("uploads/img/")->save();
            $path = config("app.url") . $fileService->getFilePath();
        }

        return $path;
    }
}
