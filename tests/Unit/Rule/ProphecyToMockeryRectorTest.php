<?php

declare(strict_types=1);

namespace Tests\Unit\Rule;

use Ghostwriter\MockeryRector\AbstractMockeryRector;
use Ghostwriter\MockeryRector\Rule\ProphecyToMockeryRector;
use PHPUnit\Framework\Attributes\CoversClass;
use Tests\Unit\AbstractMockeryRectorTestCase;

#[CoversClass(ProphecyToMockeryRector::class)]
#[CoversClass(AbstractMockeryRector::class)]
final class ProphecyToMockeryRectorTest extends AbstractMockeryRectorTestCase
{
}
