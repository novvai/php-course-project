<?php
namespace App\Validators;

use Novvai\Utilities\Validator\Validator;

class ProductRequestValidation extends Validator{
    /**
     * 
     */
    protected function handle()
    {
        $this->validate('name', ["required","min"=>3]);
        $this->validate('short_desc', ["required","min"=>3, "max"=>255]);
        $this->validate('description', ["required","min"=>64]);
        $this->validate('price', ["required","min"=>0.01]);
        $this->validate('quantity', ["required","min"=>1]);
    }
}