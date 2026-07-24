# µADR-011: Abstract Driver Architecture for Enterprise Databases
-----
tags: database orm drivers
status: accepted

## Context
Heavy components may require external engines (e.g. PostgreSQL) while core runs on SQLite.

## Decision
- DatabaseService implements connection drivers to support external database connections.
- Core system tables remain on local SQLite storage.

## Guardrail / Consequences
Framework core must remain operational without requiring external network database servers.
