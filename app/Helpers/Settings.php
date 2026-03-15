<?php
// app/Helpers/Settings.php

if (!function_exists('setting')) {
    /**
     * Get a setting value.
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    function setting($key, $default = null)
    {
        return App\Models\Setting::get($key, $default);
    }
}