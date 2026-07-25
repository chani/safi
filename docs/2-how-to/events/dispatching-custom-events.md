# How-To: Dispatching and Listening to Synchronous Events

Trigger synchronous domain events using `EventDispatcher`.

---

## 1. Define Event Class

```php
namespace App\Events;

final readonly class UserRegisteredEvent
{
    public function __construct(public int $userId) {}
}
```

---

## 2. Define Listener Class

```php
namespace App\Listeners;

use App\Events\UserRegisteredEvent;

final class SendWelcomeEmailListener
{
    public function handle(UserRegisteredEvent $event): void
    {
        // Send email to $event->userId
    }
}
```

---

## 3. Register Listener in Composition Root

```php
$eventDispatcher = $assembler->get(Safi\Core\Event\EventDispatcher::class);
$eventDispatcher->addListener(App\Events\UserRegisteredEvent::class, App\Listeners\SendWelcomeEmailListener::class);
```

---

## 4. Dispatch Event

```php
$eventDispatcher->dispatch(new UserRegisteredEvent($user->getId()));
```
