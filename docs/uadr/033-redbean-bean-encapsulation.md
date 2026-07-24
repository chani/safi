# µADR-033: RedBean Bean Encapsulation
-----
tags: #orm #redbean #architecture #models
status: accepted
context: Exposing raw RedBean OODBBean instances directly to controllers introduces tight coupling to RedBeanPHP.
decisions:
  - Encapsulate all OODBBean instances behind classes implementing `ModelInterface`.
  - Provide access to raw storage objects strictly via the `unwrap()` method.
consequences:
  - Isolates application code from ORM driver specifics.
