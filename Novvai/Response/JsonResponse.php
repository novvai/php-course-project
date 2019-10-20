<?php

namespace Novvai\Response;

use Novvai\Interfaces\Arrayable;

class JsonResponse
{
    private $payload = [];
    private $errors = [];
    private $success = [];

    public function __construct(int $code)
    {
        header("Content-Type:application/json");
        $this->code($code);
    }

    public static function make(int $code = 200)
    {
        return new static($code);
    }

    /** 
     * Sets Http Response code
     * 
     * @param int $code
     * 
     * @return self
     */
    public function code(int $code): self
    {
        http_response_code($code);
        return $this;
    }

    /**
     * Payload setter
     *  
     * @param array $payload
     * 
     * @return self
     */
    public function payload(array $payload): self
    {
        $this->payload = $payload;
        return $this;
    }

    /**
     * Success setter
     *  
     * @param array $success
     * 
     * @return self
     */
    public function success(array $success): self
    {
        $this->success = $success;
        return $this;
    }

    /**
     * Error setter
     *  
     * @param array $errors
     * 
     * @return self
     */
    public function error(array $errors): self
    {
        $this->errors = $this->wrap($errors);

        return $this;
    }

    /**
     * @inheridocs
     * 
     * @return string
     */
    public function __toString()
    {
        return json_encode($this->buildResponse());
    }

    /**
     * @return array
     */
    private function buildResponse(): array
    {
        $response = [
            'payload' => $this->normalize($this->payload),
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

    /**
     * Checks if the $data is associative array 
     * if it is wraps it in array
     * 
     * @param array $data
     * 
     * @return array
     */
    private function wrap(array $data)
    {
        if (array_keys($data) !== range(0, count($data) - 1)) {
            return [$data];
        }

        return $data;
    }

    private function normalize(array $data)
    {
        return map($data, function ($item) {
            if ($item instanceof Arrayable) {
                return $item->toArray();
            }
            return $item;
        });
    }
}
