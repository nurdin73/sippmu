<?php

if (!function_exists('initial')) {
    /**
     * get initial from string
     *
     * @param string $str
     * @return string
     */
    function initial(string $str): string
    {
        $seperate = explode(' ', $str);
        $result = '';
        foreach ($seperate as $key) {
            substr($key, 0, 1);
            $result .= $key[0];
        }
        return $result;
    }
}
