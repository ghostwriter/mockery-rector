<?php

declare(strict_types=1);

namespace Ghostwriter\MockeryRectorTests\Unit\ProphecyToMockeryRector;

use Ghostwriter\MockeryRector\AbstractMockeryRector;
use Ghostwriter\MockeryRector\Rule\ProphecyToMockeryRector;
use Ghostwriter\MockeryRectorTests\Unit\AbstractMockeryRectorTestCase;
use PHPUnit\Framework\Attributes\CoversClass;

#[CoversClass(ProphecyToMockeryRector::class)]
#[CoversClass(AbstractMockeryRector::class)]
final class ProphecyToMockeryRectorTest extends AbstractMockeryRectorTestCase
{
}
