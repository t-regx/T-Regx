<?php
namespace Test\Utils;

use PHPUnit\Framework\Assert;
use TRegx\CleanRegex\Match\Details\Detail;
use TRegx\CleanRegex\Match\Details\Group\DetailGroup;

trait AssertsSameMatches
{
    public function assertSameMatches(array $expected, array $actual)
    {
        \array_walk_recursive($actual, function (&$value) {
            if ($value instanceof Detail || $value instanceof DetailGroup) {
                $value = "$value";
            }
        });
        Assert::assertSame($expected, $actual);
    }
}
