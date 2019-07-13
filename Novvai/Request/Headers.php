<?php

namespace Novvai\Request;

class Headers
{
    const HTTP_PATTERN = 'HTTP_';
    private $requestHeaders = [];

    public function __construct()
    {
        $this->extractRequestHeaders();
    }

    /**
     * Retrieves all request headers
     * 
     * @return array
     */
    public function all(): array
    {
        return $this->requestHeaders;
    }

    /**
     * @param string $key
     * 
     * @return string|null
     */
    public function get(string $key)
    {
        return isset($this->requestHeaders[$key]) ? $this->requestHeaders[$key] : null;
    }

    /**
     * Extracts the HTTP request headers
     * from $_SERVER global variable
     * 
     * @return void
     */
    private function extractRequestHeaders(): void
    {
        map($_SERVER, function ($value, $header) {
            if (preg_match('/' . Headers::HTTP_PATTERN . '/', $header)) {
                $header = static::formatHeaderName($header);
                $this->requestHeaders = array_merge($this->requestHeaders, [$header => $value]);
            }
        });
    }

    /**
     * Applies basic string transformations
     * 
     * @param string
     * @return string
     */
    private static function formatHeaderName(string $header):string
    {
        $formatted = str_replace(Headers::HTTP_PATTERN, '', $header);
        $formatted = str_replace("_", '-', $formatted);

        return strtolower($formatted);
    }
}
