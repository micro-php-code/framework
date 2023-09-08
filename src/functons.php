<?php

declare(strict_types=1);

function dump($var): void
{
    print_r($var);
    echo PHP_EOL;
}
function base_path(string $var): string
{
    return BASE_PATH . $var;
}

function get_os(): string
{
    return DIRECTORY_SEPARATOR === '\\' ? OS_TYPE_LINUX : OS_TYPE_WINDOWS;
}
