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

final class AdminController extends AbstractController
{
    #[Route('/admin/routes', method: 'GET', name: 'admin.routes')]
    public function routes(): Response
    {
        $routes = [
            [
                'method' => 'GET',
                'path' => '/',
                'name' => 'home',
                'target' => 'Components\HelloWorld\Controllers\HelloController::index',
                'middlewares' => ['CorrelationId', 'SecurityHeaders', 'Router'],
                'public' => true,
            ],
            [
                'method' => 'GET',
                'path' => '/hello',
                'name' => 'hello.index',
                'target' => 'Components\HelloWorld\Controllers\HelloController::index',
                'middlewares' => ['CorrelationId', 'SecurityHeaders', 'Router'],
                'public' => true,
            ],
            [
                'method' => 'GET',
                'path' => '/login',
                'name' => 'auth.login.show',
                'target' => 'Components\HelloWorld\Controllers\AuthController::showLogin',
                'middlewares' => ['CorrelationId', 'SecurityHeaders', 'Router'],
                'public' => true,
            ],
            [
                'method' => 'POST',
                'path' => '/login',
                'name' => 'auth.login',
                'target' => 'Components\HelloWorld\Controllers\AuthController::login',
                'middlewares' => ['CorrelationId', 'SecurityHeaders', 'Csrf', 'Router'],
                'public' => true,
            ],
            [
                'method' => 'GET',
                'path' => '/logout',
                'name' => 'auth.logout',
                'target' => 'Components\HelloWorld\Controllers\AuthController::logout',
                'middlewares' => ['CorrelationId', 'SecurityHeaders', 'Auth', 'Router'],
                'public' => false,
            ],
            [
                'method' => 'GET',
                'path' => '/admin/routes',
                'name' => 'admin.routes',
                'target' => 'Components\HelloWorld\Controllers\AdminController::routes',
                'middlewares' => ['CorrelationId', 'SecurityHeaders', 'Auth', 'Router'],
                'public' => false,
            ],
            [
                'method' => 'GET',
                'path' => '/admin/packages',
                'name' => 'admin.packages',
                'target' => 'Components\HelloWorld\Controllers\AdminController::packages',
                'middlewares' => ['CorrelationId', 'SecurityHeaders', 'Auth', 'Router'],
                'public' => false,
            ],
            [
                'method' => 'GET',
                'path' => '/admin/docs',
                'name' => 'admin.docs',
                'target' => 'Components\HelloWorld\Controllers\AdminController::docs',
                'middlewares' => ['CorrelationId', 'SecurityHeaders', 'Auth', 'Router'],
                'public' => false,
            ],
        ];

        return $this->render('@HelloWorld/admin/routes.twig', [
            'title' => 'Route Topology Explorer',
            'routes' => $routes,
            'total_routes' => count($routes),
        ]);
    }

    #[Route('/admin/packages', method: 'GET', name: 'admin.packages')]
    public function packages(): Response
    {
        $baseDir = dirname(__DIR__, 3);

        $detectVersion = function (string $className, string $packageName, ?string $constantName = null): string {
            if (class_exists(\Composer\InstalledVersions::class) && \Composer\InstalledVersions::isInstalled($packageName)) {
                $ver = \Composer\InstalledVersions::getPrettyVersion($packageName);
                if (is_string($ver)) {
                    return $ver;
                }
            }

            if ($constantName !== null && defined($constantName)) {
                return (string) constant($constantName);
            }

            if (class_exists($className)) {
                return 'Loaded (Runtime Active)';
            }

            return 'Unknown Version';
        };

        $twigVersion = $detectVersion('\Twig\Environment', 'twig/twig', '\Twig\Environment::VERSION');
        
        $redbeanVersion = 'Loaded';
        if (class_exists('\R') && method_exists('\R', 'getVersion')) {
            $redbeanVersion = \R::getVersion();
        } else {
            $redbeanVersion = $detectVersion('\RedBeanPHP\OODB', 'redbeanphp/redbean');
        }

        $wajhaVersion = $detectVersion('\Wajha\Router\Router', 'chani/wajha');
        if ($wajhaVersion === 'Unknown Version') {
            $wajhaVersion = $detectVersion('\Wajha\Router', 'wajha/router');
        }

        $diBindings = [
            [
                'contract' => 'Safi\Core\Contracts\ViewEngineInterface',
                'implementation' => 'Safi\Extensions\ViewTwig\TwigEngine',
                'driver' => 'Twig Engine',
                'engine_version' => 'Twig ' . $twigVersion,
                'type' => 'View Extension',
            ],
            [
                'contract' => 'Safi\Core\Contracts\DatabaseDriverInterface',
                'implementation' => 'Safi\Extensions\DbRedBean\RedBeanDriver',
                'driver' => 'RedBeanPHP ORM',
                'engine_version' => 'RedBean ' . $redbeanVersion,
                'type' => 'Database Extension',
            ],
            [
                'contract' => 'Safi\Core\Contracts\RouterInterface',
                'implementation' => 'Safi\Extensions\RouterWajha\WajhaRouter',
                'driver' => 'Wajha High-Speed Router',
                'engine_version' => 'Wajha ' . $wajhaVersion,
                'type' => 'Routing Extension',
            ],
            [
                'contract' => 'Psr\SimpleCache\CacheInterface',
                'implementation' => extension_loaded('apcu') && apcu_enabled()
                    ? 'Safi\Core\Cache\ApcuCache'
                    : 'Safi\Core\Cache\JsonFallbackCache',
                'driver' => extension_loaded('apcu') && apcu_enabled() ? 'APCu Shared RAM' : 'Filesystem JSON',
                'engine_version' => extension_loaded('apcu') ? 'APCu ' . phpversion('apcu') : 'PHP Native File I/O',
                'type' => 'Cache Driver',
            ],
            [
                'contract' => 'Safi\Extensions\Auth\AuthService',
                'implementation' => 'Safi\Extensions\Auth\AuthService',
                'driver' => 'Safi Identity Shield',
                'engine_version' => 'Safi Native Auth v1.0',
                'type' => 'Auth Extension',
            ],
        ];

        $loadedExtensions = [
            ['name' => 'safi/safi-view-twig', 'namespace' => 'Safi\Extensions\ViewTwig', 'engine' => 'Twig Template Engine (' . $twigVersion . ')'],
            ['name' => 'safi/safi-db-redbean', 'namespace' => 'Safi\Extensions\DbRedBean', 'engine' => 'RedBeanPHP ORM (' . $redbeanVersion . ')'],
            ['name' => 'safi/safi-router-wajha', 'namespace' => 'Safi\Extensions\RouterWajha', 'engine' => 'Wajha Router (' . $wajhaVersion . ')'],
            ['name' => 'safi/safi-auth', 'namespace' => 'Safi\Extensions\Auth', 'engine' => 'Safi Security Core'],
        ];

        $installedComponents = [];
        $componentsDir = $baseDir . '/components';
        if (is_dir($componentsDir)) {
            $dirs = scandir($componentsDir);
            if (is_array($dirs)) {
                foreach ($dirs as $d) {
                    if ($d !== '.' && $d !== '..' && is_dir($componentsDir . '/' . $d)) {
                        $installedComponents[] = [
                            'name' => $d,
                            'namespace' => 'Components\\' . $d,
                            'path' => 'components/' . $d,
                            'has_controllers' => is_dir($componentsDir . '/' . $d . '/Controllers'),
                            'has_views' => is_dir($componentsDir . '/' . $d . '/Views'),
                            'has_models' => is_dir($componentsDir . '/' . $d . '/Models'),
                            'status' => 'Active Domain Module',
                        ];
                    }
                }
            }
        }

        return $this->render('@HelloWorld/admin/packages.twig', [
            'title' => 'DI Container & Engine Registry',
            'di_bindings' => $diBindings,
            'loaded_extensions' => $loadedExtensions,
            'installed_components' => $installedComponents,
        ]);
    }

    #[Route('/admin/docs', method: 'GET', name: 'admin.docs')]
    public function docs(): Response
    {
        $baseDir = dirname(__DIR__, 3);
        $docsDir = realpath($baseDir . '/docs');

        // 1. Scan docs directory recursively
        $docsTree = [];
        if ($docsDir && is_dir($docsDir)) {
            $folders = scandir($docsDir);
            if (is_array($folders)) {
                foreach ($folders as $folder) {
                    if ($folder === '.' || $folder === '..' || !is_dir($docsDir . '/' . $folder)) {
                        continue;
                    }
                    $files = scandir($docsDir . '/' . $folder);
                    $mdFiles = [];
                    if (is_array($files)) {
                        foreach ($files as $file) {
                            if (str_ends_with($file, '.md')) {
                                $mdFiles[] = $file;
                            }
                        }
                    }
                    if (!empty($mdFiles)) {
                        $docsTree[$folder] = $mdFiles;
                    }
                }
            }
        }

        // 2. Resolve requested document safely (Path Traversal Protection)
        $rawFile = $this->request->get('file');
        $requestedFile = is_string($rawFile) ? $rawFile : '00-getting-started/index.md';
        $targetPath = $docsDir ? realpath($docsDir . '/' . ltrim($requestedFile, '/')) : false;

        if ($targetPath === false || !str_starts_with($targetPath, (string) $docsDir) || !str_ends_with($targetPath, '.md')) {
            $requestedFile = '00-getting-started/index.md';
            $targetPath = $docsDir ? realpath($docsDir . '/' . $requestedFile) : false;
        }

        $markdownContent = ($targetPath && file_exists($targetPath)) 
            ? (string) file_get_contents($targetPath) 
            : "# Document Not Found\n\nThe requested documentation file `{$requestedFile}` could not be located in `docs/`.";

        return $this->render('@HelloWorld/admin/docs.twig', [
            'title' => 'Framework Documentation Reader',
            'docs_tree' => $docsTree,
            'active_file' => $requestedFile,
            'markdown_content' => $markdownContent,
        ]);
    }
}
