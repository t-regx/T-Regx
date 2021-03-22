<?php
namespace Test\Utils;

use PHPUnit\Framework\Assert;
use TRegx\CleanRegex\PatternInterface;

trait AssertsPattern
{
    public function assertSamePattern(string $expected, PatternInterface $actual): void
    {
        Assert::assertSame($expected, $actual->delimited());
    }
}
