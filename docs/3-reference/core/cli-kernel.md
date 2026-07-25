# Reference: Command Kernel (`Safi\Core\Cli\*`)

CLI execution engine and command interfaces.

---

## Interfaces & Classes

### `CommandInterface`
- `getName(): string`: Unique CLI invocation name (e.g., `jobs:worker`).
- `getDescription(): string`: Summary displayed in `--help`.
- `getCategory(): string`: Grouping category for CLI list.
- `execute(array $args): int`: Execution logic returning integer exit status (`0` for success).

### `CommandKernel`
- `registerCommand(CommandInterface $command): void`: Registers command instance.
- `registerCommandClass(string $commandClass): void`: Resolves command via container and registers.
- `run(array $argv): int`: Parses `$argv` and executes matching command.
