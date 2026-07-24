# µADR-019: Configurable Database Environment Pragmas
-----
tags: database sqlite nfs
status: accepted

## Context
SQLite WAL mode causes file locking deadlocks on network-attached storage (NFS/SMB).

## Decision
- Implement a configurable db_mode setting ('local' = WAL, 'network_fallback' = TRUNCATE).

## Guardrail / Consequences
SQLite connections must remain stable on network volumes without triggering lock errors.
