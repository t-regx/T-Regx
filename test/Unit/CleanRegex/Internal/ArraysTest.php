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

    /**
     * @test
     */
    public function shouldGetDuplicates()
    {
        $this->assertSame(['a', 'b', 'c'], Arrays::getDuplicates(['1', 'a', 'b', 'd', 'c', 'b', 'a', 'e', 'c', 'b', 'c', 'b', 'a']));
        $this->assertEmpty(Arrays::getDuplicates(['a', 'b', 'c']));
    }
}
