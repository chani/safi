# µADR-011: Model Inference & Controller Exception Mapping

## Context & Decision
Repeating entity existence checks across controllers violates DRY. `AbstractController` handles model resolution and error mapping automatically.

## Rules
- Do: Use `$this->findModelOrFail(User::class, $id)` inside controller actions.
- Don't: Write manual `if (!$model) throw ...` check blocks in controllers.
