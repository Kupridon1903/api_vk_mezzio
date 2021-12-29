<?php

declare(strict_types=1);

namespace System;

use Mezzio\ProblemDetails\ProblemDetailsMiddleware;
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
                ProblemDetailsMiddleware::class => ProblemDetailsMiddlewareFactory::class,
            ],
            'initializers' => [
                EventSubscriberInitializer::class,
            ],
        ];
    }
}
