<?php

declare(strict_types=1);

use Blazon\PSR11MonoLog\MonologFactory;
use Monolog\Logger;
use Psr\Log\LoggerInterface;

return [
    'dependencies' => [
        'factories' => [
            LoggerInterface::class => [MonologFactory::class, 'mainChannel'],
            'logger'               => MonologFactory::class,
            'consoleChannel'       => [MonologFactory::class, 'consoleChannel'],
            // Another logger using a different channel configuration
            'errorChannel'         => [MonologFactory::class, 'errorChannel'],
        ],
    ],

    'monolog' => [
        'handlers' => [
            'default'    => [
                'type'    => 'stream',
                'options' => [
                    'stream' => '/var/log/ofd/main.log',
                ],
            ],
            'consoleLog' => [
                'type'      => 'stream',
                'formatter' => 'consoleFormatter',
                'options'   => [
                    'stream' => 'php://stdout',
                    'level'  => Logger::INFO,
                    'bubble' => true,
                ],
            ],
            // errors Handler
            'errorLog'   => [
                'type'      => 'stream',
                'formatter' => 'lineFormatter',
                'options'   => [
                    'stream' => 'php://stderr',
                    'level'  => Logger::WARNING,
                    'bubble' => true,
                ],
            ],
        ],

        'channels' => [
            'mainChannel' => [
                'name'       => 'mainChannel',
                'handlers'   => ['default'],
                'processors' => ['psrProcessor', 'uuidProcessor', 'webProcessor'],
            ],
            'errorChannel'   => [
                'name'       => 'errorChannel',
                'handlers'   => ['errorLog',],
                'processors' => ['psrProcessor', 'webProcessor', 'uuidProcessor'],
            ],
            'consoleChannel' => [
                'name'       => 'consoleChannel',
                'handlers'   => ['consoleLog'],
                'processors' => ['psrProcessor', 'uuidProcessor'],
            ],
        ],

        'formatters' => [
            'lineFormatter'    => [
                'type'    => 'line',
                'options' => [
                    'format'                     => "[%datetime%] %channel%.%level_name%: %message% %context% %extra%\n",
                    'dateFormat'                 => 'Y-m-d H:i:s T',
                    'allowInlineLineBreaks'      => false,
                    'ignoreEmptyContextAndExtra' => false,
                ],
            ],
            'consoleFormatter' => [
                'type'    => 'line',
                'options' => [
                    'format'                     => "[%datetime%][%extra.uid%] %level_name%: %message% %context%\n",
                    'dateFormat'                 => 'Y-m-d H:i:s T',
                    'allowInlineLineBreaks'      => false,
                    'ignoreEmptyContextAndExtra' => true,
                ],
            ],
        ],

        'processors' => [
            'psrProcessor'  => [
                'type'    => 'psrLogMessage',
                'options' => [], // No options
            ],
            'webProcessor'  => [
                'type'    => 'web',
                'options' => [
                    // Optional: Array, object w/ ArrayAccess, or valid service name that provides
                    // access to the $_SERVER data
                    //'serverData'  => ['REMOTE_ADDR'],
                    //'extraFields' => [],
                ],
            ],
            'uuidProcessor' => [
                'type'    => 'uid',
                'options' => [
                    'length' => 7, // Optional: The uid length. Must be an integer between 1 and 32
                ],
            ],
        ],
    ],
];
