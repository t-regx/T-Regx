<?php
namespace Test\Feature\TRegx\CleanRegex\Match\stream\flatMap;

use PHPUnit\Framework\TestCase;
use Test\Utils\Functions;
use TRegx\CleanRegex\Exception\InvalidReturnValueException;
use TRegx\CleanRegex\Pattern;

/**
 * @covers \TRegx\CleanRegex\Internal\Match\Stream\FlatMapStream
 */
class MatchPatternTest extends TestCase
{
    /**
     * @test
     */
    public function shouldFlatMapAll()
    {
        // when
        $all = Pattern::of('\w+')->match('One, Two, Three')
            ->stream()
            ->flatMap(Functions::letters())
            ->all();
        // then
        $this->assertSame(['O', 'n', 'e', 'T', 'w', 'o', 'T', 'h', 'r', 'e', 'e'], $all);
    }

    /**
     * @test
     */
    public function shouldFlatMapFirst()
    {
        // when
        $first = Pattern::of('\w+')->match('One, Two, Three')
            ->stream()
            ->flatMap(Functions::letters())
            ->first();
        // then
        $this->assertSame('O', $first);
    }

    /**
     * @test
     */
    public function shouldReturn_firstKey()
    {
        // when
        $first = Pattern::of('\w+')->match('Lorem, Ipsum')
            ->stream()
            ->flatMapAssoc(Functions::lettersAsKeys())
            ->keys()
            ->first();
        // then
        $this->assertSame('L', $first);
    }

    /**
     * @test
     */
    public function shouldReturn_first_forEmptyFirstTrailAll()
    {
        // when
        $first = Pattern::of('"(\w*)"')->match('"", "", "Three"')
            ->group(1)
            ->stream()
            ->flatMap(Functions::letters())
            ->first();
        // then
        $this->assertSame('T', $first);
    }

    /**
     * @test
     */
    public function shouldReturn_firstKey_forEmptyFirstTrailAll()
    {
        // when
        $first = Pattern::of('"(\w*)"')->match('"", "", "Apple"')
            ->group(1)
            ->stream()
            ->flatMapAssoc(Functions::lettersAsKeys())
            ->keys()
            ->first();
        // then
        $this->assertSame('A', $first);
    }

    /**
     * @test
     */
    public function shouldThrow_forInvalidReturnType_all()
    {
        // given
        $stream = Pattern::literal('Foo')
            ->match('Foo')
            ->stream()
            ->flatMap(Functions::constant(3));
        // then
        $this->expectException(InvalidReturnValueException::class);
        $this->expectExceptionMessage('Invalid flatMap() callback return type. Expected array, but integer (3) given');
        // when
        $stream->all();
    }

    /**
     * @test
     */
    public function shouldThrow_forInvalidReturnType_first()
    {
        // given
        $stream = Pattern::literal('Foo')
            ->match('Foo')
            ->stream()
            ->flatMap(Functions::constant(3));
        // then
        $this->expectException(InvalidReturnValueException::class);
        $this->expectExceptionMessage('Invalid flatMap() callback return type. Expected array, but integer (3) given');
        // when
        $stream->first();
    }

    /**
     * @test
     */
    public function shouldThrow_forInvalidReturnType_firstKey()
    {
        // given
        $stream = Pattern::literal('Foo')
            ->match('Foo')
            ->stream()
            ->flatMap(Functions::constant(3))
            ->keys();
        // then
        $this->expectException(InvalidReturnValueException::class);
        $this->expectExceptionMessage('Invalid flatMap() callback return type. Expected array, but integer (3) given');
        // when
        $stream->first();
    }

    /**
     * @test
     * @dataProvider skewedArrays
     * @param array $array
     */
    public function shouldReturn_first_forSkewedArray(array $array)
    {
        // when
        $first = Pattern::literal('Foo')
            ->match('Foo')
            ->stream()
            ->flatMapAssoc(Functions::constant($array))
            ->first();
        // then
        $this->assertSame(1, $first);
    }

    /**
     * @test
     * @dataProvider skewedArrays
     * @param array $array
     */
    public function shouldReturn_firstKey_forSkewedArray(array $array)
    {
        // given
        $firstKey = Pattern::literal('Foo')
            ->match('Foo')
            ->stream()
            ->flatMapAssoc(Functions::constant($array))
            ->keys()
            ->first();
        // then
        $this->assertSame('F', $firstKey);
    }

    public function skewedArrays(): array
    {
        return [
            [['F' => 1, 'o' => 2]],
            [$this->skewed(['F' => 1, 'o' => 2])]
        ];
    }

    private function skewed(array $array): array
    {
        \next($array);
        \next($array);
        return $array;
    }
}
