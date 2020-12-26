<?php
namespace Test\Utils;

use TRegx\CleanRegex\Match\Details\Detail;
use TRegx\CleanRegex\Match\Details\Group\DetailGroup;

trait AssertsSameMatches
{
    public abstract function assertSame($expected, $actual, string $message = ''): void;

    public function assertSameMatches(array $expected, array $actual)
    {
        \array_walk_recursive($actual, function (&$value) {
            if ($value instanceof Detail || $value instanceof DetailGroup) {
                $value = "$value";
            }
        });
        $this->assertSame($expected, $actual);
    }
}
