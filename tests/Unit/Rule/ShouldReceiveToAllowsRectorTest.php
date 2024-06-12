<?php

declare(strict_types=1);

namespace Tests\Unit\Rule;

use Ghostwriter\MockeryRector\AbstractMockeryRector;
use Ghostwriter\MockeryRector\Rule\ShouldReceiveToAllowsRector;
use PHPUnit\Framework\Attributes\CoversClass;
use Tests\Unit\AbstractMockeryRectorTestCase;

#[CoversClass(ShouldReceiveToAllowsRector::class)]
#[CoversClass(AbstractMockeryRector::class)]
final class ShouldReceiveToAllowsRectorTest extends AbstractMockeryRectorTestCase
{
}
