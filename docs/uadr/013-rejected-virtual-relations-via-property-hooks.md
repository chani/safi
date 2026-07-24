# µADR-013: Virtual Relations via Property Hooks
-----
tags: models property-hooks rejected
status: rejected

## Context
Executing relational queries ($user->posts) inside Property Hooks.

## Decision
- Rejected. Requires Service Locator patterns in models and introduces N+1 query issues.
- Relational lookups must be performed in Service or Repository classes.

## Guardrail / Consequences
Models must remain pure data containers and must not execute database queries.
