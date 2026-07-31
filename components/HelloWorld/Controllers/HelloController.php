<?php

/**
 * Safi Microframework Skeleton
 * @author Jean Bruenn
 * @copyright 2026 All Rights Reserved
 */

declare(strict_types=1);

namespace Components\HelloWorld\Controllers;

use Safi\Core\AbstractController;
use Safi\Core\Attributes\Route;
use Safi\Core\Http\Response;

final class HelloController extends AbstractController
{
    #[Route('/hello', method: 'GET', name: 'hello.index', public: true)]
    public function index(): Response
    {
        return $this->render('@HelloWorld/example.twig', [
            'title' => 'Developer Showcase',
            'framework' => 'Safi Microframework',
            'php_version' => PHP_VERSION,
            'features' => [
                'Pure Reflection DI (No Service Locators)',
                'Attribute-Based Route Compilation via Wajha',
                'Zero-Bloat Persistence Interface',
                'Deferred View Globals & Twig Engine',
                'Secure-by-Default MVC Architecture',
            ],
        ]);
    }
}
