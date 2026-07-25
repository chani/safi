# Tutorial: Writing a Custom Extension

This tutorial explains how to build a custom driver extension by implementing `ServiceProviderInterface`.

---

## Step 1: Define Service Provider Class

Create `src/CustomServiceProvider.php` inside your extension package:

```php
<?php

declare(strict_types=1);

namespace Safi\Extensions\Custom;

use Psr\Container\ContainerInterface;
use Safi\Core\Contracts\ContainerRegistrarInterface;
use Safi\Core\Contracts\ServiceProviderInterface;

final class CustomServiceProvider implements ServiceProviderInterface
{
    public function __construct(private readonly string $option = 'default') {}

    #[\Override]
    public function register(ContainerRegistrarInterface $registrar): void
    {
        $registrar->set(CustomService::class, fn(ContainerInterface $c): CustomService => new CustomService($this->option));
    }

    #[\Override]
    public function boot(ContainerInterface $container): void
    {
        /** @var CustomService $service */
        $service = $container->get(CustomService::class);
        $service->initialize();
    }
}
```

---

## Step 2: Register Provider in Composition Root

Open `init.inc.php` and add the provider instance to `$componentManager->bootProviders()`:

```php
$componentManager->bootProviders([
    new RedBeanServiceProvider($dsn, $dbMode),
    new WajhaServiceProvider(),
    new AuthServiceProvider(),
    new TwigServiceProvider($templateDir, $cacheDir, $debug),
    new CustomServiceProvider('custom_value'),
]);
```
