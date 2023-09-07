<?php

declare(strict_types=1);

namespace MicroPHP\Framework;

use League\Route\Strategy\ApplicationStrategy;
use MicroPHP\Framework\Attribute\Scanner\AttributeScanner;
use MicroPHP\Framework\Attribute\Scanner\AttributeScannerMap;
use MicroPHP\Framework\Config\Config;
use MicroPHP\Framework\Container\Container;
use MicroPHP\Framework\Container\ContainerInterface;
use MicroPHP\Framework\Database\Database;
use MicroPHP\Framework\Http\ServerFactory;
use MicroPHP\Framework\Router\Router;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use ReflectionException;
use Throwable;

final class Application
{
    private static ContainerInterface $container;

    private function __construct()
    {
    }

    /**
     * @throws Throwable
     */
    public static function boot(): Application
    {
        $app = new Application();
        $app->init();
        self::getContainer()->add(Application::class, $app);

        return $app;
    }

    /**
     * @throws ReflectionException
     */
    public function run(): Application
    {
        $app = new Application();
        $config = $app->init();
        $app->listen($config);

        return $app;
    }

    public static function getContainer(): ContainerInterface
    {
        return Application::$container;
    }

    /**
     * @template T
     *
     * @param  class-string<T> $class
     * @return T
     *
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public static function getClass(string $class)
    {
        return Application::getContainer()->get($class);
    }

    /**
     * @throws ReflectionException
     */
    private function init(): array
    {
        Env::load();
        $this->initContainer();
        $config = $this->getConfig();
        $this->initDatabase($config['database']);
        $this->scanAttributes($config['app']['scanner']);

        return $config;
    }

    private function listen(array $config): void
    {
        $router = $this->getRouter($config['routes']);
        Application::getContainer()->add(Router::class, $router);
        ServerFactory::newServer()->run($router);
    }

    private function initDatabase(array $config): void
    {
        Database::boot($config);
    }

    /**
     * @throws ReflectionException
     */
    private function getConfig(): array
    {
        return Config::load(BASE_PATH . '/config');
    }

    private function getRouter(Router $router): Router
    {
        $strategy = new ApplicationStrategy();
        $strategy->setContainer(Application::getContainer());
        $router->setStrategy($strategy);

        return $router;
    }

    /**
     * @throws ReflectionException
     */
    private function scanAttributes(array $config): void
    {
        $result = (new AttributeScanner())->scan($config['directories']);
        Application::$container->add(AttributeScannerMap::class, $result);
    }

    private function initContainer(): void
    {
        Application::$container = new Container();
        Application::$container->defaultToShared();
    }
}
