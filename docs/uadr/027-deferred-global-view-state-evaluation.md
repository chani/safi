# µADR-027: Deferred Global View State Evaluation
-----
tags: view performance closures
status: accepted

## Context
Evaluating global view parameters (e.g. session tokens) on non-HTML responses causes unnecessary queries.

## Decision
- Global view variables can be registered as Callables/Closures.
- Evaluation is deferred lazily until render() is invoked.

## Guardrail / Consequences
Global view parameter factories must not be executed during API/JSON responses.
