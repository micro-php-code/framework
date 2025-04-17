<?php
declare(strict_types=1);

namespace MicroPHP\Framework\Router;

class Route extends \League\Route\Route
{
    public function getHandler(): array|string|callable|object
    {
        return $this->handler;
    }
}