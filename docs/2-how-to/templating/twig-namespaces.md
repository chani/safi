# How-To: Registering Twig Component Namespaces

Isolate component view templates using registered Twig namespaces.

---

## Registering Namespace in Composition Root

Register the directory mapping in `init.inc.php`:

```php
/** @var ViewEngineInterface $viewEngine */
$viewEngine = $assembler->get(ViewEngineInterface::class);

$viewEngine->registerNamespace('Invoice', __DIR__ . '/components/Invoice/Views');
```

---

## Rendering Component Templates

Reference the namespace using the `@Namespace/template.twig` syntax in controllers:

```php
return $this->render('@Invoice/show.twig', ['id' => $id]);
```
