# µADR-016: Package Decoupling & Boundary Isolation

## Context & Decision
Tight coupling between modules creates dependency deadlocks. Packages interact strictly via `safi-core` contracts or event interfaces.

## Rules
- Do: Ensure components (e.g., `safi-component-blog`) can be added or removed independently.
- Do: Use `EventDispatcherInterface` for inter-package communication.
- Don't: Instantiating concrete classes from peer components directly.
