<?php

namespace Novvai;

use ReflectionClass;
use ReflectionParameter;

class Container
{
    static private $bindings = [];

    static public function bind(array $binding)
    {
        static::$bindings = array_merge(static::$bindings, $binding);
    }

    static public function make(string $className)
    {
        $reflection = new ReflectionClass($className);
        $constructor = $reflection->getConstructor();
        if(is_null($constructor))
        {
            return $reflection->newInstance();
        }
        $resolvedDependencies = static::resolve($constructor->getParameters());
        return $reflection->newInstanceArgs($resolvedDependencies);
    }

    /**
     * @param array|ReflectionParameter $params
     * 
     * @return array
     */
    static private function resolve(array $params): array
    {
        $resolved = [];
        foreach ($params as $param) {
            $dep = $param->getClass();
            if (!$dep->isInstantiable()) {
                $dep = static::checkBinding($dep->getName());
            }
            $resolved[] = static::make($dep);
        }

        return $resolved;
    }

    static private function checkBinding(string $className)
    {
        if (array_key_exists($className, static::$bindings)) {
            return static::$bindings[$className];
        }
    }
}
