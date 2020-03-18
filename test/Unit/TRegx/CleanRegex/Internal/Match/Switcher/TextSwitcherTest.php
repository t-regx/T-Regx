<?php
namespace TRegx\CleanRegex\Internal\Match\Switcher;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Internal\Model\Match\IRawMatchOffset;
use TRegx\CleanRegex\Internal\Model\Match\RawMatchOffset;
use TRegx\CleanRegex\Internal\Model\Matches\IRawMatchesOffset;
use TRegx\CleanRegex\Internal\Model\Matches\RawMatchesOffset;

class TextSwitcherTest extends TestCase
{
    /**
     * @test
     */
    public function shouldDelegateAll()
    {
        // given
        $switcher = new TextSwitcher($this->switcherAll($this->matchesOffset()));

        // when
        $all = $switcher->all();

        // then
        $this->assertSame(['Lorem', 'Foo', 'Bar'], $all);
    }

    /**
     * @test
     */
    public function shouldDelegateFirst()
    {
        // given
        $switcher = new TextSwitcher($this->switcherFirst(new RawMatchOffset([['Lorem ipsum', 1]])));

        // when
        $first = $switcher->first();

        // then
        $this->assertSame('Lorem ipsum', $first);
    }

    private function switcherAll(IRawMatchesOffset $matches): BaseSwitcher
    {
        /** @var BaseSwitcher|MockObject $switcher */
        $switcher = $this->createMock(BaseSwitcher::class);
        $switcher->expects($this->once())->method('all')->willReturn($matches);
        $switcher->expects($this->never())->method($this->logicalNot($this->matches('all')));
        return $switcher;
    }

    private function switcherFirst(IRawMatchOffset $match): BaseSwitcher
    {
        /** @var BaseSwitcher|MockObject $switcher */
        $switcher = $this->createMock(BaseSwitcher::class);
        $switcher->expects($this->once())->method('first')->willReturn($match);
        $switcher->expects($this->never())->method($this->logicalNot($this->matches('first')));
        return $switcher;
    }

    private function matchesOffset(): IRawMatchesOffset
    {
        return new RawMatchesOffset([[
            ['Lorem', 1],
            ['Foo', 2],
            ['Bar', 3],
        ]]);
    }
}
