# Architectural Rationale: Pure DI vs. Service Locators

Safi strictly enforces Pure Dependency Injection and bans the Service Locator pattern, static facades, and global container access.

---

## The Problem with Service Locators and Facades

Frameworks using global container lookups introduce hidden dependencies:

1. **Obfuscated Contracts:** A class constructor appears parameterless, but internal logic depends on global database connections or session handlers.
2. **Brittle Unit Testing:** Mocking static calls or global container state requires complex runtime manipulation.
3. **High Coupling:** Business logic becomes tightly coupled to the framework container instance.

---

## The Safi Approach

1. **Explicit Composition Root:** Dependency resolution is isolated to application startup (`init.inc.php` or CLI entry points).
2. **Constructor Injection:** Domain classes request exact dependencies through interface types in constructors.
3. **Immutability & Predictability:** Classes receive resolved dependencies during instantiation and cannot pull external state from the container at runtime.
