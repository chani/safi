# Safi Microframework Architecture Reference

This document outlines the architectural boundaries, lifecycle flow, and design patterns implemented across the Safi Microframework ecosystem.

---

## 1. Composition Root & Dependency Injection

Safi prohibits Service Locators, global containers, and static facades in application logic.
* **Composition Root:** Dependency wiring occurs exclusively during bootstrapping (`init.inc.php`).
* **Pure Constructor Injection:** Components, controllers, and services request required contracts via explicit constructor parameters.
* **Assembler (`Safi\Core\Assembler`):** Handles autowiring and interface-to-implementation resolution without being accessible inside domain code.

---

## 2. HTTP Execution Pipeline

Every incoming HTTP request flows through a unified execution pipeline:

```text
HTTP Request
  │
  ▼
public/index.php (Instantiation of Safi\Core\Http\Request)
  │
  ▼
Kernel::handle(Request)
  │
  ▼
Middleware Pipeline
  ├── CorrelationIdMiddleware (Attaches tracing identifiers)
  ├── AuthMiddleware (Enforces route access controls)
  └── Custom Middlewares
        │
        ▼
Router::dispatch() (Matches Attribute Routes #[Route])
  │
  ▼
Controller Execution (Returns Safi\Core\Http\Response)
  │
  ▼
Response::send()
```

---

## 3. Persistence & ORM Abstraction (`µADR-036`, `µADR-037`)

Database operations are completely decoupled from concrete storage engines:
* **`DatabaseDriverInterface`:** Defines core persistence operations (`dispenseModel`, `loadModel`, `findModels`, `findOneModel`, `storeModel`, `trashModel`).
* **`ModelInterface`:** Lightweight entity wrappers encapsulate storage beans without schema annotations or ORM tight-coupling.
* **Driver Independence:** Application code operates strictly against model interfaces, allowing drivers (e.g., `safi-db-redbean` or `safi-db-lynadb`) to be swapped without modifying domain logic.

---

## 4. Security Framework

* **Default Route Lockdown:** Endpoints require authentication unless explicitly configured with `public: true`.
* **Session Protection:** Active HTTP sessions are verified against SHA-256 hashes of client User-Agent strings.
* **CSRF Mitigation:** State-mutating requests (`POST`, `PUT`, `DELETE`) require valid CSRF token validation via `$this->validateCsrf()`.
