# Safi Microframework

Safi (sāfī / صافي) translates to "pure" or "clear" in literary Arabic. In colloquial dialects (such as Darija), it translates to "enough", "done", or "it is okay" — reflecting the framework's goal of executing HTTP lifecycles cleanly and finishing without state residue.

Safi is a lightweight PHP 8.5 microframework engineered around explicit Model-View-Controller boundaries, pure dependency injection, and isolated component architecture.

---

## Core Architecture Principles

### 1. Pure Dependency Injection (Zero Service Locators)
Unlike frameworks that inject container instances (`ContainerInterface`) into controllers or rely on global static facades (`User::find()`, `app()`), Safi enforces strict Pure Dependency Injection:
* The autowiring engine (`Assembler`) exists strictly at the Composition Root (`public/index.php` and CLI entry points).
* Injecting or referencing the container inside controllers, services, or models is prohibited.
* All class dependencies must be requested explicitly via constructor parameters.

### 2. Real Web MVC & Pluggable Views
Safi separates application logic from template rendering via the `ViewEngineInterface`:
* Views operate within isolated component namespaces (e.g., `@Incursio/index.twig`).
* View engines are fully decoupled drivers. The default adapter is `safi-view-twig`.

### 3. Secure Defaults
Security is integrated directly into the core execution pipeline:
* Session Hijacking Protection: Active sessions are bound to a SHA-256 hash of the client's User-Agent string and invalidated on mismatch.
* Automated CSRF Defense: Cross-Site Request Forgery tokens are generated and validated automatically at the pipeline level for state-mutating HTTP requests.
* Constant-Memory Binary Stream Piping: Raw request input streams are piped directly to target resources via native stream descriptors.

---

## Driver Architecture

Safi Core (`safi-core`) maintains zero dependencies on specific infrastructure implementations. All technical capabilities are provided via driver packages:

* safi: Main application skeleton, config mapping, and composition root.
* safi-core: Core kernel containing DI autowiring, HTTP Context, and middleware pipeline.
* safi-router-wajha: High-performance router driver for the Wajha routing engine.
* safi-view-twig: Twig template engine driver.
* safi-db-redbean: RedBeanPHP persistence driver.
* safi-auth: Authentication service, persistent brute-force shield, and session management.

---

## Architecture Comparison

| Capability | Safi Architecture | Conventional Frameworks |
| :--- | :--- | :--- |
| Dependency Injection | Constructor autowiring at Composition Root only | Injected Container, Service Locators, Static Facades |
| Data Modeling | Explicit Model Contracts (`ModelInterface`) | Dynamic ActiveRecord models |
| View Rendering | Isolated component namespaces (`ViewEngineInterface`) | Direct script includes or un-sandboxed global views |
| Input Streaming | Memory-mapped zero-copy stream piping | Loading entire upload buffers into memory strings |

---

## Local Development Setup

```bash
git clone [https://github.com/chani/safi.git](https://github.com/chani/safi.git) my-app
cd my-app
composer install
cp config/config.php config/config.local.php
php bin/safi auth:init
php -S localhost:8000 -t public/
```
