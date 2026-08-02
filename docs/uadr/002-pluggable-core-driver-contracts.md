# µADR-002: Pluggable Core Driver Contracts & Decoupling

## Context & Decision
Coupling core code to specific third-party packages locks the architecture. Core defines pure Interfaces (Contracts), while implementations live in separate driver repositories.

## Rules
- Do: Depend strictly on `Safi\Core\Contracts\*` interfaces.
- Do: Implement concrete behavior in separate packages (`safi-db-redbean`, `safi-view-twig`).
- Don't: Import concrete driver classes inside `safi-core` or core controllers.
- Don't: Create direct coupling between component packages without explicit interface contracts.
