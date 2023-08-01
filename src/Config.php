<?php

declare(strict_types=1);

namespace MicroPHP\Framework;

class Config
{
    private static array $config = [];

    public static function get(string $key, $default = null)
    {
        $keys = explode('.', $key);
        $config = self::$config;
        foreach ($keys as $key) {
            if (isset($config[$key])) {
                $config = $config[$key];
            } else {
                return $default;
            }
        }
        return $config;
    }

    public static function load($directory): array
    {
        $files = glob($directory . '/*.php');
        foreach ($files as $file) {
            $key = basename($file, '.php');
            static::$config[$key] = require $file;
        }
        return static::$config;
    }
}