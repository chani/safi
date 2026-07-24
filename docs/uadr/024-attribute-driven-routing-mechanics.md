# µADR-024: Attribute-Driven Routing Mechanics & APCu Coupling
-----
tags: routing attributes performance
status: accepted

## Context
Reflection scanning for #[Route] attributes on every request causes performance overhead.

## Decision
- Routes declared via #[Route] attributes are scanned during initialization and cached permanently in APCu.
- Production runtimes bypass reflection completely by reading frozen APCu data arrays.

## Guardrail / Consequences
Reflection scanning during HTTP request handling in production is prohibited.
