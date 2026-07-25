# Reference: `safi-router-wajha`

Wajha routing driver adapter for `safi-core`. Implements `Safi\Core\Contracts\RouterInterface`.

---

## Implementation Details

- **Class:** `Safi\Extensions\RouterWajha\WajhaRouterAdapter`
- **Compiler:** Compiles static and dynamic route trees using `Safi\Wajha\WajhaCompiler`.
- **Performance:** Direct opcode invocation for array tuple handlers to minimize reflection overhead (µADR-032).
- **Fallback Handling:** Emits HTTP 404 for unmatched routes and HTTP 405 for method mismatches.
