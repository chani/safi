# How-To: Defining Attribute Routes

Declare routes on controller methods using the `#[Route]` attribute.

---

## Basic Route Definition

```php
use Safi\Core\Attributes\Route;
use Safi\Core\Http\Response;

#[Route('/api/status', method: 'GET', name: 'api.status', public: true)]
public function status(): Response
{
    return $this->jsonResponse(['status' => 'OK']);
}
```

---

## Attribute Options

- `path` (string, required): Request URI path.
- `method` (string, default: `'GET'`): HTTP method (`GET`, `POST`, `PUT`, `DELETE`, `PATCH`).
- `name` (string, optional): Unique route name for URL generation.
- `public` (bool, default: `false`): If set to `true`, bypasses default `401 Unauthorized` middleware check.
