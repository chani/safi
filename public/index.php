<?php

/**
 * Safi Microframework
 * @author Jean Bruenn
 * @copyright 2026 All Rights Reserved
 * @see https://github.com/chani/safi
 */

declare(strict_types=1);

use Safi\Core\Http\Request;
use Safi\Core\Kernel;

/** @var array{assembler: object, kernel: Kernel, router: object, logger: object} $boot */
$boot = require __DIR__ . '/../init.inc.php';

$request = new Request();
$response = $boot['kernel']->handle($request);
$response->send();
