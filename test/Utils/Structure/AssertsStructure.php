<?php
namespace Test\Utils\Structure;

use PHPUnit\Framework\Assert;
use Test\Utils\Iterables;

trait AssertsStructure
{
    public function assertStructure(array $items, array $expectations): void
    {
        foreach (Iterables::zip($items, $expectations) as [$actual, $expected]) {
            if ($expected instanceof Expectation) {
                $expected->apply($actual);
            } else if (\is_array($actual) && \is_array($expected)) {
                $this->assertStructure($actual, $expected);
            } else {
                Assert::assertSame($expected, $actual);
            }
        }
    }
}
