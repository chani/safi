<?php

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
        return $this->render('@HelloWorld/example', [
            'title' => 'Developer Showcase',
            'framework' => 'Safi Microframework',
            'php_version' => PHP_VERSION,
            'features' => [
                'Dependency Injection Container',
                'Attribute-Based Route Compilation',
                'MVC Boundary Isolation',
                'Deferred View Globals',
                'Inverted Route Protection',
            ],
        ]);
    }
}
