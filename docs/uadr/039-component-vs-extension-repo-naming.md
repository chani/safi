# µADR-039: Naming Conventions for Component and Extension Repositories
-----
tags: architecture repository naming components extensions conventions
status: accepted

## Context
Safi distinguishes between infrastructure/driver packages (Extensions) and domain/business feature modules (Components). A clear naming convention at the Git repository and Packagist level is required to avoid architectural ambiguity.

## Decision
1. **Extension Repositories:** MUST NOT use the word `component` in their repository or Composer package name (e.g. `safi-admin-panel`, `safi-auth`, `safi-db-redbean`).
2. **Component Repositories:** MUST use the singular `safi-component-<name>` prefix (e.g. `safi-component-blog`).
3. **Singular Denotation:** The singular term `component` is used because each repository encapsulates exactly one domain module.

## Guardrail / Consequences
- Developers can immediately distinguish infrastructure drivers from domain features by reading the repository name.
- Third-party or decoupled components on Packagist follow a unified naming scheme.
