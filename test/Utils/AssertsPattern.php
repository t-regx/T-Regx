<?php
namespace Test\Utils;

use PHPUnit\Framework\Assert;
use TRegx\CleanRegex\Pattern;

trait AssertsPattern
{
    public function assertSamePattern(string $expected, Pattern $actual): void
    {
        Assert::assertSame($expected, $actual->delimited());
    }
}
