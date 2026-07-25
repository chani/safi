# How-To: Generating URLs from Named Routes

Generate relative URLs programmatically using `RouterInterface`.

---

## Code Example

```php
use Safi\Core\Contracts\RouterInterface;

final class NavigationService
{
    public function __construct(private readonly RouterInterface $router) {}

    public function getProfileUrl(int $userId): string
    {
        return $this->router->generateUrl('user.profile', ['id' => $userId]);
    }
}
```
