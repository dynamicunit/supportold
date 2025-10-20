<?php

class hash
{

    public static function make($string, $salt = '')
    {
        return hash('sha256', $string . $salt);
    }

    public static function salt($length)
    {
        // return mcrypt_create_iv($length);
        // return random_bytes($length);
        return self::gen_salt();
    }

    public static function unique()
    {
        return self::make(uniqid());
    }

    private static function gen_salt($length = '32')
    {
        $alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
        $pass = array();
        $alphaLength = strlen($alphabet) - 1;
        for ($i = 0; $i < $length; $i ++) {
            $n = rand(0, $alphaLength);
            $pass[] = $alphabet[$n];
        }
        return implode($pass); // turn the array into a string
    }
}
?>