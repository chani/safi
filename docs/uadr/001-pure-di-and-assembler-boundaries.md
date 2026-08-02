# µADR-001: Pure Dependency Injection & Composition Root Boundaries

## Context & Decision
DI containers used as Service Locators create hidden dependencies. We enforce Pure DI where the `Assembler` acts purely as Composition Root restricted to application entrypoints.

## Rules
- Do: Use Constructor Injection with explicit type-hints across all services and controllers.
- Do: Use `Assembler` strictly inside entrypoints (`index.php`, `init.inc.php`, CLI scripts).
- Don't: Type-hint or inject `Assembler` or `ContainerInterface` inside Services, Controllers, or Drivers.
- Don't: Use Service Locators, Static Registries, or global container access in domain code.
