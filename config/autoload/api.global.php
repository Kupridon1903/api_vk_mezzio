<?php

declare(strict_types=1);

return [
    'api' => [
        'access_token' => getenv('ACCESS_TOKEN') ?: '71dbb20906984fb8db739b29c39377d1fdb1fc9f0cf4093a024bac4649462cb063563334d951ae5793b60',
        'group_id' => getenv('GROUP_ID') ?: '34767424',
        'api_version' => getenv('API_VERSION') ?: '5.131'
    ],
];
