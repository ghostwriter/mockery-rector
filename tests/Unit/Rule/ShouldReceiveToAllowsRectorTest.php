<?php

declare(strict_types=1);

namespace Tests\Unit\Rule;

use Ghostwriter\MockeryRector\AbstractMockeryRector;
use Ghostwriter\MockeryRector\Rule\ShouldReceiveToAllowsRector;
use Tests\Unit\AbstractMockeryRectorTestCase;
use PHPUnit\Framework\Attributes\CoversClass;

#[CoversClass(ShouldReceiveToAllowsRector::class)]
#[CoversClass(AbstractMockeryRector::class)]
final class ShouldReceiveToAllowsRectorTest extends AbstractMockeryRectorTestCase {}
