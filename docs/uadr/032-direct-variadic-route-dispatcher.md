# µADR-032: Direct Variadic Invocation in Route Dispatcher
-----
tags: routing wajha performance variadic zend-vm
status: accepted

## Context
Invoking controller actions in the HTTP dispatch hot-path using call_user_func_array() or Reflection introduces allocation overhead and performance penalties on every request.

## Decision
- WajhaRouterAdapter invokes controller actions directly via PHP's variadic unpacking operator ($controller->$method(...$vars)).

## Guardrail / Consequences
Compiles directly to native Zend VM opcodes (ZEND_DO_FCALL_BY_NAME), avoiding heap allocations and reflection frame generation during route dispatch.
