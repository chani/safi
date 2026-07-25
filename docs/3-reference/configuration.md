# Reference: Configuration & Overlay Architecture

Safi handles configuration through layered, immutable array files processed at the Composition Root (`init.inc.php`).

---

## 1. File Hierarchy

1. **Base Configuration (`config/config.php`):** Committed version-controlled defaults (app settings, database drivers, template paths).
2. **Local Environment Overlay (`config/config.local.php`):** Non-committed, environment-specific overrides (production credentials, local SQLite paths, debug toggles). Ignored by `.gitignore`.

---

## 2. Recursive Merge Mechanics

During bootstrap, configuration files are merged via `array_replace_recursive`:

```php
$config = require __DIR__ . '/config/config.php';
if (file_exists(__DIR__ . '/config/config.local.php')) {
    $localConfig = require __DIR__ . '/config/config.local.php';$config = array_replace_recursive($config,$localConfig);
}
```

This guarantees that nested keys in `config.local.php` partially override specific options without requiring a complete duplication of the base array structure.
