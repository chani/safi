# µADR-005: Attribute-Driven Routing & Zero-Runtime Reflection

## Context & Decision
Reflection scanning on HTTP requests adds CPU overhead. Routes declared via `#[Route]` are scanned during boot and cached permanently in APCu (`TTL=0`).

## Rules
- Do: Declare HTTP endpoints cleanly using `#[Route('/path', method: 'GET')]`.
- Do: Consume compiled APCu route arrays in production environments.
- Don't: Run Reflection (`ReflectionClass`, `ReflectionMethod`) during HTTP request execution in production.
