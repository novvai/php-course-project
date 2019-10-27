<?php
namespace App\Validators;

use Novvai\Utilities\Validator\Validator;

class CategoryRequestValidator extends Validator{
    /**
     * 
     */
    protected function handle()
    {
        $this->validate('name', ["required"]);
    }
}