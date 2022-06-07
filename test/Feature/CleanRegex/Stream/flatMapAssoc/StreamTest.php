<?php
namespace Test\Feature\CleanRegex\Stream\flatMapAssoc;

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
        $stream = ArrayStream::of(['Neo', 'Tank', 'Apoc']);
        // when
        $letters = $stream->flatMapAssoc(Functions::lettersAsKeys())->all();
        // then
        $expected = [
            'N' => 0,
            'e' => 1,
            'o' => 2,
            'T' => 0,
            'a' => 1,
            'n' => 2,
            'k' => 3,
            'A' => 0,
            'p' => 1,
            'c' => 3,
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
        $letter = $stream->flatMapAssoc(Functions::lettersAsKeys())->first();
        // then
        $this->assertSame(0, $letter);
    }

    /**
     * @test
     */
    public function shouldFlatMapFirst_entry()
    {
        // given
        $stream = ArrayStream::of(['Neo', 'Morpheus', 'Trinity']);
        // when
        $letter = $stream->flatMapAssoc(Functions::lettersAsEntries())->first();
        // then
        $this->assertSame('N', $letter);
    }

    /**
     * @test
     */
    public function shouldFlatMap_keys_first()
    {
        // given
        $stream = ArrayStream::of(['Neo', 'Morpheus', 'Trinity']);
        // when
        $first = $stream->flatMapAssoc(Functions::lettersAsKeys())->keys()->first();
        // then
        $this->assertSame('N', $first);
    }

    /**
     * @test
     */
    public function shouldThrow_forInvalidReturnType_all()
    {
        // given
        $stream = ArrayStream::of(['Foo'])->flatMapAssoc(Functions::constant(3));
        // then
        $this->expectException(InvalidReturnValueException::class);
        $this->expectExceptionMessage('Invalid flatMapAssoc() callback return type. Expected array, but integer (3) given');
        // when
        $stream->all();
    }

    /**
     * @test
     */
    public function shouldThrow_forInvalidReturnType_first()
    {
        // given
        $stream = ArrayStream::of(['Foo'])->flatMapAssoc(Functions::constant(3));
        // then
        $this->expectException(InvalidReturnValueException::class);
        $this->expectExceptionMessage('Invalid flatMapAssoc() callback return type. Expected array, but integer (3) given');
        // when
        $stream->first();
    }

    /**
     * @test
     */
    public function shouldThrow_forInvalidReturnType_firstKey()
    {
        // given
        $stream = ArrayStream::of(['Foo'])->flatMapAssoc(Functions::constant(3))->keys();
        // then
        $this->expectException(InvalidReturnValueException::class);
        $this->expectExceptionMessage('Invalid flatMapAssoc() callback return type. Expected array, but integer (3) given');
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
        $first = ArrayStream::of(['Foo'])->flatMapAssoc(Functions::constant($array))->first();
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
        $firstKey = ArrayStream::of(['Foo'])->flatMapAssoc(Functions::constant($array))
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

    /**
     * @test
     */
    public function shouldReturn_flatMap_nth()
    {
        // given
        $stream = ArrayStream::of(['Boromir', 'Faramir']);
        // when
        $nth = $stream->flatMapAssoc(Functions::lettersAsEntries())->nth(4);
        // then
        $this->assertSame('i', $nth);
    }
}
