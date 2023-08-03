<?php

declare(strict_types=1);

namespace MicroPHP\Framework\Tests;

use MicroPHP\Framework\Env;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 *
 * @coversNothing
 */
class EnvTest extends TestCase
{
    public function testGetEnv()
    {
        putenv('APP_ENV=test');
        $this->assertEquals('test', Env::get('APP_ENV'));
    }
}
