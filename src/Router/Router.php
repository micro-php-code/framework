<?php

declare(strict_types=1);

namespace MicroPHP\Framework\Router;

use League\Route\RouteGroup;

class Router extends \League\Route\Router
{
    public function getOrPost(string $path, $handler): RouteGroup
    {
        return $this->group('', function (RouteGroup $group) use ($path, $handler) {
            $group->get($path, $handler);
            $group->post($path, $handler);
        });
    }
}
