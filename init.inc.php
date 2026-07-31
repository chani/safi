<?php

/**
 * Safi Microframework
 * @author Jean Bruenn
 * @copyright 2026 All Rights Reserved
 * @see https://github.com/chani/safi
 */

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
use Safi\Extensions\Cache\CacheServiceProvider;
use Safi\Extensions\DbRedBean\RedBeanServiceProvider;
use Safi\Extensions\I18n\I18nServiceProvider;
use Safi\Extensions\Queue\QueueServiceProvider;
use Safi\Extensions\RouterWajha\WajhaServiceProvider;
use Safi\Extensions\Search\SearchServiceProvider;
use Safi\Extensions\Session\SessionMiddleware;
use Safi\Extensions\Session\SessionServiceProvider;
use Safi\Extensions\ViewTwig\TwigServiceProvider;

require_once __DIR__ . '/vendor/autoload.php';

/** @var array<string, mixed> $config */
$config = require __DIR__ . '/config/config.php';
if (file_exists(__DIR__ . '/config/config.local.php')) {
    /** @var array<string, mixed> $localConfig */
    $localConfig = require __DIR__ . '/config/config.local.php';
    $config = array_replace_recursive($config, $localConfig);
}

/** @var array<string, mixed> $appConfig */
$appConfig = is_array($config['app'] ?? null) ? $config['app'] : [];
/** @var array<string, mixed> $dbConfig */
$dbConfig = is_array($config['db'] ?? null) ? $config['db'] : [];
/** @var array<string, mixed> $viewConfig */
$viewConfig = is_array($config['views'] ?? null) ? $config['views'] : [];
/** @var string $langDir */
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
    new CacheServiceProvider(),
    new SessionServiceProvider(),
    new RedBeanServiceProvider($dsn, $dbMode),
    new WajhaServiceProvider(),
    new AuthServiceProvider(),
    new QueueServiceProvider(),
    new SearchServiceProvider(),
    new I18nServiceProvider($langDir),
    new TwigServiceProvider($templateDir, $cacheDir, $debug),
]);

/** @var SecurityService $security */
$security = $assembler->get(SecurityService::class);

/** @var ViewEngineInterface $viewEngine */
$viewEngine = $assembler->get(ViewEngineInterface::class);

$componentManager->registerComponentViews($viewEngine, __DIR__ . '/components');

if (is_dir(__DIR__ . '/templates/auth')) {
    $viewEngine->registerNamespace('Auth', __DIR__ . '/templates/auth');
}

/** @var RouterInterface $router */
$router = $assembler->get(RouterInterface::class);
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
        $namespace = ucfirst($cleanName);

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
    /** @var MiddlewareInterface $correlation */
    $correlation = $c->get(CorrelationIdMiddleware::class);
    /** @var MiddlewareInterface $session */
    $session = $c->get(SessionMiddleware::class);
    /** @var MiddlewareInterface $auth */
    $auth = $c->get(AuthMiddleware::class);

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
