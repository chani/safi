# µADR-029: Absolute Prohibition of Native Object Serialization
-----
tags: security performance serialization
status: accepted

## Context
PHP serialize() and unserialize() introduce Remote Code Execution (RCE) vulnerabilities and tight class coupling.

## Decision
- Native object serialization via serialize()/unserialize() is strictly prohibited across the entire codebase.
- Data persistence and caching must utilize JSON or native PHP array file exports (var_export).

## Guardrail / Consequences
Invoking serialize() or unserialize() in any package is prohibited and blocked by static analysis.
