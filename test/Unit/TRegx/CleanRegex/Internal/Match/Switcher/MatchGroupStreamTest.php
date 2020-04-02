<?php
namespace Test\Unit\TRegx\CleanRegex\Internal\Match\Switcher;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Internal\Match\MatchAll\EagerMatchAllFactory;
use TRegx\CleanRegex\Internal\Match\MatchAll\MatchAllFactory;
use TRegx\CleanRegex\Internal\Match\Switcher\BaseStream;
use TRegx\CleanRegex\Internal\Match\Switcher\MatchGroupStream;
use TRegx\CleanRegex\Internal\Model\Match\RawMatchOffset;
use TRegx\CleanRegex\Internal\Model\Matches\IRawMatchesOffset;
use TRegx\CleanRegex\Internal\Model\Matches\RawMatchesOffset;
use TRegx\CleanRegex\Internal\Subject;
use TRegx\CleanRegex\Match\Details\Group\MatchGroup;

class MatchGroupStreamTest extends TestCase
{
    /**
     * @test
     */
    public function shouldGetAll()
    {
        // given
        $switcher = $this->matchSwitcher($this->switcher('all', $this->matchesOffset('15')), 'group');

        // when
        /** @var MatchGroup[] $all */
        $all = $switcher->all();

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
        $switcher = $this->matchSwitcher($this->switcher('first', $this->matchOffset('192')), 'group');

        // when
        $first = $switcher->first();

        // then
        $this->assertSame('g-192', $first->text());
    }

    /**
     * @test
     */
    public function shouldGetFirstKey()
    {
        // given
        $switcher = $this->matchSwitcher($this->zeroInteraction(), 'group');

        // when
        $firstKey = $switcher->firstKey();

        // then
        $this->assertSame(0, $firstKey);
    }

    /**
     * @test
     */
    public function shouldGet_groupIndex_FirstMatch()
    {
        // given
        $switcher = $this->matchSwitcher($this->switcher('first', $this->matchOffset('192')), 'group');

        // when
        $first = $switcher->first();

        // then
        $this->assertSame(2, $first->index());
    }

    /**
     * @test
     */
    public function shouldGet_groupIndex_AllMatches()
    {
        // given
        $switcher = $this->matchSwitcher($this->switcher('all', $this->matchesOffset('19')), 'group');

        // when
        /** @var MatchGroup[] $all */
        $all = $switcher->all();

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
        $switcher = $this->matchSwitcher($this->switcher('all', $this->matchesOffset('15')), 'group');

        // when
        /** @var MatchGroup[] $all */
        $all = $switcher->all();

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
        $switcher = $this->matchSwitcher($this->switcher('first', $this->matchOffset()), 'group', $this->mockEagerFactory());

        // when
        $first = $switcher->first();

        // then
        $this->assertSame(['sword', 'bow', 'axe'], $first->all());
    }

    private function switcher(string $methodName, $value): BaseStream
    {
        /** @var BaseStream|MockObject $switcher */
        $switcher = $this->createMock(BaseStream::class);
        $switcher->expects($this->once())->method($methodName)->willReturn($value);
        $switcher->expects($this->never())->method($this->logicalNot($this->matches($methodName)));
        return $switcher;
    }

    private function zeroInteraction(): BaseStream
    {
        /** @var BaseStream|MockObject $switcher */
        $switcher = $this->createMock(BaseStream::class);
        $switcher->expects($this->never())->method($this->anything());
        return $switcher;
    }

    private function matchesOffset(string $firstValue): IRawMatchesOffset
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

    private function matchSwitcher(BaseStream $switcher, $nameOrIndex, MatchAllFactory $factory = null): MatchGroupStream
    {
        return new MatchGroupStream(
            $switcher,
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
