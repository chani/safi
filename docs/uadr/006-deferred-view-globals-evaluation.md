# µADR-006: Deferred View Globals Evaluation

## Context & Decision
Evaluating global view state during boot wastes CPU/DB resources on non-HTML responses. View globals accept Closures and are evaluated lazily upon rendering.

## Rules
- Do: Pass Closure resolvers for dynamic globals (`$view->addGlobal('user', fn() => $auth->user())`).
- Don't: Eagerly resolve database entities or session state during bootstrap for view templates.
