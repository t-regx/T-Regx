<?php
namespace Test\Unit\TRegx\CleanRegex\Internal\Match\Stream;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Internal\Match\MatchAll\EagerMatchAllFactory;
use TRegx\CleanRegex\Internal\Match\MatchAll\MatchAllFactory;
use TRegx\CleanRegex\Internal\Match\Stream\BaseStream;
use TRegx\CleanRegex\Internal\Match\Stream\MatchGroupStream;
use TRegx\CleanRegex\Internal\Model\Match\RawMatchOffset;
use TRegx\CleanRegex\Internal\Model\Matches\RawMatchesOffset;
use TRegx\CleanRegex\Internal\Subject;
use TRegx\CleanRegex\Match\Details\Group\DetailGroup;

class MatchGroupStreamTest extends TestCase
{
    /**
     * @test
     */
    public function shouldGetAll()
    {
        // given
        $stream = $this->matchStream($this->stream('all', $this->matchesOffset('15')), 'group');

        // when
        /** @var DetailGroup[] $all */
        $all = $stream->all();

        // then
        $this->assertSame('g-15', $all[0]->text());
        $this->assertSame('g-19', $all[1]->text());
        $this->assertSame('g-25', $all[2]->text());
    }

    /**
     * @test
     */
    public function shouldGetFirst()
    {
        // given
        $stream = $this->matchStream($this->stream('first', $this->matchOffset('192')), 'group');

        // when
        $first = $stream->first();

        // then
        $this->assertSame('g-192', $first->text());
    }

    /**
     * @test
     */
    public function shouldGetFirstKey()
    {
        // given
        $stream = $this->matchStream($this->zeroInteraction(), 'group');

        // when
        $firstKey = $stream->firstKey();

        // then
        $this->assertSame(0, $firstKey);
    }

    /**
     * @test
     */
    public function shouldGet_groupIndex_FirstMatch()
    {
        // given
        $stream = $this->matchStream($this->stream('first', $this->matchOffset('192')), 'group');

        // when
        $first = $stream->first();

        // then
        $this->assertSame(2, $first->index());
    }

    /**
     * @test
     */
    public function shouldGet_groupIndex_AllMatches()
    {
        // given
        $stream = $this->matchStream($this->stream('all', $this->matchesOffset('19')), 'group');

        // when
        /** @var DetailGroup[] $all */
        $all = $stream->all();

        // then
        $this->assertSame(2, $all[0]->index());
        $this->assertSame(2, $all[1]->index());
        $this->assertSame(2, $all[2]->index());
    }

    /**
     * @test
     */
    public function shouldGet_matchAll_AllMatches()
    {
        // given
        $stream = $this->matchStream($this->stream('all', $this->matchesOffset('15')), 'group');

        // when
        /** @var DetailGroup[] $all */
        $all = $stream->all();

        // then
        $this->assertSame(['g-15', 'g-19', 'g-25'], $all[0]->all());
        $this->assertSame(['g-15', 'g-19', 'g-25'], $all[1]->all());
        $this->assertSame(['g-15', 'g-19', 'g-25'], $all[2]->all());
    }

    /**
     * @test
     */
    public function shouldGet_matchAll_FirstMatch()
    {
        // given
        $stream = $this->matchStream($this->stream('first', $this->matchOffset()), 'group', $this->mockEagerFactory());

        // when
        $first = $stream->first();

        // then
        $this->assertSame(['sword', 'bow', 'axe'], $first->all());
    }

    private function stream(string $methodName, $value): BaseStream
    {
        /** @var BaseStream|MockObject $stream */
        $stream = $this->createMock(BaseStream::class);
        $stream->expects($this->once())->method($methodName)->willReturn($value);
        $stream->expects($this->never())->method($this->logicalNot($this->matches($methodName)));
        return $stream;
    }

    private function zeroInteraction(): BaseStream
    {
        /** @var BaseStream|MockObject $stream */
        $stream = $this->createMock(BaseStream::class);
        $stream->expects($this->never())->method($this->anything());
        return $stream;
    }

    private function matchesOffset(string $firstValue): RawMatchesOffset
    {
        return new RawMatchesOffset([
            0            => [
                [$firstValue, 1],
                ['19', 2],
                ['25', 3],
            ],
            'irrelevant' => [],
            1            => [],
            'group'      => [
                ["g-$firstValue", 1],
                ['g-19', 2],
                ['g-25', 3],
            ],
            # This is a hint for GroupNameIndexAssign, it will know that "group" is #2
            2            => [
                ["g-$firstValue", 1],
                ['g-19', 2],
                ['g-25', 3],
            ],
        ]);
    }

    private function matchOffset(string $value = ''): RawMatchOffset
    {
        return new RawMatchOffset([
            0            => [$value, 1],
            'irrelevant' => [],
            1            => [],
            'group'      => ["g-$value", 1],
            2            => ["g-$value", 1]    # This is a hint for GroupNameIndexAssign, it will know that "group" is #2
        ]);
    }

    private function matchStream(BaseStream $stream, $nameOrIndex, MatchAllFactory $factory = null): MatchGroupStream
    {
        return new MatchGroupStream(
            $stream,
            new Subject('switch subject'),
            $nameOrIndex,
            $factory ?? $this->createMock(MatchAllFactory::class));
    }

    private function mockEagerFactory(): EagerMatchAllFactory
    {
        return new EagerMatchAllFactory(new RawMatchesOffset([
            2 => [
                ['sword', 1],
                ['bow', 2],
                ['axe', 3],
            ],
        ]));
    }
}
