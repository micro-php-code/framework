<?php

declare(strict_types=1);

namespace MicroPHP\Framework\Database;

use Illuminate\Database\Capsule\Manager as Capsule;

class Database
{
    public static function __callStatic($name, $arguments)
    {
        return Capsule::$name(...$arguments);
    }

    public static function boot(array $connections): void
    {
        $capsule = new Capsule();

        foreach ($connections as $name => $connection) {
            $capsule->addConnection($connection, $name);
        }
        $capsule->setAsGlobal();
        $capsule->bootEloquent();
    }
}
