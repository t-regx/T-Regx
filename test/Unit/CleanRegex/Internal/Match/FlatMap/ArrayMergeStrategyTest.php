<?php
namespace Test\Unit\TRegx\CleanRegex\Internal\Match\FlatMap;

use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Internal\Match\FlatMap\ArrayMergeStrategy;

/**
 * @covers \TRegx\CleanRegex\Internal\Match\FlatMap\ArrayMergeStrategy
 */
class ArrayMergeStrategyTest extends TestCase
{
    /**
     * @test
     */
    public function shouldLaterAppend()
    {
        // given
        $strategy = new ArrayMergeStrategy();

        // when
        $result = $strategy->flatten([['One', 'Two'], ['Cat', 'Dog', 'Duck'],]);

        // then
        $this->assertSame(['One', 'Two', 'Cat', 'Dog', 'Duck'], $result);
    }

    /**
     * @test
     */
    public function shouldFlatten()
    {
        // given
        $strategy = new ArrayMergeStrategy();
        $array = [
            ['Foo', 4, 'key' => 'value'],
            ['Bar', 8, 'lorem' => 'ipsum'],
        ];

        // when
        $result = $strategy->flatten($array);

        // then
        $this->assertSame(['Foo', 4, 'key' => 'value', 'Bar', 8, 'lorem' => 'ipsum'], $result);
    }
}
