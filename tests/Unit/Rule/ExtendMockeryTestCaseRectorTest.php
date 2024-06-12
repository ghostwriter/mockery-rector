<?php

declare(strict_types=1);

namespace Tests\Unit\Rule;

use Ghostwriter\MockeryRector\AbstractMockeryRector;
use Ghostwriter\MockeryRector\Rule\ExtendMockeryTestCaseRector;
use PHPUnit\Framework\Attributes\CoversClass;
use Tests\Unit\AbstractMockeryRectorTestCase;

#[CoversClass(ExtendMockeryTestCaseRector::class)]
#[CoversClass(AbstractMockeryRector::class)]
final class ExtendMockeryTestCaseRectorTest extends AbstractMockeryRectorTestCase
{
}
