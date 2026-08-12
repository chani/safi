<?php

declare(strict_types=1);

return [
    'default' => 'file',
    'stores' => [
        'apcu' => ['driver' => 'apcu', 'prefix' => 'safi_'],
        'file' => ['driver' => 'file', 'path' => __DIR__ . '/../data/cache'],
        'redis' => ['driver' => 'redis', 'host' => '127.0.0.1', 'port' => 6379],
        'null' => ['driver' => 'null'],
    ],
    'scopes' => [
        'session' => 'apcu',
        'auth' => 'apcu',
        'rbac' => 'apcu',
    ],
];
