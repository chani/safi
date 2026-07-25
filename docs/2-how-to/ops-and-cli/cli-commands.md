# How-To: Registering CLI Commands

Create custom command-line interface tools using `CommandInterface` and `CommandKernel`.

---

## Command Class Definition

```php
<?php

declare(strict_types=1);

namespace App\Commands;

use Safi\Core\Cli\CommandInterface;

final readonly class CleanupCommand implements CommandInterface
{
    #[\Override]
    public function getName(): string
    {
        return 'sys:cleanup';
    }

    #[\Override]
    public function getDescription(): string
    {
        return 'Cleans up temporary cache files.';
    }

    #[\Override]
    public function getCategory(): string
    {
        return 'system';
    }

    #[\Override]
    public function execute(array $args): int
    {
        fwrite(STDOUT, "Executing system cleanup...\n");
        return 0;
    }
}
```
