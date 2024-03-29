<?php

declare(strict_types=1);

namespace Ghostwriter\MockeryRectorTests\Unit\MyNewRector;

use Ghostwriter\MockeryRector\AbstractMockeryRector;
use Ghostwriter\MockeryRector\Rule\RuleRector;
use Ghostwriter\MockeryRectorTests\Unit\AbstractMockeryRectorTestCase;
use PHPUnit\Framework\Attributes\CoversClass;

#[CoversClass(RuleRector::class)]
#[CoversClass(AbstractMockeryRector::class)]
final class RuleRectorTest extends AbstractMockeryRectorTestCase
{
}
