<?php
namespace Test\Unit\TRegx\CleanRegex\Internal\Match\Stream;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Test\Utils\Functions;
use TRegx\CleanRegex\Exception\InvalidReturnValueException;
use TRegx\CleanRegex\Internal\Exception\NoFirstStreamException;
use TRegx\CleanRegex\Internal\Match\Stream\GroupByCallbackStream;
use TRegx\CleanRegex\Internal\Match\Stream\Stream;
use TRegx\CleanRegex\Match\Details\Group\MatchGroup;
use TRegx\CleanRegex\Match\Details\Match;

class GroupByCallbackStreamTest extends TestCase
{
    /**
     * @test
     */
    public function shouldGetAll()
    {
        // given
        $stream = new GroupByCallbackStream($this->mock('all', 'willReturn', [10 => 'One', 20 => 'Two', 30 => 'Three']), function (string $value) {
            return $value[0];
        });

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
        $match = $this->matchMock('hello');
        $group = $this->matchGroupMock('hello');
        $input = ['hello', 2, $match, 2, $group,];
        $stream = new GroupByCallbackStream($this->mock('all', 'willReturn', $input), Functions::identity());

        // when
        $all = $stream->all();

        // then
        $expected = [
            'hello' => ['hello', $match, $group],
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
        $stream = new GroupByCallbackStream($this->mock('first', 'willReturn', 'One'), 'strtoupper');

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
        $stream = new GroupByCallbackStream($this->mock('first', 'willReturn', 'One'), 'strtoupper');

        // when
        $firstKey = $stream->firstKey();

        // then
        $this->assertSame('ONE', $firstKey);
    }

    /**
     * @test
     */
    public function shouldFirstThrow()
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
        $stream = new GroupByCallbackStream($this->mock($caller, 'willReturn', $returnValue), function () {
            return [];
        });

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

    private function mock(string $methodName, string $setter, $value): Stream
    {
        /** @var Stream|MockObject $stream */
        $stream = $this->createMock(Stream::class);
        $stream->expects($this->once())->method($methodName)->$setter($value);
        $stream->expects($this->never())->method($this->logicalNot($this->matches($methodName)));
        return $stream;
    }

    private function matchMock(string $text): Match
    {
        /** @var Match|MockObject $match */
        $match = $this->createMock(Match::class);
        $match->expects($this->once())->method('text')->willReturn($text);
        $match->expects($this->never())->method($this->logicalNot($this->matches('text')));
        return $match;
    }

    private function matchGroupMock(string $text): MatchGroup
    {
        /** @var MatchGroup|MockObject $group */
        $group = $this->createMock(MatchGroup::class);
        $group->expects($this->once())->method('text')->willReturn($text);
        $group->expects($this->never())->method($this->logicalNot($this->matches('text')));
        return $group;
    }
}
