<?php
namespace Test\Unit\TRegx\SafeRegex\Internal;

use PHPUnit\Framework\TestCase;
use TRegx\SafeRegex\Internal\Tuple;
use TypeError;

/**
 * @covers \TRegx\SafeRegex\Internal\Tuple
 */
class TupleTest extends TestCase
{
    /**
     * @test
     */
    public function shouldGetFirstValue()
    {
        // when
        $value = Tuple::first(['foo', 'bar']);

        // then
        $this->assertSame($value, 'foo');
    }

    /**
     * @test
     */
    public function shouldGetSecondValue()
    {
        // when
        $value = Tuple::second(['foo', 'bar']);

        // then
        $this->assertSame($value, 'bar');
    }

    /**
     * @test
     * @dataProvider tuples
     */
    public function shouldNotBeTupleFirst(array $tuple)
    {
        // then
        $this->expectException(TypeError::class);

        // when
        Tuple::first($tuple);
    }

    /**
     * @test
     * @dataProvider tuples
     */
    public function shouldNotBeTupleSecond(array $tuple)
    {
        // then
        $this->expectException(TypeError::class);

        // when
        Tuple::second($tuple);
    }

    public function tuples(): array
    {
        return [
            'empty'        => [[]],
            'one value'    => [['value']],
            'three values' => [['foo', 'bar', 'two']],

            'first key '  => [['x' => 'foo', 'bar']],
            'second key ' => [['foo', 'x' => 'bar']],

            'associate ' => [[1 => 'foo', 0 => 'bar']],
        ];
    }
}
