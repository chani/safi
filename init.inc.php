<?php

declare(strict_types=1);

use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;
use Safi\Core\Assembler;
use Safi\Core\ComponentManager;
use Safi\Core\Contracts\EventDispatcherInterface;
use Safi\Core\Contracts\RouterInterface;
use Safi\Core\Contracts\SecurityServiceInterface;
use Safi\Core\Contracts\ServiceProviderInterface;
use Safi\Core\Contracts\ViewEngineInterface;
use Safi\Core\Event\EventDispatcher;
use Safi\Core\Http\CorrelationIdMiddleware;
use Safi\Core\Http\MiddlewareInterface;
use Safi\Core\Kernel;
use Safi\Core\Logger;
use Safi\Core\Services\SecurityService;
use Safi\Extensions\AdminPanel\AdminPanelServiceProvider;
use Safi\Extensions\Auth\AuthMiddleware;
use Safi\Extensions\Auth\AuthServiceProvider;
use Safi\Extensions\DbRedBean\RedBeanServiceProvider;
use Safi\Extensions\RouterWajha\WajhaServiceProvider;
use Safi\Extensions\Session\SessionMiddleware;
use Safi\Extensions\Session\SessionServiceInterface;
use Safi\Extensions\Session\SessionServiceProvider;
use Safi\Extensions\ViewTwig\TwigServiceProvider;

require_once __DIR__ . '/vendor/autoload.php';

$config = require __DIR__ . '/config/config.php';
assert(is_array($config));

if (file_exists(__DIR__ . '/config/config.local.php')) {
    $localConfig = require __DIR__ . '/config/config.local.php';
    assert(is_array($localConfig));
    $config = array_replace_recursive($config, $localConfig);
}

$appConfig = is_array($config['app'] ?? null) ? $config['app'] : [];
$dbConfig = is_array($config['db'] ?? null) ? $config['db'] : [];
$viewConfig = is_array($config['views'] ?? null) ? $config['views'] : [];

$debug = isset($appConfig['debug']) && $appConfig['debug'] === true;
$dsn = is_string($dbConfig['dsn'] ?? null) ? $dbConfig['dsn'] : 'sqlite:' . __DIR__ . '/data/db/safi.db';
$dbMode = is_string($dbConfig['mode'] ?? null) ? $dbConfig['mode'] : 'local';
$templateDir = is_string($viewConfig['template_dir'] ?? null) ? $viewConfig['template_dir'] : __DIR__ . '/templates';
$cacheDir = is_string($viewConfig['cache_dir'] ?? null) ? $viewConfig['cache_dir'] : __DIR__ . '/data/cache/views';

$logger = new Logger($debug);
$assembler = new Assembler($logger);

$eventDispatcher = new EventDispatcher();
$assembler->set(ContainerInterface::class, $assembler);
$assembler->set(LoggerInterface::class, $logger);
$assembler->set(EventDispatcherInterface::class, $eventDispatcher);
$assembler->set(EventDispatcher::class, $eventDispatcher);

$componentManager = new ComponentManager($assembler, $logger);

/** @var list<ServiceProviderInterface> $providers */
$providers = [
    new SessionServiceProvider(),
    new RedBeanServiceProvider($dsn, $dbMode),
    new WajhaServiceProvider(),
    new AuthServiceProvider(),
    new TwigServiceProvider($templateDir, $cacheDir, $debug),
    new AdminPanelServiceProvider(),
];

$componentManager->bootProviders($providers);

$assembler->set(SecurityServiceInterface::class, static function (ContainerInterface $c): SecurityService {
    $logger = $c->get(LoggerInterface::class);
    assert($logger instanceof LoggerInterface);
    $sessionClass = SessionServiceInterface::class;

    return new SecurityService(
        $logger,
        [],
        static fn() => $c->has($sessionClass) ? $c->get($sessionClass) : null
    );
});

$assembler->set(SecurityService::class, fn(ContainerInterface $c) => $c->get(SecurityServiceInterface::class));
$security = $assembler->get(SecurityServiceInterface::class);
assert($security instanceof SecurityService);

$viewEngine = $assembler->get(ViewEngineInterface::class);
assert($viewEngine instanceof ViewEngineInterface);

$componentManager->registerComponentViews($viewEngine, __DIR__ . '/components');

$router = $assembler->get(RouterInterface::class);
assert($router instanceof RouterInterface);

$componentManager->registerAttributeRoutes($router, __DIR__ . '/components');

$manifestFile = __DIR__ . '/data/cache/package_manifest.php';

/** @var array{templates: array<string, string>, components: list<string>, routes: list<string>} $manifest */
if ($debug || !file_exists($manifestFile)) {
    $manifest = ['templates' => [], 'components' => [], 'routes' => []];

    if (is_dir($templateDir)) {
        $subDirs = scandir($templateDir);
        if (is_array($subDirs)) {
            foreach ($subDirs as $subDir) {
                if ($subDir === '.' || $subDir === '..') {
                    continue;
                }
                $fullPath = $templateDir . '/' . $subDir;
                if (is_dir($fullPath)) {
                    $manifest['templates'][ucfirst($subDir)] = $fullPath;
                }
            }
        }
    }

    if (class_exists(\Composer\InstalledVersions::class)) {
        foreach (\Composer\InstalledVersions::getInstalledPackages() as $package) {
            if (!str_starts_with($package, 'chani/')) {
                continue;
            }
            $installPath = \Composer\InstalledVersions::getInstallPath($package);
            if (!is_string($installPath)) {
                continue;
            }

            $packageName = basename($package);
            $cleanName = preg_replace('/^safi-/', '', $packageName) ?? $packageName;
            $namespace = str_replace(' ', '', ucwords(str_replace('-', ' ', $cleanName)));

            if (is_dir($installPath . '/templates')) {
                $manifest['templates'][$namespace] = $installPath . '/templates';
            }
            if (is_dir($installPath . '/components')) {
                $manifest['components'][] = $installPath . '/components';
            }
            if (is_dir($installPath . '/src')) {
                $manifest['routes'][] = $installPath . '/src';
            }
        }
    }

    if (!$debug) {
        $manifestDir = dirname($manifestFile);
        if (!is_dir($manifestDir)) {
            mkdir($manifestDir, 0755, true);
        }
        $tmpFile = $manifestFile . '.' . bin2hex(random_bytes(4)) . '.tmp';
        file_put_contents($tmpFile, "<?php\n\ndeclare(strict_types=1);\n\nreturn " . var_export($manifest, true) . ";\n", LOCK_EX);
        rename($tmpFile, $manifestFile);
    }
} else {
    /** @var array{templates: array<string, string>, components: list<string>, routes: list<string>} $loadedManifest */
    $loadedManifest = require $manifestFile;
    $manifest = $loadedManifest;
}

foreach ($manifest['templates'] as $namespace => $path) {
    $viewEngine->registerNamespace($namespace, $path);
}
foreach ($manifest['components'] as $path) {
    $componentManager->registerComponentViews($viewEngine, $path);
}
foreach ($manifest['routes'] as $path) {
    $componentManager->registerAttributeRoutes($router, $path);
}

$viewEngine->addGlobal('csrf_token', static fn(): string => $security->getCsrfToken());
$viewEngine->addGlobal('session', static function () use ($assembler): array {
    if ($assembler->has(SessionServiceInterface::class)) {
        $session = $assembler->get(SessionServiceInterface::class);
        if ($session instanceof SessionServiceInterface) {
            return [
                'auth_user_id' => $session->get('auth_user_id'),
                'auth_username' => $session->get('auth_username'),
            ];
        }
    }
    return [];
});
$viewEngine->addGlobal('app_version', static fn(): string => Kernel::VERSION);

$assembler->set(Kernel::class, static function (ContainerInterface $c) use ($router, $logger, $viewEngine): Kernel {
    $correlation = $c->get(CorrelationIdMiddleware::class);
    assert($correlation instanceof MiddlewareInterface);

    $session = $c->get(SessionMiddleware::class);
    assert($session instanceof MiddlewareInterface);

    $auth = $c->get(AuthMiddleware::class);
    assert($auth instanceof MiddlewareInterface);

    return new Kernel(
        $router,
        $logger,
        $viewEngine,
        [$correlation, $session, $auth],
    );
});

return [
    'assembler' => $assembler,
    'kernel' => $assembler->get(Kernel::class),
    'router' => $router,
    'logger' => $logger,
];
