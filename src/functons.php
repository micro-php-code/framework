<?php

declare(strict_types=1);

if (!function_exists('dump')) {
    function dump($var): void
    {
        print_r($var);
    }
}