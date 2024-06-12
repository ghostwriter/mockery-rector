<?php

declare(strict_types=1);

namespace Tests\Unit\Rule;

use Ghostwriter\MockeryRector\AbstractMockeryRector;
use Ghostwriter\MockeryRector\Rule\UseMockeryPHPUnitIntegrationTraitRector;
use PHPUnit\Framework\Attributes\CoversClass;
use Tests\Unit\AbstractMockeryRectorTestCase;

#[CoversClass(UseMockeryPHPUnitIntegrationTraitRector::class)]
#[CoversClass(AbstractMockeryRector::class)]
final class UseMockeryPHPUnitIntegrationTraitRectorTest extends AbstractMockeryRectorTestCase
{
}
