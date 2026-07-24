# µADR-015: Strict FQCN Database Abstraction
-----
tags: database orm fqcn
status: accepted

## Context
Using raw string table names bypasses schema translation rules and namespace protection.

## Decision
- All ORM interactions must pass the Fully Qualified Class Name using Model::class syntax.

## Guardrail / Consequences
Passing raw string table names directly to DatabaseService is prohibited.
