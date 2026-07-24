# µADR-035: Session Security and User-Agent Fingerprinting
-----
tags: #security #session #auth
status: accepted
context: Session hijacking poses risks in stateless microframework environments.
decisions:
  - Regenerate session IDs upon authentication state transitions.
  - Bind active sessions to a SHA-256 hash of the client's User-Agent string.
consequences:
  - Automatically terminates hijacked sessions if client User-Agent strings mismatch.
