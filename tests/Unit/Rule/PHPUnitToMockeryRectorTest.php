<?php

declare(strict_types=1);

namespace Ghostwriter\MockeryRectorTests\Unit\Rule;

use Ghostwriter\MockeryRector\AbstractMockeryRector;
use Ghostwriter\MockeryRector\Rule\PHPUnitToMockeryRector;
use Ghostwriter\MockeryRectorTests\Unit\AbstractMockeryRectorTestCase;
use PHPUnit\Framework\Attributes\CoversClass;

#[CoversClass(PHPUnitToMockeryRector::class)]
#[CoversClass(AbstractMockeryRector::class)]
final class PHPUnitToMockeryRectorTest extends AbstractMockeryRectorTestCase
{
}
