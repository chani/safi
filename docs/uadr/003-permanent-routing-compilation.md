# µADR-003: Permanent APCu Routing Compilation
-----
tags: routing cache apcu performance
status: accepted

## Context
Parsing route definitions on every HTTP request introduces avoidable file I/O overhead.

## Decision
- Compiled route definition arrays are stored permanently in APCu with TTL set to 0.
- Invalidation occurs exclusively via explicit CLI signals (safi cache:clear).

## Guardrail / Consequences
Dynamic route parsing during HTTP request execution in production environments is prohibited.
