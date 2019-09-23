<?php
if (!function_exists("base_path")) {
    function base_path(): string
    {
        return __DIR__ . DIRECTORY_SEPARATOR;
    }
};

if (!function_exists("dd")) {
    function dd(...$args): void
    {
        foreach ($args as $arg) {
            echo "<pre>" . print_r($arg, true) . "</pre>";
        }
        die();
    }
};


if (!function_exists("get_public_vars")) {
    function get_public_vars($obj)
    {
        return get_object_vars($obj);
    }
};

if (!function_exists("get_short_name")) {
    function get_short_name($class)
    {
        return strtolower((new \ReflectionClass($class))->getShortName());
    }
};

if (!function_exists("map")) {
    function map(array $arr, callable $func): array
    {
        $newArray = [];
        foreach ($arr as $key => $item) {
            $result =  $func($item, $key);
            if (key_exists($key, $newArray)) {
                $newArray[$key] = array_merge($newArray[$key], [$result]);
                continue;
            }
            $newArray[$key] = $result;
        }
        return $newArray;
    }
};

if (!function_exists("plural")) {
    function plural(string $singular): string
    {
        $last = $singular[strlen($singular) - 1];
        switch ($last) {
            case 'y':
                return substr($singular, 0, -1) . 'ies';
            case 's':
                return $singular . 'es';
            default:
                return $singular . 's';
        }
        return $singular;
    }
};
