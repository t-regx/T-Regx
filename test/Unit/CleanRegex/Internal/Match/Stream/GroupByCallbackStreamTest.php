<?php
namespace Test\Unit\TRegx\CleanRegex\Internal\Match\Stream;

use PHPUnit\Framework\TestCase;
use Test\Utils\Functions;
use Test\Utils\Impl\AllStream;
use Test\Utils\Impl\FirstStream;
use Test\Utils\Impl\TextDetail;
use Test\Utils\Impl\TextGroup;
use Test\Utils\Impl\ThrowStream;
use TRegx\CleanRegex\Exception\InvalidReturnValueException;
use TRegx\CleanRegex\Internal\Exception\NoFirstStreamException;
use TRegx\CleanRegex\Internal\Match\Stream\GroupByCallbackStream;
use TRegx\CleanRegex\Internal\Match\Stream\Stream;

/**
 * @covers \TRegx\CleanRegex\Internal\Match\Stream\GroupByCallbackStream
 */
class GroupByCallbackStreamTest extends TestCase
{
    /**
     * @test
     */
    public function shouldGetAll()
    {
        // given
        $stream = new GroupByCallbackStream(new AllStream([10 => 'One', 20 => 'Two', 30 => 'Three']), Functions::charAt(0));

        // when
        $all = $stream->all();

        // then
        $this->assertSame(['O' => ['One'], 'T' => ['Two', 'Three']], $all);
    }

    /**
     * @test
     */
    public function shouldGroupDifferentDataTypes()
    {
        // given
        $stream = new GroupByCallbackStream(new AllStream(['hello', 2, new TextDetail('hello'), 2, new TextGroup('hello')]), Functions::identity());

        // when
        $all = $stream->all();

        // then
        $expected = [
            'hello' => ['hello', new TextDetail('hello'), new TextGroup('hello')],
            2       => [2, 2],
        ];
        $this->assertEquals($expected, $all);
    }

    /**
     * @test
     */
    public function shouldGetFirst()
    {
        // given
        $stream = new GroupByCallbackStream(new FirstStream('One'), 'strToUpper');

        // when
        $first = $stream->first();

        // then
        $this->assertSame('One', $first);
    }

    /**
     * @test
     */
    public function shouldGetFirstKey()
    {
        // given
        $stream = new GroupByCallbackStream(new FirstStream('One'), 'strToUpper');

        // when
        $firstKey = $stream->firstKey();

        // then
        $this->assertSame('ONE', $firstKey);
    }

    /**
     * @test
     */
    public function shouldThrow_first()
    {
        // given
        $stream = new GroupByCallbackStream(new ThrowStream(new NoFirstStreamException()), 'strLen');

        // then
        $this->expectException(NoFirstStreamException::class);

        // when
        $stream->first();
    }

    /**
     * @test
     * @dataProvider inputStreams
     * @param string $method
     * @param Stream $input
     */
    public function shouldThrowForInvalidGroupByType_all(string $method, Stream $input)
    {
        // given
        $stream = new GroupByCallbackStream($input, Functions::constant([]));

        // then
        $this->expectException(InvalidReturnValueException::class);
        $this->expectExceptionMessage('Invalid groupByCallback() callback return type. Expected int|string, but array (0) given');

        // when
        $stream->$method();
    }

    public function inputStreams(): array
    {
        return [
            'all()'   => ['all', new AllStream(['foo'])],
            'first()' => ['first', new FirstStream('foo')]
        ];
    }
}
