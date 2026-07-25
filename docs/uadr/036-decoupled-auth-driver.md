# µADR-036: Decoupled Authentication via DatabaseDriverInterface
-----
tags: auth database abstraction interface decoupling
status: accepted

## Context
The safi-auth package must not hold direct dependencies on specific ORM implementations or hardcoded database drivers.

## Decision
- AuthService interacts with user entities exclusively through the DatabaseDriverInterface contract.

## Guardrail / Consequences
Allows swapping the persistence driver (RedBean, Doctrine, or raw PDO) without modifying authentication logic or security primitives.
