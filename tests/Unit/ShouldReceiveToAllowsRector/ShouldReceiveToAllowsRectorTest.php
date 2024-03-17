<?php

declare(strict_types=1);

namespace Ghostwriter\MockeryRectorTests\Unit\ShouldReceiveToAllowsRector;

use Ghostwriter\MockeryRector\AbstractMockeryRector;
use Ghostwriter\MockeryRector\Rule\ShouldReceiveToAllowsRector;
use Ghostwriter\MockeryRectorTests\Unit\AbstractMockeryRectorTestCase;
use PHPUnit\Framework\Attributes\CoversClass;

#[CoversClass(ShouldReceiveToAllowsRector::class)]
#[CoversClass(AbstractMockeryRector::class)]
final class ShouldReceiveToAllowsRectorTest extends AbstractMockeryRectorTestCase
{
}
