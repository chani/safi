# µADR-012: PSR-7 / PSR-15 Compliance via Bridge Adapters

## Context & Decision
Direct PSR-7 implementation in core execution paths adds object allocation overhead. Core HTTP classes stay native, while PSR compatibility is handled via outer adapters.

## Rules
- Do: Keep native framework Request/Response classes lightweight and native.
- Don't: Force PSR-7/15 interface implementations into primary execution hot paths.
