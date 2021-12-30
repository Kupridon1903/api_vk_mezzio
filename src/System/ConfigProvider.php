<?php

declare(strict_types=1);

namespace System;

use Mezzio\ProblemDetails\ProblemDetailsMiddleware;
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
                Handler\PingHandler::class => Handler\PingHandler::class,
                Handler\HomePageHandler::class => Handler\HomePageHandler::class,
            ],
            'factories'    => [
                ProblemDetailsMiddleware::class => ProblemDetailsMiddlewareFactory::class,
            ],
        ];
    }
}
