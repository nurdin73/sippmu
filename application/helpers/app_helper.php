<?php

if (!function_exists('dd')) {
    /**
     * variable dump and die
     *
     * @param any $payload
     * @return void
     */
    function dd($payload)
    {
        var_dump($payload);
        die;
    }
}

if (!function_exists('byteToMega')) {
    /**
     * convert size file from bytes to mega
     *
     * @param integer $bytes
     * @return int
     */
    function byteToMega(int $bytes)
    {
        $conversion = 1000 * 1024;
        $convert = $bytes / $conversion;
        return number_format($convert, 2);
    }
}