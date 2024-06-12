<?php

declare(strict_types=1);

namespace Tests\Unit\Rule;

use Ghostwriter\MockeryRector\AbstractMockeryRector;
use Ghostwriter\MockeryRector\Rule\PHPUnitToMockeryRector;
use PHPUnit\Framework\Attributes\CoversClass;
use Tests\Unit\AbstractMockeryRectorTestCase;

#[CoversClass(PHPUnitToMockeryRector::class)]
#[CoversClass(AbstractMockeryRector::class)]
final class PHPUnitToMockeryRectorTest extends AbstractMockeryRectorTestCase
{
}
