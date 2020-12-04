<?php
namespace Test\Unit\TRegx\SafeRegex\Guard;

use PHPUnit\Framework\TestCase;
use TRegx\SafeRegex\Guard\Arrays;

class ArraysTest extends TestCase
{
    /**
     * @test
     */
    public function shouldGetDuplicates()
    {
        $this->assertEquals(['a', 'b', 'c'], Arrays::getDuplicates(['1', 'a', 'b', 'd', 'c', 'b', 'a', 'e', 'c', 'b', 'c', 'b', 'a']));
        $this->assertEmpty(Arrays::getDuplicates(['a', 'b', 'c']));
    }
}
