<?php

declare(strict_types=1);

namespace Tests\Unit\Rule;

use Ghostwriter\MockeryRector\AbstractMockeryRector;
use Ghostwriter\MockeryRector\Rule\ShouldReceiveToExpectsRector;
use Tests\Unit\AbstractMockeryRectorTestCase;
use PHPUnit\Framework\Attributes\CoversClass;

#[CoversClass(AbstractMockeryRector::class)]
#[CoversClass(ShouldReceiveToExpectsRector::class)]
final class ShouldReceiveToExpectsRectorTest extends AbstractMockeryRectorTestCase
{
}
