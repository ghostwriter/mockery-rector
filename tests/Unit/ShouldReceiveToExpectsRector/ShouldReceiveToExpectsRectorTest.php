<?php

declare(strict_types=1);

namespace Ghostwriter\MockeryRectorTests\Unit\ShouldReceiveToExpectsRector;

use Ghostwriter\MockeryRector\AbstractMockeryRector;
use Ghostwriter\MockeryRector\Rule\ShouldReceiveToExpectsRector;
use Ghostwriter\MockeryRectorTests\Unit\AbstractMockeryRectorTestCase;
use PHPUnit\Framework\Attributes\CoversClass;

#[CoversClass(AbstractMockeryRector::class)]
#[CoversClass(ShouldReceiveToExpectsRector::class)]
final class ShouldReceiveToExpectsRectorTest extends AbstractMockeryRectorTestCase
{
}
