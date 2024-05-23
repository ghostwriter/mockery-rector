<?php

declare(strict_types=1);

namespace Tests\Unit\Rule;

use Ghostwriter\MockeryRector\AbstractMockeryRector;
use Ghostwriter\MockeryRector\Rule\ProphecyToMockeryRector;
use Tests\Unit\AbstractMockeryRectorTestCase;
use PHPUnit\Framework\Attributes\CoversClass;

#[CoversClass(ProphecyToMockeryRector::class)]
#[CoversClass(AbstractMockeryRector::class)]
final class ProphecyToMockeryRectorTest extends AbstractMockeryRectorTestCase
{
}
