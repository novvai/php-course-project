<?php
namespace App\Validators;

use Novvai\Utilities\Validator\Validator;

class ShopRequestValidator extends Validator{
    
    /**
     * 
     */
    protected function handle()
    {
        $this->validate('title', ["min" => 3]);
        $this->validate('phone', ["min" => 9]);
        $this->validate('work_time', ["min" => 3]);
    }
}