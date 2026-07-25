# Reference: `safi-db-redbean`

RedBeanPHP persistence driver adapter for `safi-core`. Implements `Safi\Core\Contracts\DatabaseDriverInterface`.

---

## Features

- **Class:** `Safi\Extensions\DbRedBean\RedBeanDatabaseDriver`
- **Model Dispensing:** Wraps RedBean OODBBean instances inside `ModelInterface` wrappers.
- **Production Mode:** Freezes database schema alterations automatically when `$mode === 'production'`.
- **Slow Query Telemetry:** Dispatches `Safi\Extensions\DbRedBean\Events\SlowQueryEvent` when query duration exceeds 100ms.
