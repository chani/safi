# How-To: Executing Database Transactions

Execute database operations inside an ACID transaction boundary using `DatabaseDriverInterface::transaction()`.

---

## Transaction Execution Example

```php
use Safi\Core\Contracts\DatabaseDriverInterface;

$db->transaction(function (DatabaseDriverInterface $driver) use ($user, $account): void {
    $driver->storeModel($user);
    $driver->storeModel($account);
});
```
