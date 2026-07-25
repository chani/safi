# µADR-038: Standardized Query Parameter Redirect Construction
-----
tags: controller http response redirect query-params
status: accepted

## Context
Constructing HTTP redirects with dynamic query parameters across controllers often leads to duplicated code and malformed query strings.

## Decision
- AbstractController::redirect() accepts an optional $queryParams array.
- Query strings are formatted using http_build_query() and automatically appended with proper ? or & delimiters.

## Guardrail / Consequences
Eliminates manual string concatenation in controllers and prevents malformed Location headers.
