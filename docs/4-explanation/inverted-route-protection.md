# Architectural Rationale: Inverted Route Protection

Safi implements a "Secure by Default" model for route access control.

---

## Default Access Denied

In traditional frameworks, routes are public by default unless explicit authentication middleware is attached. Forgetting to attach middleware exposes private endpoints to unauthenticated users.

---

## Safi Model: Explicit Opt-In

1. **Default State:** All routes registered in Safi require an authenticated session.
2. **Pipeline Rejection:** If `AuthMiddleware` processes a route without `public: true`, unauthenticated requests receive HTTP 401 Unauthorized or a redirect to `/login`.
3. **Explicit Exemption:** Public endpoints must explicitly declare `public: true` in their `#[Route]` attribute:

```php
#[Route('/about', method: 'GET', public: true)]
```
