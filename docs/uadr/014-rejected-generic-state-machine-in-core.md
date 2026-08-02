# µADR-014: Rejected: Synchronous HTTP State Machine Engine

## Context & Decision
HTTP is inherently stateless. Adding a global state machine to core adds unnecessary request latency and architectural bloat.

## Rules
- Do: Maintain fast, stateless HTTP request handling.
- Don't: Introduce state machine layers into synchronous HTTP execution paths.
