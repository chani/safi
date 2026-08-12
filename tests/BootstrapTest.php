<?php

declare(strict_types=1);

namespace Tests;

use PHPUnit\Framework\TestCase;
use Safi\Core\Assembler;
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
}
