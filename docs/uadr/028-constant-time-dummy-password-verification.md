# µADR-028: Constant-Time Dummy Password Verification
-----
tags: security auth timing-attack
status: accepted

## Context
Timing differences between existing and non-existing user authentication expose username enumeration vectors.

## Decision
- If a requested user entity is missing, AuthService executes password_verify against a dummy hash.
- Response time remains constant regardless of user existence.

## Guardrail / Consequences
Authentication routines must execute hash verifications unconditionally.
