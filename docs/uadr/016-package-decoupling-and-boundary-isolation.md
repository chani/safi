# µADR-016: Package Decoupling & Boundary Isolation

## Context & Decision

Tight coupling between modules creates dependency deadlocks and vendor lock-in. Packages interact strictly via `safi-core` contracts or event interfaces.

Feature extensions (such as `safi-auth`) operate as self-contained vertical slices with their own domain controllers, but MUST remain completely agnostic of concrete infrastructure drivers (such as ORMs or database adapters).

## Rules

- Do: Ensure components (e.g., `safi-component-blog`) and extensions (e.g., `safi-auth`) can be added or removed independently.
- Do: Interact with persistence layers exclusively via `Safi\Core\Contracts\DatabaseDriverInterface` and `Safi\Core\Contracts\ModelInterface`.
- Do: Keep feature controllers inside their respective extension packages, inheriting directly from `Safi\Core\AbstractController`.
- Do: Use `EventDispatcherInterface` for inter-package communication.
- Don't: Import concrete classes from peer components or ORM drivers (e.g., `safi-db-redbean` or `RedBeanModel`) inside extension packages.
- Don't: Pollute `safi-core` with domain-specific business logic or feature controllers.
