# µADR-025: Domain Entity Resolution via findModelOrFail
-----
tags: controller entity database validation exception
status: accepted

## Context
Controllers frequently query database entities by ID and must enforce valid entity existence without scattering repetitive null checks across business logic.

## Decision
- AbstractController::findModelOrFail() loads the model by ID and checks if it exists.
- If the entity ID evaluates to 0 or cannot be resolved, it throws a ValidationException.

## Guardrail / Consequences
Throwing ValidationException triggers the Kernel's central HTTP boundary, rendering a standardized error response instead of requiring manual error checks in controllers.
