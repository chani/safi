# Safi Microframework

Safi (sāfī / صافي) translates to "pure" or "clear" in literary Arabic. In colloquial dialects, it translates to "enough" or "done" — reflecting the framework's goal of executing HTTP lifecycles cleanly and finishing without state residue.

Safi is a lightweight PHP 8.5 microframework engineered around explicit Model-View-Controller boundaries, pure dependency injection, and isolated component architecture.

---

## Core Architecture Principles

### 1. Pure Dependency Injection (Zero Service Locators)

Safi enforces strict Pure Dependency Injection:

- The autowiring engine (`Assembler`) exists strictly at the Composition Root (`public/index.php` and CLI entry points).
- Injecting or referencing the container inside controllers, services, or models is prohibited.
- All class dependencies must be requested explicitly via constructor parameters.

### 2. Real Web MVC & Pluggable Views

Safi separates application logic from template rendering via `ViewEngineInterface`:

- Business application modules live as standalone MVC slices in `components/<Name>/` (e.g., `@Blog/index.twig`).
- View engines are fully decoupled drivers. The default adapter is `safi-view-twig`.

### 3. Secure Defaults

Security is integrated directly into the core execution pipeline:

- Session Hijacking Protection: Active sessions are bound to a SHA-256 hash of the client's User-Agent string.
- Automated CSRF Defense: Cross-Site Request Forgery tokens are generated and validated automatically at the pipeline level.
- Constant-Memory Binary Stream Piping: Raw request input streams are piped directly to target resources via native stream descriptors.

---

## Packages

- **safi:** Application skeleton, configuration mapping, and composition root.
- **safi-core:** Core kernel containing DI autowiring, HTTP Context, and middleware pipeline.
- **safi-router-wajha:** Router driver for the Wajha routing engine.
- **safi-view-twig:** Twig template engine driver.
- **safi-db-redbean:** RedBeanPHP persistence driver.
- **safi-session:** Session management service and middleware.
- **safi-auth:** Authentication service, brute-force shield, and RBAC.
- **safi-admin-panel:** Admin UI, system metrics, and route/DI inspector.

---

## Local Development Setup

```bash
git clone https://github.com/chani/safi.git my-app
cd my-app
composer install
cp config/config.php config/config.local.php
php bin/safi auth:init
php -S localhost:8000 -t public/
```
