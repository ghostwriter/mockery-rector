<?php

declare(strict_types=1);

namespace Ghostwriter\MockeryRectorTests\Unit\ReplaceHamcrestWithPHPUnitRector;

use Ghostwriter\MockeryRector\Upgrade\ReplaceHamcrestWithPHPUnitRector;
use Ghostwriter\MockeryRectorTests\Unit\AbstractMockeryRectorTestCase;
use PHPUnit\Framework\Attributes\CoversClass;

#[CoversClass(ReplaceHamcrestWithPHPUnitRector::class)]
final class ReplaceHamcrestWithPHPUnitRectorTest extends AbstractMockeryRectorTestCase
{
}
