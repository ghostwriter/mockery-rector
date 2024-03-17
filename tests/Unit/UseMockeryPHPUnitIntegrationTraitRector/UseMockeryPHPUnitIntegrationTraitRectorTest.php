<?php

declare(strict_types=1);

namespace Ghostwriter\MockeryRectorTests\Unit\UseMockeryPHPUnitIntegrationTraitRector;

use Ghostwriter\MockeryRector\AbstractMockeryRector;
use Ghostwriter\MockeryRector\Rule\UseMockeryPHPUnitIntegrationTraitRector;
use Ghostwriter\MockeryRectorTests\Unit\AbstractMockeryRectorTestCase;
use PHPUnit\Framework\Attributes\CoversClass;

#[CoversClass(UseMockeryPHPUnitIntegrationTraitRector::class)]
#[CoversClass(AbstractMockeryRector::class)]
final class UseMockeryPHPUnitIntegrationTraitRectorTest extends AbstractMockeryRectorTestCase
{
}
