# safi (Skeleton) — TODO

## App & Admin UI

- [ ] Build auto-generated permission matrix UI (`/admin/roles`) with delegation and visibility controls.
- [ ] Add session termination button to `templates/auth/sessions.twig`.
- [ ] Add CLI retention purge command (`php bin/safi retention:purge`) for logs and expired sessions.
- [ ] Add `<div id="form-error"></div>` placeholders to `login.twig` and `users.twig` for HTMX error swaps.

## Operations Center & Operations UI

- [ ] Refactor Operations Room dashboard layout to Pico.css CSS Grid.
- [ ] Implement environment health check matrix (PHP extensions, file permissions).
- [ ] Add `/healthz` JSON health probe endpoint.
- [ ] Add log stream viewer (`/admin/logs`).
- [ ] Implement Write-Through Materialized Views for caching rendered HTMX partials in APCu.
