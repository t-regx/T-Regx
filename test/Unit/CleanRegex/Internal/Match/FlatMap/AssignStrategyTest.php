<?php
namespace Test\Unit\TRegx\CleanRegex\Internal\Match\FlatMap;

use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Internal\Match\FlatMap\AssignStrategy;

class AssignStrategyTest extends TestCase
{
    /**
     * @test
     */
    public function shouldLaterOverride()
    {
        // given
        $strategy = new AssignStrategy();

        // when
        $result = $strategy->flatten([['Cat', 'Dog', 'Duck'], ['One', 'Two', 3 => 'Four']]);

        // then
        $this->assertSame(['One', 'Two', 'Duck', 'Four'], $result);
    }

    /**
     * @test
     */
    public function shouldFlattenWithOrder()
    {
        // given
        $strategy = new AssignStrategy();

        // when
        $result = $strategy->flatten([
            [
                0     => 'Foo',
                1     => 4,
                'key' => 'value'
            ],
            [
                0       => 'Bar',
                1       => 8,
                2       => false,
                'lorem' => 'ipsum'
            ],
            [
                0     => 'Value',
                1     => 12,
                2     => true,
                'cat' => 'dog'
            ],
        ]);

        // then
        $expected = [
            0       => 'Value',
            1       => 12,
            'key'   => 'value',
            2       => true,
            'lorem' => 'ipsum',
            'cat'   => 'dog'
        ];
        $this->assertSame($expected, $result); // don't use assertEquals() and don't change the order
    }
}
