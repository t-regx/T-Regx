<?php
namespace Test\Unit\TRegx\CleanRegex\Internal\Match\FlatMap;

use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Internal\Match\FlatMap\ArrayMergeStrategy;
use TRegx\CleanRegex\Internal\Nested;

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
        $result = $strategy->flatten(new Nested([['One', 'Two'], ['Cat', 'Dog', 'Duck']]));

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
        $result = $strategy->flatten(new Nested($array));

        // then
        $this->assertSame(['Foo', 4, 'key' => 'value', 'Bar', 8, 'lorem' => 'ipsum'], $result);
    }
}
