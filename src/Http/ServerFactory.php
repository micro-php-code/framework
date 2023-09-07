<?php

declare(strict_types=1);

namespace MicroPHP\Framework\Http;

use MicroPHP\Framework\Config;
use MicroPHP\Framework\Http\Contract\ServerInterface;
use MicroPHP\Framework\Http\Enum\Driver;
use MicroPHP\RoadRunner\RoadRunnerHttpServer;
use MicroPHP\Workerman\WorkermanHttpServer;
use RuntimeException;

class ServerFactory
{
    public static function newServer(): ServerInterface
    {
        $serverConfig = Config::get('app.server');

        return match ($serverConfig['driver']) {
            Driver::WORKERMAN => new WorkermanHttpServer(),
            Driver::ROADRUNNER => new RoadRunnerHttpServer(),
            default => throw new RuntimeException('unsupported driver: ' . $serverConfig['driver']),
        };
    }
}
