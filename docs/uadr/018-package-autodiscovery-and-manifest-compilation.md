# µADR-018: Package Auto-Discovery & Manifest Compilation

## Context & Decision

Scanning vendor directories on every HTTP request introduces disk I/O bottlenecks. Package templates, components, and routes are discovered via Composer's `InstalledVersions` API during boot and compiled into `data/cache/package_manifest.php`.

## Rules

- Do: Compile discovered package paths to `package_manifest.php` in production environments.
- Do: Recompile manifest automatically when `$debug === true`.
- Don't: Execute filesystem scans (`scandir`, `glob`) on every HTTP request in production.
