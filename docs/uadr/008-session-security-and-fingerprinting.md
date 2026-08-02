# µADR-008: Session Security & Hijacking Protection

## Context & Decision
Stolen session cookies allow client impersonation. Sessions are tied to a SHA-256 fingerprint generated from the client User-Agent string.

## Rules
- Do: Validate the SHA-256 User-Agent fingerprint on every authenticated request.
- Don't: Retain session state when a fingerprint mismatch occurs (destroy session immediately).
