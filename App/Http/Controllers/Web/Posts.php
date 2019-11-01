<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Base;
use App\Repositories\PostRepository;
use App\Validators\PostRequestValidation;
use Novvai\Response\Response;
use Novvai\Utilities\Translations\ErrorTranslator;

class Posts extends Base
{
    /**
     * Lists all posts
     */
    public function index()
    {
        $postsRepo = new PostRepository();
        $posts = $postsRepo->allBy($this->request->get('filters', []));

        Response::withTemplate("posts/index", ["posts" => $posts]);
    }
    /**
     * Lists all posts
     */
    public function view($id)
    {
        Response::withTemplate("posts/detail", ["post" => (new PostRepository())->findById($id)]);
    }

    /**
     * Lists all posts
     */
    public function create()
    {
        Response::withTemplate("posts/create");
    }

    /**
     * Lists all posts
     */
    public function processCreate()
    {
        $data = $this->request->all();
        $data['files'] = $this->request->files();

        $validation = new PostRequestValidation($data);
        $validation->validate('files', ['required']);

        if ($validation->failed()) {
            return  Response::make()
                ->withErrors(ErrorTranslator::map($validation->errors()))
                ->withInputs($data)->back();
        }

        $postRepo = new PostRepository();
        $postRepo->create($data);

        return Response::redirect("posts");
    }

    /**
     * Creates edit page
     * @param $id
     * @return Response 
     */
    public function edit($id)
    {
        return Response::withTemplate('posts/edit', [
            "post" => (new PostRepository())->findById($id)
        ]);
    }
    /**
     * Lists all posts
     */
    public function processEdit($id)
    {
        $data = $this->request->all();
        $data['files'] = $this->request->files();

        $validation = new PostRequestValidation($data);

        if ($validation->failed()) {
            return  Response::make()
                ->withErrors(ErrorTranslator::map($validation->errors()))
                ->withInputs($data)->back();
        }

        $postRepo = new PostRepository();
        $postRepo->updateById($id, $data);

        return Response::redirect("posts");
    }

    /**
     * Deletes post from DB
     * @param $id
     * 
     * @return Response
     */
    public function delete($id)
    {
        (new PostRepository())->deleteById($id);
        return Response::redirect("posts");
    }
}
