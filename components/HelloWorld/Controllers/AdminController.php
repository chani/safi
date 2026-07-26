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
use Safi\Core\Contracts\DatabaseDriverInterface;
use Safi\Core\Contracts\RouterInterface;
use Safi\Core\Contracts\ViewEngineInterface;
use Safi\Core\Exception\ValidationException;
use Safi\Core\Http\Request;
use Safi\Core\Http\Response;
use Safi\Core\Kernel;
use Safi\Core\Services\SecurityService;
use Safi\Extensions\Auth\Models\User;

final class AdminController extends AbstractController
{
    public function __construct(
        ViewEngineInterface $view,
        Request $request,
        SecurityService $security,
        DatabaseDriverInterface $db,
        private readonly RouterInterface $router,
        private readonly Kernel $kernel,
    ) {
        parent::__construct($view, $request, $security, $db);
    }

    #[Route('/admin/routes', method: 'GET', name: 'admin.routes')]
    public function routes(): Response
    {
        $this->enforceAdminRole();

        // 1. Dynamically extract registered pipeline middlewares from Kernel
        $pipelineMiddlewares = [];
        foreach ($this->kernel->getMiddlewares() as $mw) {
            if (is_string($mw)) {
                $parts = explode('\\', $mw);
                $pipelineMiddlewares[] = end($parts);
            } elseif (is_object($mw)) {
                $parts = explode('\\', $mw::class);
                $pipelineMiddlewares[] = end($parts);
            } else {
                $pipelineMiddlewares[] = 'Closure';
            }
        }
        $pipelineMiddlewares[] = 'Router';

        // 2. Read live route definitions from router
        $rawRoutes = $this->router->getRoutes();
        $formattedRoutes = [];

        foreach ($rawRoutes as $r) {
            $target = 'Closure / Callable';
            if (is_array($r['handler']) && isset($r['handler'][0], $r['handler'][1])) {
                $target = $r['handler'][0] . '::' . $r['handler'][1];
            }

            $isPublic = isset($r['options']['public']) && $r['options']['public'] === true;

            $formattedRoutes[] = [
                'method' => $r['method'],
                'path' => $r['path'],
                'name' => $r['name'] ?? '-',
                'target' => $target,
                'middlewares' => $pipelineMiddlewares,
                'public' => $isPublic,
            ];
        }

        return $this->render('@HelloWorld/admin/routes.twig', [
            'title' => 'Route Topology Explorer',
            'routes' => $formattedRoutes,
            'total_routes' => count($formattedRoutes),
        ]);
    }

    #[Route('/admin/packages', method: 'GET', name: 'admin.packages')]
    public function packages(): Response
    {
        $this->enforceAdminRole();
        $baseDir = dirname(__DIR__, 3);

        // 1. Real runtime DI bindings retrieved via object reflection
        $diBindings = [
            [
                'contract' => \Safi\Core\Contracts\ViewEngineInterface::class,
                'implementation' => get_class($this->view),
                'driver' => 'Template View Adapter',
                'type' => 'View Extension',
            ],
            [
                'contract' => \Safi\Core\Contracts\DatabaseDriverInterface::class,
                'implementation' => get_class($this->db),
                'driver' => 'Persistence Driver',
                'type' => 'Database Extension',
            ],
            [
                'contract' => \Safi\Core\Contracts\RouterInterface::class,
                'implementation' => get_class($this->router),
                'driver' => 'HTTP Route Dispatcher',
                'type' => 'Routing Extension',
            ],
            [
                'contract' => \Safi\Core\Services\SecurityService::class,
                'implementation' => get_class($this->security),
                'driver' => 'Security & Session Shield',
                'type' => 'Security Core',
            ],
        ];

        // 2. Dynamic discovery of installed framework packages via Composer API
        $loadedExtensions = [];
        if (class_exists(\Composer\InstalledVersions::class)) {
            /** @var list<string> $installedPackages */
            $installedPackages = \Composer\InstalledVersions::getInstalledPackages();
            foreach ($installedPackages as $pkg) {
                if (str_starts_with($pkg, 'chani/safi')) {
                    $ver = \Composer\InstalledVersions::getPrettyVersion($pkg) ?? 'Active';
                    $loadedExtensions[] = [
                        'name' => $pkg,
                        'version' => $ver,
                        'status' => 'Runtime Active Package',
                    ];
                }
            }
        }

        // 3. Dynamic scan of application domain components in components/*
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
        $this->enforceAdminRole();
        $baseDir = dirname(__DIR__, 3);
        $docsDir = realpath($baseDir . '/docs');

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

    private function enforceAdminRole(): void
    {
        $rawCurrentUserId = $_SESSION['auth_user_id'] ?? 0;
        $currentUserId = is_numeric($rawCurrentUserId) ? (int) $rawCurrentUserId : 0;

        if ($currentUserId <= 0) {
            throw new ValidationException('Access denied: Authentication required.');
        }

        $user = $this->db->loadModel(User::class, $currentUserId);
        if (!$user instanceof User || $user->role !== 'admin') {
            throw new ValidationException('Access denied: Administrative privileges required.');
        }
    }
}
