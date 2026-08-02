# µADR-009: Extension vs. Component Naming Conventions

## Context & Decision
Mixing infrastructure packages with business feature modules creates repo ambiguity. We enforce strict repository naming conventions.

## Rules
- Do: Name domain feature modules using singular `safi-component-<name>` (e.g., `safi-component-blog`).
- Don't: Include the word `component` in infrastructure or driver packages (e.g., `safi-auth`, `safi-session`).
