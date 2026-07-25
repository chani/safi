# How-To: Using Deferred View Globals

Register lazy-evaluated global variables in `ViewEngineInterface` (µADR-027) to avoid executing unnecessary operations when templates do not reference the variable.

---

## Registering Deferred Globals

Pass a closure to `addGlobal()` or `addDeferredGlobal()`:

```php
/** @var ViewEngineInterface $viewEngine */
$viewEngine = $assembler->get(ViewEngineInterface::class);

$viewEngine->addGlobal('csrf_token', fn(): string => $security->getCsrfToken());
```
