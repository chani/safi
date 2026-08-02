# µADR-003: Strict FQCN Database Abstraction & RedBean Encapsulation

## Context & Decision
Raw table strings bypass schema protection and leaking ORM beans breaks domain encapsulation. Operations use FQCNs (`Model::class`) and beans are wrapped inside domain models.

## Rules
- Do: Use `Model::class` FQCN syntax for all ORM/Database driver interactions.
- Do: Encapsulate raw `OODBBean` instances inside `RedBeanModel` / `ModelInterface`.
- Don't: Write raw SQL strings (`SELECT`, `INSERT`, `UPDATE`, `DELETE`) or pass raw table strings in application code.
- Don't: Expose raw `OODBBean` instances outside the database driver (`unwrap()` is driver-internal only).
