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

if (!function_exists("map")) {
    function map(array $arr, callable $func): void
    {
        foreach ($arr as $key => $item) {
            $func($item, $key);
        }
    }
};

if (!function_exists("plural")) {
    function plural(string $singular): string
    {
        $last = $singular[strlen($singular)-1];
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
