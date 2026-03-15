<?php

if (!function_exists('base_path')) {
    function base_path($path = '')
    {
        $base = dirname(__DIR__, 2);
        $path = ltrim($path, '/\\');

        return $path
            ? $base . DIRECTORY_SEPARATOR . str_replace(['/', '\\'], DIRECTORY_SEPARATOR, $path)
            : $base;
    }
}

if (!function_exists('config')) {
    function config($key = null, $default = null)
    {
        static $configs = [];

        if (empty($configs)) {
            $configPath = base_path('config');

            foreach (glob($configPath . DIRECTORY_SEPARATOR . '*.php') as $file) {
                $name = basename($file, '.php');
                $configs[$name] = require $file;
            }
        }

        if ($key === null) {
            return $configs;
        }

        $segments = explode('.', $key);
        $value = $configs;

        foreach ($segments as $segment) {
            if (!isset($value[$segment])) {
                return $default;
            }
            $value = $value[$segment];
        }

        return $value;
    }
}

if (!function_exists('base_url')) {
    function base_url($path = '')
    {
        $path = ltrim($path, '/');
        return $path ? '/' . $path : '/';
    }
}

if (!function_exists('asset')) {
    function asset($path = '')
    {
        return base_url($path);
    }
}

if (!function_exists('url')) {
    function url($path = '')
    {
        return base_url($path);
    }
}

if (!function_exists('full_url')) {
    function full_url($path = '')
    {
        $base = rtrim(config('app.url', ''), '/');
        $path = ltrim($path, '/');

        return $path ? $base . '/' . $path : $base;
    }
}

if (!function_exists('view_path')) {
    function view_path($view)
    {
        return base_path('resources/views/' . str_replace('.', '/', $view) . '.php');
    }
}

if (!function_exists('app_path')) {
    function app_path($path = '')
    {
        return base_path('app/' . ltrim($path, '/\\'));
    }
}

if (!function_exists('storage_path')) {
    function storage_path($path = '')
    {
        return base_path('storage/' . ltrim($path, '/\\'));
    }
}