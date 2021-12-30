<?php

namespace Api\Delegator;

use Api\Middleware\ApiClientMiddleware;
use System\Delegator\BaseDelegatorFactory;

class ApiClientDelegator extends BaseDelegatorFactory
{
    protected function getMiddlewares(): array
    {
        return [
            ApiClientMiddleware::class
        ];
    }
}