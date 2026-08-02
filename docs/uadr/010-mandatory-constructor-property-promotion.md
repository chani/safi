# µADR-010: Constructor Property Promotion & Early Return Guardrails

## Context & Decision
Boilerplate property assignments and nested `if/else` blocks dilute code readability. We enforce PHP 8.5 syntax ergonomics.

## Rules
- Do: Use Constructor Property Promotion for dependency injection (`public function __construct(private DB $db)`).
- Do: Flatten execution paths using early return guards (`if (!valid) return;`).
- Don't: Write manual property assignments inside constructors for standard injections.
- Don't: Use deeply nested conditional structures.
