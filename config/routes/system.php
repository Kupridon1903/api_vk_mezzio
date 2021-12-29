<?php

declare(strict_types=1);

use App\Handler\HomePageHandler;
use App\Handler\PingHandler;
use Mezzio\Application;

return static function (Application $app): void {
    $app->get('/', HomePageHandler::class, 'home');
    $app->get('/ping', PingHandler::class, 'api.ping');
};