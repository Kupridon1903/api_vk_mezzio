<?php

declare(strict_types=1);

use Mezzio\Application;

return static function (Application $app): void {
    $iterator = new RecursiveDirectoryIterator(__DIR__ . '/routes', FilesystemIterator::SKIP_DOTS);

    /** @var SplFileInfo $fileInfo */
    foreach (new RecursiveIteratorIterator($iterator) as $fileInfo) {
        if ($fileInfo->isFile()) {
            /** @noinspection UsingInclusionOnceReturnValueInspection */
            (require_once $fileInfo->getPathname())($app);
        }
    }
};
