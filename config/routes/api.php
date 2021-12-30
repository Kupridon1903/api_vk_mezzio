<?php

declare(strict_types=1);

use Api\Handler\VkApiHandler;
use Mezzio\Application;

return static function (Application $app): void {
    $app->get('/api', VkApiHandler::class, 'api');
};
