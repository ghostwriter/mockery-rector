<?php

declare(strict_types=1);

namespace Ghostwriter\MockeryRectorTests\Unit\PHPUnitToMockeryRector;

use Ghostwriter\MockeryRector\Migrate\PHPUnitToMockeryRector;
use Ghostwriter\MockeryRectorTests\Unit\AbstractMockeryRectorTestCase;
use PHPUnit\Framework\Attributes\CoversClass;

#[CoversClass(PHPUnitToMockeryRector::class)]
final class PHPUnitToMockeryRectorTest extends AbstractMockeryRectorTestCase
{
}
