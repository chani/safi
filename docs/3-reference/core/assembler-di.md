# Reference: Assembler (`Safi\Core\Assembler`)

The `Assembler` class is Safi's dependency injection container and autowiring engine. It implements `Psr\Container\ContainerInterface` and `Safi\Core\Contracts\ContainerRegistrarInterface`.

---

## Public Method Signatures

### `set(string $id, callable\vert{}object$factory): void`
Registers a service factory closure or object instance.

### `setInterfaceMap(array $map): void`
Configures an explicit interface-to-concrete implementation mapping array: `['InterfaceClass' => 'ConcreteClass']`.

### `get(string $id): mixed`
Resolves and returns a service instance. Instantiates classes automatically via reflection autowiring if unregistered. Throws `RuntimeException` if unresolvable.

### `has(string $id): bool`
Checks whether a service factory or instance is explicitly registered.
