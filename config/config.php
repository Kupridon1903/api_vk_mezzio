<?php

declare(strict_types=1);

use Laminas\ConfigAggregator\ArrayProvider;
use Laminas\ConfigAggregator\ConfigAggregator;
use Laminas\ConfigAggregator\PhpFileProvider;

$cacheConfig = [
    'config_cache_path' => 'data/cache/config-cache.php',
];

$aggregator = new ConfigAggregator([
    \Mezzio\ProblemDetails\ConfigProvider::class,
    \Mezzio\Helper\ConfigProvider::class,
    Laminas\Di\ConfigProvider::class,
    \Mezzio\Router\FastRouteRouter\ConfigProvider::class,
    Mezzio\ProblemDetails\ConfigProvider::class,
    \Laminas\HttpHandlerRunner\ConfigProvider::class,
    new ArrayProvider($cacheConfig),

    Mezzio\ConfigProvider::class,
    Mezzio\Router\ConfigProvider::class,
    Laminas\Diactoros\ConfigProvider::class,

    System\ConfigProvider::class,
    Api\ConfigProvider::class,

    new PhpFileProvider(realpath(__DIR__) . '/autoload/{{,*.}global,{,*.}local}.php'),
    new PhpFileProvider(realpath(__DIR__) . '/development.config.php'),
], $cacheConfig['config_cache_path']);

return $aggregator->getMergedConfig();
