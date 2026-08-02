# µADR-004: Prohibition of Native Object Serialization

## Context & Decision
PHP `serialize()` / `unserialize()` introduces RCE vulnerabilities and tight class coupling. Native object serialization is strictly prohibited.

## Rules
- Do: Use standard JSON (`json_encode` / `json_decode`) or PHP array exports (`var_export`) for persistence.
- Don't: Use `serialize()` or `unserialize()` anywhere in the codebase.
