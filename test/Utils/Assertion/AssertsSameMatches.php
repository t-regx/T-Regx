<?php
namespace Test\Utils\Assertion;

use PHPUnit\Framework\Assert;
use TRegx\CleanRegex\Match\Details\Detail;
use TRegx\CleanRegex\Match\Details\Group\Group;

/**
 * @deprecated
 */
trait AssertsSameMatches
{
    /**
     * @deprecated
     */
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
