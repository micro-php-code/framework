<?php

declare(strict_types=1);

namespace MicroPHP\Framework\Http\Contract;

use League\Route\Router;

interface ServerInterface
{
    public function run(Router $router): void;
}
