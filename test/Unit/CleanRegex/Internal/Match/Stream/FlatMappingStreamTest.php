<?php
namespace Test\Unit\TRegx\CleanRegex\Internal\Match\Stream;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Test\Utils\Functions;
use Test\Utils\Impl\ReverseFlatMap;
use Test\Utils\Impl\ThrowFlatMap;
use TRegx\CleanRegex\Exception\InvalidReturnValueException;
use TRegx\CleanRegex\Internal\Match\FlatMap\ArrayMergeStrategy;
use TRegx\CleanRegex\Internal\Match\Stream\FlatMappingStream;
use TRegx\CleanRegex\Internal\Match\Stream\Stream;

class FlatMappingStreamTest extends TestCase
{
    /**
     * @test
     */
    public function shouldReturn_all()
    {
        // given
        $stream = new FlatMappingStream($this->all(['One', 'Two', 'Three']), new ReverseFlatMap(), 'str_split', '');

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
        $stream = new FlatMappingStream($this->first('Foo'), new ReverseFlatMap(), 'str_split', '');

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
        $stream = new FlatMappingStream($this->first('Bar'), new ReverseFlatMap(), Functions::lettersFlip(), '');

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
        $flatMap = new FlatMappingStream($this->both('', ['', '', 'One']), new ArrayMergeStrategy(), Functions::letters(), '');

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
        $flatMap = new FlatMappingStream($this->both('', ['', '', 'Two']), new ArrayMergeStrategy(), Functions::lettersFlip(), '');

        // when
        $result = $flatMap->firstKey();

        // then
        $this->assertSame('T', $result);
    }

    /**
     * @test
     * @dataProvider methodsInvalidReturn
     * @param string $mock
     * @param string $method
     * @param string[]|string $return
     */
    public function shouldThrow_forInvalidReturnType(string $mock, string $method, $return)
    {
        // given
        $stream = new FlatMappingStream($this->mock($mock, $return), new ThrowFlatMap(), 'strLen', 'hello');

        // then
        $this->expectException(InvalidReturnValueException::class);
        $this->expectExceptionMessage('Invalid hello() callback return type. Expected array, but integer (3) given');

        // when
        $stream->$method();
    }

    public function methodsInvalidReturn(): array
    {
        return [
            ['all', 'all', ['Foo']],
            ['first', 'first', 'Foo'],
            ['first', 'firstKey', 'Foo'],
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
        $stream = new FlatMappingStream($this->first('One'), new ThrowFlatMap(), Functions::constant($array), '');

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
        $stream = new FlatMappingStream($this->first('One'), new ThrowFlatMap(), Functions::constant($array), '');

        // when
        $firstKey = $stream->firstKey();

        // then
        $this->assertSame('F', $firstKey);
    }

    public function skewedArrays(): array
    {
        $skewedArray = ['F' => 1, 'o' => 2];
        next($skewedArray);
        next($skewedArray);
        return [
            [['F' => 1, 'o' => 2]],
            [$skewedArray]
        ];
    }

    private function all(array $all): Stream
    {
        return $this->mock('all', $all);
    }

    private function first($string): Stream
    {
        return $this->mock('first', $string);
    }

    private function mock(string $methodName, $value): Stream
    {
        /** @var Stream|MockObject $stream */
        $stream = $this->createMock(Stream::class);
        $stream->expects($this->once())->method($methodName)->willReturn($value);
        $stream->expects($this->never())->method($this->logicalNot($this->matches($methodName)));
        return $stream;
    }

    private function both(string $first, array $all): Stream
    {
        $stream = $this->createMock(Stream::class);
        $stream->expects($this->once())->method('first')->willReturn($first);
        $stream->expects($this->once())->method('all')->willReturn($all);
        return $stream;
    }
}
