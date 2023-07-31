<?php

declare(strict_types=1);

namespace Simple\Framework;
use JsonException;
use League\Container\Container;
use League\Container\DefinitionContainerInterface;
use League\Route\Http\Exception\MethodNotAllowedException;
use League\Route\Http\Exception\NotFoundException;
use League\Route\Router;
use League\Route\Strategy\ApplicationStrategy;
use Nyholm\Psr7\Factory\Psr17Factory;
use Psr\Container\ContainerInterface;
use Simple\Framework\Database\Database;
use Simple\Framework\Http\Response;
use Simple\Framework\Http\ServerRequest;
use Spiral\RoadRunner\Http\PSR7Worker;
use Spiral\RoadRunner\Worker;
use Throwable;

class Application
{
    private static DefinitionContainerInterface $container;
    /**
     * @throws Throwable
     * @throws JsonException
     */
    public static function boot(): void
    {
        static::initContainer();
        (new static())->init();
    }

    /**
     * @throws JsonException
     * @noinspection PhpRedundantCatchClauseInspection
     */
    private function init(): void
    {
        $config = $this->getConfig();
        $router = $this->getRouter($config['routes']);
        $this->initDatabase($config['database']);
        $worker = Worker::create();

        $factory = new Psr17Factory();

        $psr7 = new PSR7Worker($worker, $factory, $factory, $factory);

        while ($req = $psr7->waitRequest()) {
            $request = ServerRequest::fromPsr7($req);
            try {
                $psr7->respond($router->dispatch($request));
            } catch (NotFoundException $exception){
                $psr7->respond(new Response(404, [], $exception->getMessage()));
            } catch (MethodNotAllowedException $exception) {
                $psr7->respond(new Response(405, [], $exception->getMessage()));
            } catch (Throwable $e) {
                $psr7->getWorker()->error((string)$e);
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
        $strategy = new ApplicationStrategy;
        $strategy->setContainer(static::getContainer());
        $router->setStrategy($strategy);
        return $router;
    }

    private static function initContainer(): void
    {
        static::$container = new Container();
        static::$container->defaultToShared();
    }

    public function getContainer(): ContainerInterface
    {
        return static::$container;
    }
}