<?php
namespace TRegx\CleanRegex\Internal\Match\Switcher;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class KeysSwitcherTest extends TestCase
{
    /**
     * @test
     */
    public function shouldGetKeys()
    {
        // given
        $switcher = new KeysSwitcher($this->mock('all', 'willReturn', ['a' => 'One', 'b' => 'Two', 'c' => 'Three']));

        // when
        $keys = $switcher->all();

        // then
        $this->assertSame(['a', 'b', 'c'], $keys);
    }

    /**
     * @test
     */
    public function shouldGetFirst()
    {
        // given
        $switcher = new KeysSwitcher($this->mock('firstKey', 'willReturn', 'One'));

        // when
        $first = $switcher->first();

        // then
        $this->assertSame('One', $first);
    }

    /**
     * @test
     */
    public function shouldFirstKey_beAlwaysZero()
    {
        // given
        $switcher = new KeysSwitcher($this->zeroInteraction());

        // when
        $firstKey = $switcher->firstKey();

        // then
        $this->assertSame(0, $firstKey);
    }

    private function mock(string $methodName, string $setter, $value): Switcher
    {
        /** @var Switcher|MockObject $switcher */
        $switcher = $this->createMock(Switcher::class);
        $switcher->expects($this->once())->method($methodName)->$setter($value);
        $switcher->expects($this->never())->method($this->logicalNot($this->matches($methodName)));
        return $switcher;
    }

    private function zeroInteraction(): Switcher
    {
        /** @var Switcher|MockObject $base */
        $base = $this->createMock(Switcher::class);
        $base->expects($this->never())->method($this->anything());
        return $base;
    }
}
