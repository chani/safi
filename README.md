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
* Views are not raw file includes with access to global state. They operate within isolated, sandboxed component namespaces (e.g., `@Incursio/index.twig`).
* View engines are fully decoupled drivers. The default adapter is `safi-view-twig`, but alternative drivers (such as Blade or Native PHP) can be plugged in by implementing `ViewEngineInterface`.

### 3. Secure by Default
Security is integrated into the core execution pipeline rather than left to manual controller checks:
* Inverted Route Protection: All routes are locked by default (returning HTTP 401 Unauthorized). Public endpoints require explicit opt-in via route attribute flags (`public: true`).
* Session Hijacking Protection: Active sessions are bound to a SHA-256 hash of the client's User-Agent string and invalidated on mismatch.
* Template Sandbox Isolation: Twig execution is constrained by a security policy restricting untrusted template tags, raw PHP calls, and un-whitelisted method execution (SSTI defense).
* Automated CSRF Defense: Cross-Site Request Forgery tokens are generated and validated automatically at the pipeline level for state-mutating HTTP requests.
* Constant-Memory Binary Stream Piping: Raw request input streams are piped directly to target resources via native stream descriptors, preventing memory exhaustion attacks on large file uploads.

---

## Driver Architecture

Safi Core (`safi-core`) maintains zero dependencies on specific infrastructure implementations. All technical capabilities are provided via driver packages:

* safi: Main application skeleton, config mapping, and composition root.
* safi-core: Core kernel containing DI autowiring, HTTP Context, and middleware pipeline.
* safi-router-wajha: High-performance router driver for the Wajha routing engine (replaceable by alternative drivers, e.g. FastRoute).
* safi-view-twig: Twig template engine driver featuring security sandbox policies.
* safi-db-redbean: RedBeanPHP persistence driver supporting SQLite WAL and NFS fallback synchronization modes.
* safi-auth: Authentication service, brute-force rate limiting shield, and session management.

---

## Architecture Comparison

| Capability | Safi Architecture | Conventional Frameworks |
| :--- | :--- | :--- |
| Dependency Injection | Constructor autowiring at Composition Root only | Injected Container, Service Locators, Static Facades |
| Data Modeling | Native PHP 8.5 Property Hooks (`BaseModel`) | Manual getter and setter method definitions |
| View Rendering | Sandboxed component namespaces (`ViewEngineInterface`) | Direct script includes or un-sandboxed global views |
| Route Access | Locked by default (401 Unauthorized default) | Open by default; manual middleware binding per route |
| Input Streaming | Memory-mapped zero-copy stream piping | Loading entire upload buffers into memory strings |

---

## Local Development Setup

```bash
git clone [https://github.com/chani/safi.git](https://github.com/chani/safi.git) my-app
cd my-app
composer install
cp config/config.php config/config.local.php
php -S localhost:8000 -t public/
```
