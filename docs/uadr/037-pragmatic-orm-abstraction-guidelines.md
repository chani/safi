# µADR-037: Pragmatic ORM Abstraction and Model Query Strategy
-----
tags: #orm #redbean #lynadb #architecture #models
status: accepted
context: Falling back to raw SQL SELECT queries in controllers invalidates the purpose of an ORM abstraction layer.
decisions:
  - Utilize ORM model abstractions (`findModels`, `findOneModel`, `loadModel`, `storeModel`, `trashModel`) for all application-level database operations.
  - Eliminate raw SQL `SELECT`, `DELETE`, and `INSERT` statements in domain logic and controllers.
consequences:
  - Maximizes ORM capabilities without introducing complex mapping configuration.
