<?php
namespace Test\Unit\TRegx\CleanRegex\Internal\Match\Stream;

use PHPUnit\Framework\TestCase;
use Test\Utils\Functions;
use Test\Utils\Impl\AllStream;
use Test\Utils\Impl\ConstantStream;
use Test\Utils\Impl\FirstStream;
use Test\Utils\Impl\ReverseFlatMap;
use Test\Utils\Impl\ThrowFlatMap;
use TRegx\CleanRegex\Exception\InvalidReturnValueException;
use TRegx\CleanRegex\Internal\Match\FlatFunction;
use TRegx\CleanRegex\Internal\Match\FlatMap\ArrayMergeStrategy;
use TRegx\CleanRegex\Internal\Match\Stream\FlatMappingStream;
use TRegx\CleanRegex\Internal\Match\Stream\Stream;

/**
 * @covers \TRegx\CleanRegex\Internal\Match\Stream\FlatMappingStream
 */
class FlatMappingStreamTest extends TestCase
{
    /**
     * @test
     */
    public function shouldReturn_all()
    {
        // given
        $stream = new FlatMappingStream(new AllStream(['One', 'Two', 'Three']), new ReverseFlatMap(), new FlatFunction(Functions::letters(), ''));

        // when
        $all = $stream->all();

        // then
        $this->assertSame(['e', 'e', 'r', 'h', 'T', 'o', 'w', 'T', 'e', 'n', 'O'], $all);
    }

    /**
     * @test
     */
    public function shouldReturn_first()
    {
        // given
        $stream = new FlatMappingStream(new FirstStream('Foo'), new ReverseFlatMap(), new FlatFunction(Functions::letters(), ''));

        // when
        $first = $stream->first();

        // then
        $this->assertSame('F', $first);
    }

    /**
     * @test
     */
    public function shouldReturn_firstKey()
    {
        // given
        $stream = new FlatMappingStream(new FirstStream('Bar'), new ReverseFlatMap(), new FlatFunction(Functions::lettersAsKeys(), ''));

        // when
        $firstKey = $stream->firstKey();

        // then
        $this->assertSame('B', $firstKey);
    }

    /**
     * @test
     */
    public function shouldReturn_first_forEmptyFirstTrailAll()
    {
        // given
        $flatMap = new FlatMappingStream(new ConstantStream('', ['', '', 'One']), new ArrayMergeStrategy(), new FlatFunction(Functions::letters(), ''));

        // when
        $first = $flatMap->first();

        // then
        $this->assertSame('O', $first);
    }

    /**
     * @test
     */
    public function shouldReturn_firstKey_forEmptyFirstTrailAll()
    {
        // given
        $flatMap = new FlatMappingStream(new ConstantStream('', ['', '', 'Two']), new ArrayMergeStrategy(), new FlatFunction(Functions::lettersAsKeys(), ''));

        // when
        $result = $flatMap->firstKey();

        // then
        $this->assertSame('T', $result);
    }

    /**
     * @test
     * @dataProvider methodsInvalidReturn
     * @param Stream $input
     * @param string $method
     */
    public function shouldThrow_forInvalidReturnType(Stream $input, string $method)
    {
        // given
        $stream = new FlatMappingStream($input, new ThrowFlatMap(), new FlatFunction('strLen', 'hello'));

        // then
        $this->expectException(InvalidReturnValueException::class);
        $this->expectExceptionMessage('Invalid hello() callback return type. Expected array, but integer (3) given');

        // when
        $stream->$method();
    }

    public function methodsInvalidReturn(): array
    {
        return [
            [new AllStream(['Foo']), 'all'],
            [new FirstStream('Foo'), 'first'],
            [new FirstStream('Foo'), 'firstKey'],
        ];
    }

    /**
     * @test
     * @dataProvider skewedArrays
     * @param array $array
     */
    public function shouldReturn_first_forSkewedArray(array $array)
    {
        // given
        $stream = new FlatMappingStream(new FirstStream('One'), new ThrowFlatMap(), new FlatFunction(Functions::constant($array), ''));

        // when
        $first = $stream->first();

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
        $stream = new FlatMappingStream(new FirstStream('One'), new ThrowFlatMap(), new FlatFunction(Functions::constant($array), ''));

        // when
        $firstKey = $stream->firstKey();

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
