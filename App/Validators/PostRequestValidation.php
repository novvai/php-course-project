<?php
namespace App\Validators;

use Novvai\Utilities\Validator\Validator;

class PostRequestValidation extends Validator{
    /**
     * 
     */
    protected function handle()
    {
        $this->validate('title', ["required","min"=>3]);
        $this->validate('author', ["required","min"=>3]);
        $this->validate('content', ["required","min"=>250]);
    }
}