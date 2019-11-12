<?php

namespace Novvai\Utilities\Validator;

abstract class Validator
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
        "email" => "/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,})$/i",
        "phone" => "/(\+)?(359|0)8[789]\d{1}(|-| )\d{3}(|-| )\d{3}/",
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
        $this->handle();
    }

    abstract protected function handle();
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
            if (is_numeric($rule)) {
                $this->{$arguments}($this->context[$key]);
                continue;
            }

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

    /**
     * @return array
     */
    public function errors(): array
    {
        return reset($this->errors);
    }

    /**
     * @param mixed $ctx
     * @param int $minNumber
     * 
     * @return void
     */
    private function min($ctx, $minNumber)
    {
        $errorCode = 9001;
        if (!is_numeric($ctx)) {
            $ctx = strlen($ctx);
            $errorCode = 9002;
        }
        if (!($ctx >= $minNumber)) {
            $this->errors['errors'][$this->currentKey][] = [
                "code" => $errorCode,
                "criterion" => $minNumber
            ];
        }
    }
    /**
     * @param mixed $ctx
     * @param int $maxNumber
     * 
     * @return void
     */
    private function max($ctx, $maxValue)
    {
        $errorCode = 9003;
        if (!is_numeric($ctx)) {
            $ctx = strlen($ctx);
            $errorCode = 9004;
        }
        if (!($ctx <= $maxValue)) {
            $this->errors['errors'][$this->currentKey][] = [
                "code" => $errorCode,
                "criterion" => $maxValue
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
                $this->errors['errors'][$this->currentKey][] = [
                    "code" => 9005,
                    "criterion" => $pattern
                ];
            }
        }
    }

    /**
     * @param string $ctx
     * @param int $minNumber
     * 
     * @return void
     */
    private function required($ctx)
    {
        $invalid = is_null($ctx) || (empty($ctx) && $ctx!=0) || $ctx === "";
        if ($invalid) {
            $this->errors['errors'][$this->currentKey][] = [
                "code" => 9006,
                "criterion" => $this->currentKey
            ];
        }
    }
}
