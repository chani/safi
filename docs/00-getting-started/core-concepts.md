# Core Architecture Concepts

Safi differs from conventional PHP frameworks by enforcing three architectural constraints: Pure Dependency Injection, Inverted Route Protection, and Isolated View Namespaces.

---

## 1. Pure Dependency Injection

Safi prohibits global service locators, static facades (`User::find()`), and container injection into domain classes.

- The DI container (`Assembler`) exists strictly at the Composition Root (`init.inc.php` or CLI entry points).
- Controllers, services, and repositories declare required dependencies explicitly through constructor parameters using PHP 8.5 property promotion.
- Autowiring resolves class dependencies automatically during application startup.

---

## 2. Inverted Route Protection

All HTTP endpoints are locked by default.

- Unannotated or default routes return HTTP 401 Unauthorized automatically.
- Publicly accessible endpoints require explicit opt-in via the `public: true` flag in the `#[Route]` attribute.

---

## 3. Thin Controllers & AbstractController

Controllers inherit from `Safi\Core\AbstractController` and perform three tasks:

1. Validating input parameters and CSRF tokens.
2. Delegating business operations to domain services or database drivers.
3. Returning a `Safi\Core\Http\Response` instance.

---

## 4. HTTP Context & Middleware Pipeline

Requests flow through a single `MiddlewarePipeline` carrying a unified `Context` object containing the `Request`, `Response`, and `LoggerInterface`.

```text
Request ──► CorrelationIdMiddleware ──► AuthMiddleware ──► Router Dispatch ──► Controller
```
