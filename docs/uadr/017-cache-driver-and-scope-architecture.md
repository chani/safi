# µADR-017: Cache Driver & Scope Architecture

## Context & Decision

Caching requirements vary between transient session states, permission trees, and rate limits. Cache storage is divided into logical scopes configured in `config/cache.php`.

## Rules

- Do: Configure scope mappings (`session`, `auth`, `rbac`) to driver targets in `config/cache.php`.
- Do: Fallback gracefully to file or in-memory drivers when APCu/Redis is unavailable.
- Don't: Hardcode concrete cache client instances inside domain components.
