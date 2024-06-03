<?php

declare(strict_types=1);

namespace Tests\Unit\Rule;

use Ghostwriter\MockeryRector\AbstractMockeryRector;
use Ghostwriter\MockeryRector\Rule\UseMockeryPHPUnitIntegrationTraitRector;
use Tests\Unit\AbstractMockeryRectorTestCase;
use PHPUnit\Framework\Attributes\CoversClass;

#[CoversClass(UseMockeryPHPUnitIntegrationTraitRector::class)]
#[CoversClass(AbstractMockeryRector::class)]
final class UseMockeryPHPUnitIntegrationTraitRectorTest extends AbstractMockeryRectorTestCase {}
