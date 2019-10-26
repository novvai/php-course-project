<?php

namespace Novvai\Utilities\Validator;

class Validator
{
    /**
     * Currentry validation key in the data set
     * @var string
     */
    private $currentKey = "";

    /**
     * List of Regex validations
     * @var Array<string>
     */
    private $patterns = [
        "email" => "/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,})$/i"
    ];

    /**
     * @var array
     */
    private $context;

    /**
     * @var array
     */
    private $errors = [];

    public function __construct(array $data)
    {
        $this->context = $data;
    }

    /**
     * @param string $key
     * @param array $rules
     * 
     * @return self
     */
    public function validate(string $key, array $rules): self
    {
        $this->currentKey = $key;

        foreach ($rules as $rule => $arguments) {
            $this->{$rule}($this->context[$key], $arguments);
        }

        $this->currentKey = "";

        return $this;
    }

    /**
     * @return bool
     */
    public function failed(): bool
    {
        return (bool) (count($this->errors) > 0);
    }

    public function errors()
    {
        return $this->errors;
    }

    /**
     * @param string $ctx
     * @param int $minNumber
     * 
     * @return void
     */
    private function min(string $ctx, int $minNumber)
    {
        $valid = strlen($ctx) > $minNumber;
        if (!$valid) {
            $this->errors['errors'][] = [
                "field" => $this->currentKey,
                "code" => 9000,
                "message" => "Input should be minimum : $minNumber"
            ];
        }
    }

    /**
     * @param string $ctx
     * @param array $patterns
     * 
     * @return void
     */
    private function pattern(string $ctx, array $patterns)
    {
        foreach ($patterns as $pattern) {
            $valid = preg_match($this->patterns[$pattern], $ctx);
            if (!$valid) {
                $this->errors['errors'][] = [
                    "field" => $this->currentKey,
                    "code" => 9001,
                    "message" => "Invalid $pattern"
                ];
            }
        }
    }
}
