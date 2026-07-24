<?php

/**
 * Safi Microframework Skeleton
 * @author Jean Bruenn
 * @copyright 2026 All Rights Reserved
 */

declare(strict_types=1);

namespace Components\HelloWorld\Models;

use Safi\Core\Contracts\ModelInterface;

/**
 * @psalm-suppress UndefinedPropertyAssignment
 * @psalm-suppress UndefinedPropertyFetch
 */
final class SystemMetric implements ModelInterface
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

    public string $key {
        get {
            $key = $this->getProperty('metric_key', '');
            return is_string($key) ? $key : '';
        }
        set {
            $this->setProperty('metric_key', trim($value));
        }
    }

    public string $value {
        get {
            $val = $this->getProperty('metric_value', '');
            return is_string($val) ? $val : '';
        }
        set {
            $this->setProperty('metric_value', trim($value));
        }
    }

    private function getProperty(string $property, mixed $default = null): mixed
    {
        if (is_object($this->entity) && property_exists($this->entity, $property)) {
            /** @phpstan-ignore property.notFound */
            return $this->entity->{$property};
        }

        return $default;
    }

    private function setProperty(string $property, mixed $value): void
    {
        if (is_object($this->entity)) {
            /** @phpstan-ignore property.notFound */
            $this->entity->{$property} = $value;
        }
    }
}
