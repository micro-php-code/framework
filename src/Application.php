<?php

declare(strict_types=1);

namespace MicroPHP\Framework;

use JsonException;
use League\Container\Container;
use League\Container\DefinitionContainerInterface;
use League\Route\Http\Exception\MethodNotAllowedException;
use League\Route\Http\Exception\NotFoundException;
use League\Route\Router;
use League\Route\Strategy\ApplicationStrategy;
use MicroPHP\Framework\Attribute\Scanner\AttributeScanner;
use MicroPHP\Framework\Attribute\Scanner\AttributeScannerMap;
use MicroPHP\Framework\Database\Database;
use MicroPHP\Framework\Http\Response;
use MicroPHP\Framework\Http\ServerRequest;
use Nyholm\Psr7\Factory\Psr17Factory;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use ReflectionException;
use Spiral\RoadRunner\Http\PSR7Worker;
use Spiral\RoadRunner\Worker;
use Throwable;

final class Application
{
    private static DefinitionContainerInterface $container;

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

        return $app;
    }

    /**
     * @throws ReflectionException
     * @throws JsonException
     */
    public function run(): Application
    {
        $app = new Application();
        $config = $app->init();
        $app->listen($config);

        return $app;
    }

    public static function getContainer(): Container
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

    /**
     * @throws JsonException
     *
     * @noinspection PhpRedundantCatchClauseInspection
     */
    private function listen(array $config): void
    {
        $router = $this->getRouter($config['routes']);
        Application::getContainer()->add(Router::class, $router);

        $worker = Worker::create();

        $factory = new Psr17Factory();

        $psr7 = new PSR7Worker($worker, $factory, $factory, $factory);

        while ($req = $psr7->waitRequest()) {
            $request = ServerRequest::fromPsr7($req);
            try {
                $psr7->respond($router->dispatch($request));
            } catch (NotFoundException $exception) {
                $psr7->respond(new Response(404, [], $exception->getMessage()));
            } catch (MethodNotAllowedException $exception) {
                $psr7->respond(new Response(405, [], $exception->getMessage()));
            } catch (Throwable $e) {
                $psr7->getWorker()->error((string) $e);
            }
        }
    }

    private function initDatabase(array $config): void
    {
        Database::boot($config);
    }

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
