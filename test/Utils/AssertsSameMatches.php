<?php
namespace Test\Utils;

use PHPUnit\Framework\Assert;
use TRegx\CleanRegex\Match\Details\Detail;
use TRegx\CleanRegex\Match\Details\Group\Group;

trait AssertsSameMatches
{
    public function assertSameMatches(array $expected, array $actual)
    {
        \array_walk_recursive($actual, function (&$value) {
            if ($value instanceof Detail || $value instanceof Group) {
                $value = "$value";
            }
        });
        Assert::assertSame($expected, $actual);
    }
}
