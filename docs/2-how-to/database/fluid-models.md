# How-To: Defining RedBean Fluid Models

Define domain model wrappers using RedBean beans and PHP 8.5 Property Hooks.

---

## Model Definition Example

```php
<?php

declare(strict_types=1);

namespace App\Models;

use Safi\Core\Contracts\ModelInterface;

final class Customer implements ModelInterface
{
    public function __construct(private readonly mixed $entity = null) {}

    #[\Override]
    public function unwrap(): mixed
    {
        return $this->entity;
    }

    #[\Override]
    public function getId(): int
    {
        $id = $this->getProperty('id', 0);
        return is_numeric($id) ? (int) $id : 0;
    }

    public string $email {
        get => is_string($v = $this->getProperty('email', '')) ? $v : '';
        set => $this->setProperty('email', strtolower(trim($value)));
    }

    private function getProperty(string $property, mixed $default = null): mixed
    {
        return is_object($this->entity) ? ($this->entity->{$property} ?? $default) : $default;
    }

    private function setProperty(string $property, mixed $value): void
    {
        if (is_object($this->entity)) {
            $this->entity->{$property} = $value;
        }
    }
}
```
