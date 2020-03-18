<?php
namespace TRegx\CleanRegex\Internal\Match\Switcher;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Internal\Match\MatchAll\EagerMatchAllFactory;
use TRegx\CleanRegex\Internal\Match\MatchAll\MatchAllFactory;
use TRegx\CleanRegex\Internal\Match\UserData;
use TRegx\CleanRegex\Internal\Model\Match\RawMatchOffset;
use TRegx\CleanRegex\Internal\Model\Matches\IRawMatchesOffset;
use TRegx\CleanRegex\Internal\Model\Matches\RawMatchesOffset;
use TRegx\CleanRegex\Internal\Model\MatchObjectFactory;
use TRegx\CleanRegex\Internal\Subject;
use TRegx\CleanRegex\Match\Details\Match;

class MatchSwitcherTest extends TestCase
{
    /**
     * @test
     */
    public function shouldDelegateAll()
    {
        // given
        $switcher = new MatchSwitcher($this->switcherAll('14'), $this->factory(), $this->mock());

        // when
        /** @var Match[] $all */
        $all = $switcher->all();

        // then
        $this->assertSame('14', $all[0]->text());
        $this->assertSame('19', $all[1]->text());
        $this->assertSame('25', $all[2]->text());
    }

    /**
     * @test
     */
    public function shouldDelegateFirst()
    {
        // given
        $switcher = new MatchSwitcher($this->switcherFirst('192'), $this->factory(), $this->mock());

        // when
        $first = $switcher->first();

        // then
        $this->assertSame('192', $first->text());
    }

    /**
     * @test
     */
    public function shouldCreateFirstMatch_index()
    {
        // given
        $switcher = new MatchSwitcher($this->switcherFirst('192'), $this->factory(), $this->mock());

        // when
        $first = $switcher->first();

        // then
        $this->assertSame(0, $first->index());
    }

    /**
     * @test
     */
    public function shouldCreateAllMatches_index()
    {
        // given
        $switcher = new MatchSwitcher($this->switcherAll('19'), $this->factory(), $this->mock());

        // when
        /** @var Match[] $all */
        $all = $switcher->all();

        // then
        $this->assertSame(0, $all[0]->index());
        $this->assertSame(1, $all[1]->index());
        $this->assertSame(2, $all[2]->index());
    }

    /**
     * @test
     */
    public function shouldGetAll_all()
    {
        // given
        $switcher = new MatchSwitcher($this->switcherAll('14'), $this->factory(), $this->mock());

        // when
        /** @var Match[] $all */
        $all = $switcher->all();

        // then
        $this->assertSame(['14', '19', '25'], $all[0]->all());
        $this->assertSame(['14', '19', '25'], $all[1]->all());
        $this->assertSame(['14', '19', '25'], $all[2]->all());
    }

    /**
     * @test
     */
    public function shouldGetAll_first()
    {
        // given
        $switcher = new MatchSwitcher($this->switcherFirst(''), $this->factory(), new EagerMatchAllFactory($this->matchesOffset('First')));

        // when
        $first = $switcher->first();

        // then
        $this->assertSame(['First', '19', '25'], $first->all());
    }

    private function switcherAll($firstValue): BaseSwitcher
    {
        /** @var BaseSwitcher|MockObject $switcher */
        $switcher = $this->createMock(BaseSwitcher::class);
        $switcher->expects($this->once())->method('all')->willReturn($this->matchesOffset($firstValue));
        $switcher->expects($this->never())->method($this->logicalNot($this->matches('all')));
        return $switcher;
    }

    private function switcherFirst(string $value): BaseSwitcher
    {
        /** @var BaseSwitcher|MockObject $switcher */
        $switcher = $this->createMock(BaseSwitcher::class);
        $switcher->expects($this->once())->method('first')->willReturn($this->matchOffset($value));
        $switcher->expects($this->never())->method($this->logicalNot($this->matches('first')));
        return $switcher;
    }

    private function matchesOffset(string $firstValue): IRawMatchesOffset
    {
        return new RawMatchesOffset([[
            [$firstValue, 1],
            ['19', 2],
            ['25', 3],
        ]]);
    }

    private function matchOffset(string $value): RawMatchOffset
    {
        return new RawMatchOffset([[$value, 1]]);
    }

    private function factory(int $limit = null): MatchObjectFactory
    {
        return new MatchObjectFactory(new Subject('switch subject'), $limit ?? -4, new UserData());
    }

    private function mock(): MatchAllFactory
    {
        /** @var MatchAllFactory|MockObject $factory */
        $factory = $this->createMock(MatchAllFactory::class);
        $factory->expects($this->never())->method($this->anything());
        return $factory;
    }
}
