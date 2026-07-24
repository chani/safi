# µADR-026: Synchronous In-Memory Event Dispatcher
-----
tags: events architecture decoupling
status: accepted

## Context
Decoupling domain side-effects (such as login logging) without adding complex event brokers.

## Decision
- The kernel includes a lightweight synchronous EventDispatcher.
- Listeners execute in-memory within the active HTTP request thread.
- Asynchronous workloads must be delegated to JobQueueService.

## Guardrail / Consequences
Event listeners must not execute blocking long-running I/O tasks directly.
