<?php

declare(strict_types=1);

namespace Ghostwriter\MockeryRectorTests\Unit\ExtendMockeryTestCaseRector;

use Ghostwriter\MockeryRector\AbstractMockeryRector;
use Ghostwriter\MockeryRector\Rule\ExtendMockeryTestCaseRector;
use Ghostwriter\MockeryRectorTests\Unit\AbstractMockeryRectorTestCase;
use PHPUnit\Framework\Attributes\CoversClass;

#[CoversClass(ExtendMockeryTestCaseRector::class)]
#[CoversClass(AbstractMockeryRector::class)]
final class ExtendMockeryTestCaseRectorTest extends AbstractMockeryRectorTestCase
{
}
