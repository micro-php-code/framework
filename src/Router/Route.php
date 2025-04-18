<?php

declare(strict_types=1);

namespace MicroPHP\Framework\Router;

class Route extends \League\Route\Route
{
    public function getHandler(): array|callable|object|string
    {
        return $this->handler;
    }
}
