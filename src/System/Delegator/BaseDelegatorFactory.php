<?php

declare(strict_types=1);

namespace System\Delegator;

use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\DelegatorFactoryInterface;
use Laminas\Stratigility\MiddlewarePipe;
use Laminas\Stratigility\MiddlewarePipeInterface;
use Mezzio\MiddlewareFactory;

abstract class BaseDelegatorFactory implements DelegatorFactoryInterface
{
    protected ContainerInterface $container;

    /**
     * @param ContainerInterface $container
     * @param string $name
     * @param callable $callback
     * @param array|null $options
     *
     * @return MiddlewarePipeInterface
     */
    public function __invoke(
        ContainerInterface $container,
        $name,
        callable $callback,
        array $options = null
    ): MiddlewarePipeInterface {
        $this->container = $container;
        $factory = $container->get(MiddlewareFactory::class);
        $pipeline = new MiddlewarePipe();

        // These correspond to the bullet points above
        foreach ($this->getMiddlewares() as $middleware) {
            $pipeline->pipe($factory->prepare($middleware));
        }

        // This is the actual handler you're routing to.
        $pipeline->pipe($factory->prepare($callback()));

        return $pipeline;
    }

    abstract protected function getMiddlewares(): array;
}
