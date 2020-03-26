<?php
namespace TRegx\CleanRegex\Internal\Match\Switcher;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Exception\IntegerFormatException;
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
        $switcher = new IntSwitcher($this->mock('all', 'willReturn', $this->matchesOffset('14')));

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
        $switcher = new IntSwitcher($this->mock('all', 'willReturn', new RawMatchesOffset([[]])));

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
        $switcher = new IntSwitcher($this->mock('first', 'willReturn', new RawMatchOffset([['192', 1]])));

        // when
        $first = $switcher->first();

        // then
        $this->assertSame(192, $first);
    }

    /**
     * @test
     */
    public function shouldGetFirstKey()
    {
        // given
        $switcher = new IntSwitcher($this->mock('firstKey', 'willReturn', 123));

        // when
        $firstKey = $switcher->firstKey();

        // then
        $this->assertSame(123, $firstKey);
    }

    /**
     * @test
     */
    public function shouldAll_throwForMalformedInteger()
    {
        // given
        $switcher = new IntSwitcher($this->mock('all', 'willreturn', $this->matchesOffset('Foo')));

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
        $switcher = new IntSwitcher($this->mock('first', 'willReturn', new RawMatchOffset([['Foo', 1]])));

        // then
        $this->expectException(IntegerFormatException::class);
        $this->expectExceptionMessage("Expected to parse 'Foo', but it is not a valid integer");

        // when
        $switcher->first();
    }

    private function mock(string $methodName, string $setter, $value): BaseSwitcher
    {
        /** @var BaseSwitcher|MockObject $switcher */
        $switcher = $this->createMock(BaseSwitcher::class);
        $switcher->expects($this->once())->method($methodName)->$setter($value);
        $switcher->expects($this->never())->method($this->logicalNot($this->matches($methodName)));
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
