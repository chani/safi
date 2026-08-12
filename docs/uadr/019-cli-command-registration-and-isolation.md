# µADR-019: CLI Command Registration & Isolation

## Context & Decision

CLI commands must be explicitly registered or resolved dynamically via the autowiring container without polluting global HTTP runtime execution.

## Rules

- Do: Implement `Safi\Core\Cli\CommandInterface` for all CLI commands.
- Do: Register commands via `CommandKernel::registerCommandClass()`.
- Don't: Execute CLI command logic inside HTTP request lifecycles.
