# µADR-027: Deferred Global Evaluation in Views
-----
tags: twig view-engine deferred-globals performance
status: accepted

## Context
Passing global view variables (such as CSRF tokens or session state) to every template execution causes unnecessary evaluation overhead if the rendered view never uses them.

## Decision
- ViewEngineInterface::addGlobal() accepts callable resolvers (closures).
- Deferred closures are evaluated lazily only when ViewEngineInterface::render() is invoked and the key is actually needed.

## Guardrail / Consequences
Prevents unnecessary database queries and CPU cycles on routes that short-circuit, return early, or render partial/JSON responses.
