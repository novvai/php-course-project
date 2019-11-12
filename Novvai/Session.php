<?php

namespace Novvai;

use Novvai\Interfaces\SessionInterface;

/** SINGLETON */
class Session implements SessionInterface
{
    /**
     * Instance container for the Singleton patter
     * @var Session 
     */
    static $instance = null;

    /**  */
    private function __construct()
    {
        session_start();
        $this->handle();
    }

    /** 
     * Singleton init
     * @return Session
     */
    static public function make()
    {
        if (is_null(static::$instance)) {
            static::$instance = new static();
        }
        return static::$instance;
    }

    /**
     * @param string $key
     * @return bool
     */
    public function has(string $key): bool
    {
        return isset($_SESSION[$key]);
    }
    /**
     * @param string $key
     * @return bool
     */
    public function all()
    {
        return $_SESSION;
    }

    /**
     * @param string $key
     * @return mixed $result
     */
    public function get(string $dottedPath, $default=null)
    {
        $result = $_SESSION;
        $components = explode('.', $dottedPath);
        foreach ($components as $component) {
            if (isset($result[$component])) {
                $result = $result[$component];
                continue;
            }
            $result = $default;
            break;
        }

        return $result;
    }

    /**
     * @param string $key
     * @param mixed $value
     * @return SessionInterface 
     */
    public function add(string $key, $value): SessionInterface
    {
        $_SESSION[$key] = $value;

        return $this;
    }

    /** 
     * Removes everything from the session
     * Destroy the session id
     */
    public function destroy()
    {
        session_destroy();
        unset($_SESSION);
    }


    /**
     * @param string $key
     * @param mixed $value
     * @return SessionInterface 
     */
    public function flash(string $key, $value): SessionInterface
    {
        if (!$this->has('flash')) {
            $this->initFlash();
        }
        array_push($_SESSION["flash"]["_keys"], $key);
        $this->add($key, $value);

        return $this;
    }

    private function handle()
    {
        $this->manageFlashSessions();
    }

    private function manageFlashSessions()
    {
        if ($this->has('flash')) {
            if ($_SESSION["flash"]["_fc"] > 0) {
                $_SESSION["flash"]["_fc"]--;
                return null;
            }

            foreach ($_SESSION["flash"]["_keys"] ?? [] as $key) {
                unset($_SESSION[$key]);
            }
            unset($_SESSION["flash"]);
        }
    }

    private function initFlash()
    {
        $_SESSION["flash"] = [
            "_fc" => 1,
            "_keys" => []
        ];
    }
}
