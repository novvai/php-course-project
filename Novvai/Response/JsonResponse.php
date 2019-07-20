<?php

namespace Novvai\Response;

use Novvai\Interfaces\Arrayable;

class JsonResponse
{
    private $data = [];
    private $errors = [];
    private $success = [];

    public function __construct()
    {
        header("Content-Type:application/json");
    }

    public static function make()
    {
        return new static();
    }

    public function data(array $data): self
    {
        $this->data = $data;
        return $this;
    }

    public function success(array $success): self
    {
        $this->success = $success;
        return $this;
    }

    public function error(array $errors): self
    {
        $this->errors = $errors;
        return $this;
    }

    public function __toString()
    {
        return json_encode($this->buildResponse());
    }

    /**
     * 
     * @return array
     */
    private function buildResponse(): array
    {
        $response = [
            'data' => $this->normalize($this->data),
            'success' => $this->normalize($this->success),
            'errors' => $this->normalize($this->errors)
        ];

        map($response, function ($item, $key) use (&$response) {
            if (is_null($item) || count($item) == 0) {
                unset($response[$key]);
            }
        });

        return $response;
    }

    private function normalize(array $data)
    {
        return map($data, function ($item) { 
            if($item instanceof Arrayable){
                return $item->toArray();
            }
            return $item;
        });
    }
}
