<?php

class input
{
    public static function exists($type = 'post'): bool
    {
        switch (strtolower($type)) {
            case 'post':
                return !empty($_POST);
            case 'get':
                return !empty($_GET);
            default:
                return false;
        }
    }

    public static function querystring($name): bool
    {
        return isset($_GET[$name]);
    }

    public static function get($item)
    {
        return $_POST[$item] ?? $_GET[$item] ?? false;
    }
}
?>
