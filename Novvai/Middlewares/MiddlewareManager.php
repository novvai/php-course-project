<?php

namespace Novvai\Middlewares;

use Novvai\Request\Request;
use Novvai\Container;

class MiddlewareManager
{
    static private $_instance = null;

    public static function getInstance()
    {
        if (is_null(static::$_instance)) {
            static::$_instance = new static();
        }
        return self::$_instance;
    }

    private function __construct()
    { }

    private $groupMapping = [];

    public static function register($groups)
    {
        $instance = self::getInstance();
        foreach ($groups as &$group) {
            foreach ($group as &$grItem) {
                $grItem = [$grItem, "handle", [Request::getInstance()]];
            }
        }

        $instance->groupMapping = $groups;
    }

    public function get($group): array
    {

        return isset($this->groupMapping[$group]) ? $this->groupMapping[$group] : [];
    }

    public function process($group, $lastCall)
    {
        $group = $this->get($group);
        $group[] = $lastCall;

        return $this->handle($group);
    }

    private function handle($group)
    {
        $arr = array_shift($group);

        $cl = Container::make($arr[0]);

        if (count($group)) {
            $arr[2][] = function () use ($group) {
                return $this->handle($group);
            };
        }

        return call_user_func_array([$cl, $arr[1]], $arr[2]);
    }
}
