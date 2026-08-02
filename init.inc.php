<?php

declare(strict_types=1);

use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;
use Safi\Core\Assembler;
use Safi\Core\ComponentManager;
use Safi\Core\Contracts\RouterInterface;
use Safi\Core\Contracts\ViewEngineInterface;
use Safi\Core\Http\CorrelationIdMiddleware;
use Safi\Core\Http\MiddlewareInterface;
use Safi\Core\Kernel;
use Safi\Core\Logger;
use Safi\Core\Services\SecurityService;
use Safi\Extensions\Auth\AuthMiddleware;
use Safi\Extensions\Auth\AuthServiceProvider;
use Safi\Extensions\DbRedBean\RedBeanServiceProvider;
use Safi\Extensions\I18n\I18nServiceProvider;
use Safi\Extensions\RouterWajha\WajhaServiceProvider;
use Safi\Extensions\Session\SessionMiddleware;
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
$langDir = is_string($config['lang_dir'] ?? null) ? $config['lang_dir'] : __DIR__ . '/data/lang';

$debug = isset($appConfig['debug']) && $appConfig['debug'] === true;
$dsn = is_string($dbConfig['dsn'] ?? null) ? $dbConfig['dsn'] : 'sqlite:' . __DIR__ . '/data/db/safi.db';
$dbMode = is_string($dbConfig['mode'] ?? null) ? $dbConfig['mode'] : 'local';
$templateDir = is_string($viewConfig['template_dir'] ?? null) ? $viewConfig['template_dir'] : __DIR__ . '/templates';
$cacheDir = is_string($viewConfig['cache_dir'] ?? null) ? $viewConfig['cache_dir'] : __DIR__ . '/data/cache/views';

$logger = new Logger($debug);
$assembler = new Assembler($logger);

$assembler->set(ContainerInterface::class, $assembler);
$assembler->set(LoggerInterface::class, $logger);

$componentManager = new ComponentManager($assembler, $logger);

$componentManager->bootProviders([
    new SessionServiceProvider(),
    new RedBeanServiceProvider($dsn, $dbMode),
    new WajhaServiceProvider(),
    new AuthServiceProvider(),
    new I18nServiceProvider($langDir),
    new TwigServiceProvider($templateDir, $cacheDir, $debug),
]);

// ARCHITECTURE GUARD: SessionServiceInterface MUST be passed as a lazy closure.
// SessionService depends on SecurityService for client IP resolution during boot. Direct container fetch here causes
// a circular dependency deadlock during initialization. Keep the closure resolver lazy.
$assembler->set(SecurityService::class, static function (ContainerInterface $c): SecurityService {
    $logger = $c->get(LoggerInterface::class);
    assert($logger instanceof LoggerInterface);
    $sessionClass = 'Safi\\Extensions\\Session\\SessionServiceInterface';

    return new SecurityService(
        $logger,
        [],
        static fn() => $c->has($sessionClass) ? $c->get($sessionClass) : null
    );
});

$security = $assembler->get(SecurityService::class);
assert($security instanceof SecurityService);

$viewEngine = $assembler->get(ViewEngineInterface::class);
assert($viewEngine instanceof ViewEngineInterface);

$componentManager->registerComponentViews($viewEngine, __DIR__ . '/components');

if (is_dir(__DIR__ . '/templates/auth')) {
    $viewEngine->registerNamespace('Auth', __DIR__ . '/templates/auth');
}

$router = $assembler->get(RouterInterface::class);
assert($router instanceof RouterInterface);

$componentManager->registerAttributeRoutes($router, __DIR__ . '/components');

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
            $viewEngine->registerNamespace($namespace, $installPath . '/templates');
        }
        if (is_dir($installPath . '/components')) {
            $componentManager->registerComponentViews($viewEngine, $installPath . '/components');
        }
        if (is_dir($installPath . '/src')) {
            $componentManager->registerAttributeRoutes($router, $installPath . '/src');
        }
    }
}

$viewEngine->addGlobal('csrf_token', fn(): string => $security->getCsrfToken());
$viewEngine->addGlobal('session', fn(): array => $_SESSION ?? []);
$viewEngine->addGlobal('app_version', Kernel::VERSION);

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
