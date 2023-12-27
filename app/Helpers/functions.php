<?php

if (! function_exists('guard')) {
    /**
     * Get Guard from request
     *
     * @return string
     */
    function guard(): string
    {
        if (app('request')->expectsJson()) {
            return 'api';
        }

        return config('auth.defaults.guard');
    }
}
