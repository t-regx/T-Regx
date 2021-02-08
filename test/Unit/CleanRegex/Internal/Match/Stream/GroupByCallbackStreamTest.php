<?php
namespace Test\Unit\TRegx\CleanRegex\Internal\Match\Stream;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Test\Utils\Functions;
use TRegx\CleanRegex\Exception\InvalidReturnValueException;
use TRegx\CleanRegex\Internal\Exception\NoFirstStreamException;
use TRegx\CleanRegex\Internal\Match\Stream\GroupByCallbackStream;
use TRegx\CleanRegex\Internal\Match\Stream\Stream;
use TRegx\CleanRegex\Match\Details\Detail;
use TRegx\CleanRegex\Match\Details\Group\DetailGroup;

class GroupByCallbackStreamTest extends TestCase
{
    /**
     * @test
     */
    public function shouldGetAll()
    {
        // given
        $stream = new GroupByCallbackStream($this->all([10 => 'One', 20 => 'Two', 30 => 'Three']), Functions::charAt(0));

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
        $detail = $this->detailMock('hello');
        $group = $this->matchGroupMock('hello');
        $stream = new GroupByCallbackStream($this->all(['hello', 2, $detail, 2, $group]), Functions::identity());

        // when
        $all = $stream->all();

        // then
        $expected = [
            'hello' => ['hello', $detail, $group],
            2       => [2, 2],
        ];
        $this->assertSame($expected, $all);
    }

    /**
     * @test
     */
    public function shouldGetFirst()
    {
        // given
        $stream = new GroupByCallbackStream($this->first('One'), 'strtoupper');

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
        $stream = new GroupByCallbackStream($this->first('One'), 'strtoupper');

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
        $stream = new GroupByCallbackStream($this->mock('first', 'willThrowException', new NoFirstStreamException()), 'strlen');

        // then
        $this->expectException(NoFirstStreamException::class);

        // when
        $stream->first();
    }

    /**
     * @test
     * @dataProvider callers
     * @param string $caller
     * @param $returnValue
     */
    public function shouldThrowForInvalidGroupByType_all(string $caller, $returnValue)
    {
        // given
        $stream = new GroupByCallbackStream($this->mock($caller, 'willReturn', $returnValue), Functions::constant([]));

        // then
        $this->expectException(InvalidReturnValueException::class);
        $this->expectExceptionMessage('Invalid groupByCallback() callback return type. Expected int|string, but array (0) given');

        // when
        $stream->$caller();
    }

    public function callers(): array
    {
        return [
            'all()'   => ['all', ['foo']],
            'first()' => ['first', 'foo']
        ];
    }

    private function first(string $string): Stream
    {
        return $this->mock('first', 'willReturn', $string);
    }

    private function all(array $all): Stream
    {
        return $this->mock('all', 'willReturn', $all);
    }

    private function mock(string $methodName, string $setter, $value): Stream
    {
        /** @var Stream|MockObject $stream */
        $stream = $this->createMock(Stream::class);
        $stream->expects($this->once())->method($methodName)->$setter($value);
        $stream->expects($this->never())->method($this->logicalNot($this->matches($methodName)));
        return $stream;
    }

    private function detailMock(string $text): Detail
    {
        /** @var Detail|MockObject $detail */
        $detail = $this->createMock(Detail::class);
        $detail->expects($this->once())->method('text')->willReturn($text);
        $detail->expects($this->never())->method($this->logicalNot($this->matches('text')));
        return $detail;
    }

    private function matchGroupMock(string $text): DetailGroup
    {
        /** @var DetailGroup|MockObject $group */
        $group = $this->createMock(DetailGroup::class);
        $group->expects($this->once())->method('text')->willReturn($text);
        $group->expects($this->never())->method($this->logicalNot($this->matches('text')));
        return $group;
    }
}
