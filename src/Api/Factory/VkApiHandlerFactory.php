<?php

declare(strict_types=1);

namespace Api\Factory;

use Api\Handler\VkApiHandler;
use Psr\Container\ContainerInterface;
use Psr\Http\Server\RequestHandlerInterface;

class VkApiHandlerFactory
{
    public function __invoke(ContainerInterface $container): RequestHandlerInterface
    {
        return new HomePageHandler($container);
    }
}