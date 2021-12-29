<?php

declare(strict_types=1);

namespace System;

use Mezzio\ProblemDetails\ProblemDetailsMiddleware;
use Psr\SimpleCache\CacheInterface;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use System\Cache\MemcachedFactory;
use System\Constraint\ValidatorFactory;
use System\Initializer\EventSubscriberInitializer;
use System\Response\ProblemDetailsMiddlewareFactory;

class ConfigProvider
{
    public function __invoke(): array
    {
        return [
            'dependencies' => $this->getDependencies(),
        ];
    }

    public function getDependencies(): array
    {
        return [
            'invokables'   => [
            ],
            'factories'    => [
                ValidatorInterface::class       => ValidatorFactory::class,
                CacheInterface::class           => MemcachedFactory::class,
                ProblemDetailsMiddleware::class => ProblemDetailsMiddlewareFactory::class,
            ],
            'aliases'      => [
                EventDispatcherInterface::class                                    => EventDispatcher::class,
                \Symfony\Contracts\EventDispatcher\EventDispatcherInterface::class => EventDispatcher::class,
                \Psr\EventDispatcher\EventDispatcherInterface::class               => EventDispatcher::class,
            ],
            'initializers' => [
                EventSubscriberInitializer::class,
            ],
        ];
    }
}
