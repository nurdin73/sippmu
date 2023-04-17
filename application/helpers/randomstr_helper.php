<?php

if (!function_exists('randomStr')) {
    /**
     * generate random string
     *
     * @param integer $length
     * @return string
     */
    function randomStr(int $length = 30): string
    {
        $str = '';
        $char = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
        $charLength = strlen($char);
        for ($i = 0; $i < $length; $i++) {
            $str .= $char[rand(0, $charLength - 1)];
        }
        return $str;
    }
}
