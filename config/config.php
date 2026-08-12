<?php

/**
 * Safi Microframework
 * @author Jean Bruenn
 * @copyright 2026 All Rights Reserved
 * @see https://github.com/chani/safi
 */

declare(strict_types=1);

return [
    'app' => [
        'name' => 'Safi Application',
        'env' => 'development',
        'debug' => false,
    ],
    'db' => [
        'dsn' => 'sqlite:' . __DIR__ . '/../data/db/safi.db',
        'mode' => 'local',
    ],
    'security' => [
        'trusted_proxies' => [],
    ],
    'views' => [
        'template_dir' => __DIR__ . '/../templates',
        'cache_dir' => __DIR__ . '/../data/cache/views',
    ],
];
