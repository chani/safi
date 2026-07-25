# µADR-035: Session Fingerprinting via SHA-256
-----
tags: security auth session fingerprinting hijacking
status: accepted

## Context
Session hijacking via stolen cookie identifiers must be mitigated without introducing stateful IP locks, which break under mobile carrier CGNAT network switching.

## Decision
- AuthService computes and stores a SHA-256 hash of the client's User-Agent header in $_SESSION['auth_fingerprint'].
- Every authenticated request validates the current User-Agent hash against the stored session fingerprint.

## Guardrail / Consequences
Any request presenting a valid session cookie with a mismatching User-Agent fingerprint causes immediate session destruction and logout.
