<?php
namespace TRegx\CleanRegex\Internal\Match\Switcher;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
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
        $switcher = new TextSwitcher($this->mock('all', $this->matchesOffset()));

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
        $switcher = new TextSwitcher($this->mock('first', new RawMatchOffset([['Lorem ipsum', 1]])));

        // when
        $first = $switcher->first();

        // then
        $this->assertSame('Lorem ipsum', $first);
    }

    /**
     * @test
     */
    public function shouldGetFirstKey()
    {
        // given
        $switcher = new TextSwitcher($this->mock('firstKey', 123));

        // when
        $firstKey = $switcher->firstKey();

        // then
        $this->assertSame(123, $firstKey);
    }

    private function mock(string $methodName, $value): BaseSwitcher
    {
        /** @var BaseSwitcher|MockObject $switcher */
        $switcher = $this->createMock(BaseSwitcher::class);
        $switcher->expects($this->once())->method($methodName)->willReturn($value);
        $switcher->expects($this->never())->method($this->logicalNot($this->matches($methodName)));
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
