<?php

namespace Novvai\Response;

use Novvai\Session;

class Response
{
    public function __construct(int $code)
    {
        $this->extract();
        $this->code($code);
        $this->session = Session::make();
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
        header("location: " . config("app.url") . $to);
        exit();
    }

    /**
     * @param string $view
     * @param array $viewVariables
     */
    public static function withTemplate(string $view, array $viewVariables = null)
    {
        foreach ($viewVariables ?? [] as $key => $value) {
            ${$key} = $value;
        }

        include_once load_template($view);
    }

    /** 
     * @param mixed $errors
     * @return Response
     */
    public function withErrors($errors)
    {
        $this->session->flash("errors", $errors);
        return $this;
    }
    /** 
     * @param mixed $inputs
     * @return Response
     */
    public function withInputs($inputs)
    {
        $this->session->flash("inputs",  $inputs);
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
