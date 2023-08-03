<?php

declare(strict_types=1);

function dump($var): void
{
    print_r($var);
}
function base_path(string $var): string
{
    return BASE_PATH . $var;
}
