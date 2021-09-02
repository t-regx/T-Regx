<?php
namespace Test\Unit\TRegx\CleanRegex\Internal;

use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Internal\Arrays;

/**
 * @covers \TRegx\CleanRegex\Internal\Arrays
 */
class ArraysTest extends TestCase
{
    /**
     * @test
     */
    public function shouldFlatten()
    {
        $this->assertSame([], Arrays::flatten([]));
        $this->assertSame([], Arrays::flatten([[]]));
        $this->assertSame(['a', 'b', 'c'], Arrays::flatten([['a', 'b'], ['c']]));
    }
}
