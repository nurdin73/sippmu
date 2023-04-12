<?php

if (!function_exists('env')) {
    /**
     * get environment data
     *
     * @param string $key
     * @param string|null $default
     * @return string
     */
    function env(string $key, ?string $default = null)
    {
        return $_ENV[$key] ?? $default;
    }
}
