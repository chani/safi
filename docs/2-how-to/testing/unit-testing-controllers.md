# How-To: Unit Testing Controllers with Pure DI

Unit test controller actions directly by passing mocked or fake dependencies into constructors.

---

## Test Example (PHPUnit)

```php
<?php

declare(strict_types=1);

namespace Tests;

use Components\HelloWorld\Controllers\HelloController;
use PHPUnit\Framework\TestCase;
use Safi\Core\Contracts\DatabaseDriverInterface;
use Safi\Core\Contracts\ViewEngineInterface;
use Safi\Core\Http\Request;
use Safi\Core\Services\SecurityService;

final class HelloControllerTest extends TestCase
{
    public function testIndexReturnsResponse(): void
    {
        $view = $this->createMock(ViewEngineInterface::class);
        $view->expects($this->once())->method('render')->willReturn('<h1>Hello</h1>');

        $request = new Request([], [], ['REQUEST_METHOD' => 'GET', 'REQUEST_URI' => '/hello']);
        $security = $this->createMock(SecurityService::class);
        $db = $this->createMock(DatabaseDriverInterface::class);

        $controller = new HelloController($view, $request, $security, $db);
        $response = $controller->index();

        $this->assertSame(200, $response->getStatus());
        $this->assertSame('<h1>Hello</h1>', $response->getContent());
    }
}
```
