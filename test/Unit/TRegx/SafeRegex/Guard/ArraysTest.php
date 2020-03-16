<?php
namespace Test\Unit\TRegx\SafeRegex\Guard;

use PHPUnit\Framework\TestCase;
use TRegx\SafeRegex\Guard\Arrays;

class ArraysTest extends TestCase
{
    /**
     * @test
     */
    public function shouldBeEqual()
    {
        // given
        $array1 = ['a' => 12, 'b' => 69];
        $array2 = ['a' => 12, 'b' => 69];

        // when
        $result = Arrays::equal($array1, $array2);

        // then
        $this->assertTrue($result);
    }

    /**
     * @test
     */
    public function shouldNotBeEqualWithDifferentKeys()
    {
        // given
        $array1 = ['a' => 12, 'b' => 69];
        $array2 = ['c' => 12, 'b' => 69];

        // when
        $result = Arrays::equal($array1, $array2);

        // then
        $this->assertFalse($result);
    }

    /**
     * @test
     */
    public function shouldNotBeEqualWithDifferentValues()
    {
        // given
        $array1 = ['a' => 12, 'b' => 69];
        $array2 = ['c' => 12, 'b' => 70];

        // when
        $result = Arrays::equal($array1, $array2);

        // then
        $this->assertFalse($result);
    }

    /**
     * @test
     */
    public function shouldBeEqualDifferentOrder()
    {
        // given
        $array1 = ['a' => 12, 'b' => 69];
        $array2 = ['b' => 70, 'a' => 12];

        // when
        $result = Arrays::equal($array1, $array2);

        // then
        $this->assertTrue($result);
    }

    /**
     * @test
     */
    public function shouldGetDuplicates()
    {
        $this->assertEquals(['a', 'b', 'c'], Arrays::getDuplicates(['1', 'a', 'b', 'd', 'c', 'b', 'a', 'e', 'c', 'b', 'c', 'b', 'a']));
        $this->assertEmpty(Arrays::getDuplicates(['a', 'b', 'c']));
    }
}
