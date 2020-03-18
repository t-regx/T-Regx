<?php
namespace TRegx\CleanRegex\Internal\Match\Switcher;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Exception\IntegerFormatException;
use TRegx\CleanRegex\Internal\Model\Match\IRawMatchOffset;
use TRegx\CleanRegex\Internal\Model\Match\RawMatchOffset;
use TRegx\CleanRegex\Internal\Model\Matches\IRawMatchesOffset;
use TRegx\CleanRegex\Internal\Model\Matches\RawMatchesOffset;

class IntSwitcherTest extends TestCase
{
    /**
     * @test
     */
    public function shouldDelegateAll()
    {
        // given
        $switcher = new IntSwitcher($this->switcherAll($this->matchesOffset('14')));

        // when
        $all = $switcher->all();

        // then
        $this->assertSame([14, 19, 25], $all);
    }

    /**
     * @test
     */
    public function shouldDelegateAll_unmatched()
    {
        // given
        $switcher = new IntSwitcher($this->switcherAll(new RawMatchesOffset([[]])));

        // when
        $all = $switcher->all();

        // then
        $this->assertSame([], $all);
    }

    /**
     * @test
     */
    public function shouldDelegateFirst()
    {
        // given
        $switcher = new IntSwitcher($this->switcherFirst(new RawMatchOffset([['192', 1]])));

        // when
        $first = $switcher->first();

        // then
        $this->assertSame(192, $first);
    }

    /**
     * @test
     */
    public function shouldAll_throwForMalformedInteger()
    {
        // given
        $switcher = new IntSwitcher($this->switcherAll($this->matchesOffset('Foo')));

        // then
        $this->expectException(IntegerFormatException::class);
        $this->expectExceptionMessage("Expected to parse 'Foo', but it is not a valid integer");

        // when
        $switcher->all();
    }

    /**
     * @test
     */
    public function shouldFirst_throwForMalformedInteger()
    {
        // given
        $switcher = new IntSwitcher($this->switcherFirst(new RawMatchOffset([['Foo', 1]])));

        // then
        $this->expectException(IntegerFormatException::class);
        $this->expectExceptionMessage("Expected to parse 'Foo', but it is not a valid integer");

        // when
        $switcher->first();
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

    private function matchesOffset(string $firstValue): IRawMatchesOffset
    {
        return new RawMatchesOffset([[
            [$firstValue, 1],
            ['19', 2],
            ['25', 3],
        ]]);
    }
}
