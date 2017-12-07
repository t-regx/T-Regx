<?php
namespace Test\SafeRegex\Guard;

use PHPUnit\Framework\TestCase;
use SafeRegex\Guard\Arrays;

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
}
