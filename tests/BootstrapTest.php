<?php

declare(strict_types=1);

namespace Tests;

use Components\HelloWorld\Controllers\HelloController;
use PHPUnit\Framework\TestCase;
use Safi\Core\Assembler;
use Safi\Core\Contracts\DatabaseDriverInterface;
use Safi\Core\Contracts\SecurityServiceInterface;
use Safi\Core\Contracts\ViewEngineInterface;
use Safi\Core\Http\Request;
use Safi\Core\Kernel;

final class BootstrapTest extends TestCase
{
    public function testFrameworkBootstrapsCorrectly(): void
    {
        $boot = require __DIR__ . '/../init.inc.php';

        $this->assertIsArray($boot);
        $this->assertArrayHasKey('assembler', $boot);
        $this->assertArrayHasKey('kernel', $boot);

        $this->assertInstanceOf(Assembler::class, $boot['assembler']);
        $this->assertInstanceOf(Kernel::class, $boot['kernel']);
    }

    public function testHelloWorldControllerReturnsResponse(): void
    {
        $view = $this->createMock(ViewEngineInterface::class);
        $view->expects($this->once())
            ->method('render')
            ->with('@HelloWorld/example', $this->callback('is_array'))
            ->willReturn('<h1>Showcase</h1>');

        $request = new Request([], [], ['REQUEST_METHOD' => 'GET', 'REQUEST_URI' => '/hello']);
        $security = $this->createMock(SecurityServiceInterface::class);
        $db = $this->createMock(DatabaseDriverInterface::class);

        $controller = new HelloController($view, $request, $security, $db);
        $response = $controller->index();

        $this->assertSame(200, $response->getStatus());
        $this->assertSame('<h1>Showcase</h1>', $response->getContent());
    }
}
