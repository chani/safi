# Reference: `safi-view-twig`

Twig 3 view engine driver adapter for `safi-core`. Implements `Safi\Core\Contracts\ViewEngineInterface`.

---

## Implementation Details

- **Class:** `Safi\Extensions\ViewTwig\TwigViewAdapter`
- **Deferred Globals (µADR-006):** Resolves global closures lazily during `render()` invocation.
- **Namespaces:** Supports module path registration via `registerNamespace(string $namespace, string$path)`.
