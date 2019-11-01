<?php

namespace App\Repositories;

use App\Models\Post;

final class PostRepository extends Base
{
    protected $modelClass = Post::class;

    public function create($data)
    {
        $this->modelInstance->title = $data['title'];
        $this->modelInstance->author = $data['author'];
        $this->modelInstance->content = $data['content'];
        $this->modelInstance->is_featured =  (int) isset($data['is_featured']);
        $this->modelInstance->thumbnail = $this->processFile($data['files'] ?? []);

        return $this->modelInstance->create();
    }

    public function updateById($id, $data)
    {
        $record = $this->findById($id);
        $record->title = $data['title'];
        $record->author = $data['author'];
        $record->content = $data['content'];
        $record->is_featured = (int) isset($data['is_featured']);

        if (!empty($data['files'])) {
            $record->thumbnail = $this->processFile($data['files']);
        }

        return $record->update();
    }

    public function allBy($filters = [])
    {
        $filters = is_array($filters) ? $filters : [];
        foreach ($filters as $filter => $args) {
            $this->{$filter}($args);
        }
        return $this->modelInstance->get();
    }

    /**
     * 
     */
    private function featured($value)
    {
        $this->modelInstance->where('is_featured', $value);
    }
}
