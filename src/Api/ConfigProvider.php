<?php

declare(strict_types=1);

namespace Api;

use Api\Delegator\ApiClientDelegator;

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
            'delegators' => [
//                Handler\VkApiHandler::class => [ApiClientDelegator::class]
            ],
        ];
    }
}
