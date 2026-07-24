# µADR-022: Strict Template Sandbox Isolation
-----
tags: view twig security sandbox
status: accepted

## Context
Preventing Server-Side Template Injection (SSTI) risks in Twig templates.

## Decision
- Enforce Twig SandboxExtension with explicit whitelists for tags and filters.
- Allowed domain model properties must be registered explicitly in the view adapter policy.

## Guardrail / Consequences
Unrestricted method or property execution in Twig templates is prohibited.
