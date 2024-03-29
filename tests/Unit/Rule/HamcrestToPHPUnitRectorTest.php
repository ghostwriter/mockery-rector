<?php

declare(strict_types=1);

namespace Ghostwriter\MockeryRectorTests\Unit\Rule;

use Ghostwriter\MockeryRector\AbstractMockeryRector;
use Ghostwriter\MockeryRector\Rule\HamcrestToPHPUnitRector;
use Ghostwriter\MockeryRectorTests\Unit\AbstractMockeryRectorTestCase;
use PHPUnit\Framework\Attributes\CoversClass;

#[CoversClass(AbstractMockeryRector::class)]
#[CoversClass(HamcrestToPHPUnitRector::class)]
final class HamcrestToPHPUnitRectorTest extends AbstractMockeryRectorTestCase
{
}
