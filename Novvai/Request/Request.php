<?php

namespace Novvai\Request;

class Request
{
    private static $instance = null;

    private $requestBag = [];
    private $filesBag = [];
    private $headersInstance;

    public static function getInstance()
    {
        if (is_null(self::$instance)) {
            self::$instance = new static();
        }
        return self::$instance;
    }

    private function __construct()
    {
        $this->headersInstance = new Headers();
        $this->buildRequestBag();
    }

    public function headers()
    {
        return $this->headersInstance;
    }
    /**
     * Check if the request bag has given key
     * 
     * @return bool
     */
    public function has(string $key): bool
    {
        return key_exists($key, $this->requestBag);
    }

    /**
     * Retrieves value of given key from the sent request
     * or return null if it cant find such key
     * 
     * @return null|string|array
     */
    public function get(string $key, $default = null)
    {
        if ($this->has($key)) {
            return $this->requestBag[$key];
        }
        return $default;
    }

    /**
     * Retrieves all sent information to the server
     * 
     * @return array
     */
    public function all(): array
    {
        return $this->requestBag;
    }

    /**
     * Retrieves files that have been sent
     * 
     * @return array;
     */
    public function files()
    {
        return $this->filesBag;
    }

    private function appendGETParams()
    {
        $this->requestBag = array_merge($this->requestBag, $_GET ?? []);
    }


    private function appendPOSTParams(): void
    {
        $params = empty($_POST) ? json_decode(file_get_contents("php://input"), true) ?: [] : $_POST;
        $this->requestBag = array_merge($this->requestBag, $params);
    }

    private function processFiles(): void
    {
        
        if (isset($_FILES['files'])) {
            $filesCount = count(reset($_FILES['files']));
            $file = [];
            for ($i = 0; $i < $filesCount; $i++) {
                foreach ($_FILES['files'] ?? [] as $type => $data) {
                    $file[$type] = $data[$i];
                }
                $this->filesBag[] = $file;
            }
        }else if(!empty($_FILES)){
            $this->filesBag[] = reset($_FILES);
        }
    }
    /**
     * Fill the request bag with GET and POST parameters
     */
    private function buildRequestBag(): void
    {
        $this->appendGETParams();
        $this->appendPOSTParams();
        $this->processFiles();
    }
}
