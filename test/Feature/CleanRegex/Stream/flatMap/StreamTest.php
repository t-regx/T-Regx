<?php
namespace Test\Feature\CleanRegex\Stream\flatMap;

use PHPUnit\Framework\TestCase;
use Test\Utils\ArrayStream;
use Test\Utils\Functions;
use TRegx\CleanRegex\Exception\InvalidReturnValueException;

/**
 * @covers \TRegx\CleanRegex\Match\Stream
 * @covers \TRegx\CleanRegex\Internal\Match\Stream\FlatMapStream
 */
class StreamTest extends TestCase
{
    /**
     * @test
     */
    public function shouldFlatMap()
    {
        // given
        $stream = ArrayStream::of(['Neo', 'Morpheus', 'Trinity']);
        // when
        $letters = $stream->flatMap(Functions::letters())->all();
        // then
        $expected = [
            'N', 'e', 'o', 'M', 'o', 'r', 'p', 'h',
            'e', 'u', 's', 'T', 'r', 'i', 'n', 'i', 't', 'y'
        ];
        $this->assertSame($expected, $letters);
    }

    /**
     * @test
     */
    public function shouldFlatMapFirst()
    {
        // given
        $stream = ArrayStream::of(['Neo', 'Morpheus', 'Trinity']);
        // when
        $letter = $stream->flatMap(Functions::letters())->first();
        // then
        $this->assertSame('N', $letter);
    }

    /**
     * @test
     */
    public function shouldFlatMap_keys_first()
    {
        // given
        $stream = ArrayStream::of(['Lorem', 'Ipsum']);
        // when
        $first = $stream->flatMap(Functions::lettersAsKeys())->keys()->first();
        // then
        $this->assertSame(0, $first);
    }

    /**
     * @test
     */
    public function shouldThrow_forInvalidReturnType_all()
    {
        // given
        $stream = ArrayStream::of(['Foo'])->flatMap(Functions::constant(3));
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
        $stream = ArrayStream::of(['Foo'])->flatMap(Functions::constant(3));
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
        $stream = ArrayStream::of(['Foo'])->flatMap(Functions::constant(3))->keys();
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
        $first = ArrayStream::of(['Foo'])->flatMap(Functions::constant($array))->first();
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
        $firstKey = ArrayStream::of(['Foo'])->flatMap(Functions::constant($array))
            ->keys()
            ->first();
        // then
        $this->assertSame(0, $firstKey);
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

    /**
     * @test
     */
    public function shouldReturn_flatMap_nth()
    {
        // given
        $stream = ArrayStream::of(['Boromir', 'Faramir']);
        // when
        $nth = $stream->flatMap(Functions::letters())->nth(7);
        // then
        $this->assertSame('F', $nth);
    }
}
