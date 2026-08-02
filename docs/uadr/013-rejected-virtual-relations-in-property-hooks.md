# µADR-013: Rejected: Database Queries in Property Hooks

## Context & Decision
Executing database lookups inside PHP 8.4+ Property Hooks introduces hidden N+1 queries and Service Locator anti-patterns.

## Rules
- Do: Use Property Hooks strictly for in-memory model state calculations (`$model->isExpired`).
- Don't: Execute database queries or network lookups inside Property Hooks.
