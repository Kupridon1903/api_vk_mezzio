<?php

declare(strict_types=1);

use System\Handler\HomePageHandler;
use System\Handler\PingHandler;
use Mezzio\Application;

return static function (Application $app): void {
    $app->get('/', HomePageHandler::class, 'system.home');
    $app->get('/ping', PingHandler::class, 'system.ping');
};