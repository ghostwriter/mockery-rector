<?php

declare(strict_types=1);

namespace Ghostwriter\MockeryRectorTests\Unit\MyNewRector;

use Ghostwriter\MockeryRector\AbstractMockeryRector;
use Ghostwriter\MockeryRector\Rule\MockeryShouldReceiveToAllowsRector;
use Ghostwriter\MockeryRectorTests\Unit\AbstractMockeryRectorTestCase;
use PHPUnit\Framework\Attributes\CoversClass;

#[CoversClass(MyNewRector::class)]
#[CoversClass(AbstractMockeryRector::class)]
final class MyNewRectorTest extends AbstractMockeryRectorTestCase
{
}
