<?php

namespace Novvai\Response;

use Novvai\Interfaces\Arrayable;

class Response
{
    public function __construct(int $code)
    {
        $this->extract();
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
     * Sets the header location to new url
     * 
     * @param string $to
     * 
     * @return redirect
     */
    public static function redirect(string $to)
    {
        header("location: ".config("app.url") . "/" . $to);
        exit();
    }

    /**
     * @param string $view
     * @param array $viewVariables
     */
    public static function withTemplate(string $view, array $viewVariables = null)
    {
        foreach ($viewVariables??[] as $key => $value) {
            ${$key} = $value;
        }

        include_once load_template($view);
    }

    /** 
     * [TODO] CLEAN UP
     */
    public function withErrors($errors)
    {
        $_SESSION["flash"] = [
            "_fl" => 1,
            "keys" => ['errors']
        ];
        $_SESSION['errors'] =  $errors;

        return $this;
    }
    /** 
     * [TODO] CLEAN UP
     */
    public function withInputs($inputs)
    {
        $_SESSION["flash"]["keys"][] = "inputs";
        $_SESSION["inputs"] = [$inputs];

        return $this;
    }

    public function back()
    {
        header("location: $this->backUrl");
        exit();
    }

    private function extract()
    {
        $this->backUrl = $_SERVER["HTTP_REFERER"];
    }
}
