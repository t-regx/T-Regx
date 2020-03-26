<?php
namespace TRegx\CleanRegex\Internal\Match\Switcher;

use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Internal\Exception\NoFirstSwitcherException;

class ArraySwitcherTest extends TestCase
{
    /**
     * @test
     */
    public function shouldGetAll()
    {
        // given
        $switcher = new ArraySwitcher(['One', 'Two', 'Three']);

        // when
        $all = $switcher->all();

        // then
        $this->assertSame(['One', 'Two', 'Three'], $all);
    }

    /**
     * @test
     */
    public function shouldGetFirst()
    {
        // given
        $switcher = new ArraySwitcher(['One', 'Two', 'Three']);

        // when
        $first = $switcher->first();

        // then
        $this->assertSame('One', $first);
    }

    /**
     * @test
     */
    public function shouldGetFirstKey()
    {
        // given
        $elements = [10 => 'One', 20 => 'Two', 30 => 'Three'];
        next($elements);
        next($elements); # Intentionally move internal pointer
        $switcher = new ArraySwitcher($elements);

        // when
        $firstKey = $switcher->firstKey();

        // then
        $this->assertSame(10, $firstKey);
    }

    /**
     * @test
     */
    public function shouldGetFirst_assoc()
    {
        // given
        $switcher = new ArraySwitcher(['a' => 'One', 'b' => 'Two', 'c' => 'Three']);

        // when
        $first = $switcher->first();

        // then
        $this->assertSame('One', $first);
    }

    /**
     * @test
     */
    public function shouldFirstThrow()
    {
        // given
        $switcher = new ArraySwitcher([]);

        // then
        $this->expectException(NoFirstSwitcherException::class);

        // when
        $switcher->first();
    }

    /**
     * @test
     */
    public function shouldFirstReturnInteger()
    {
        // given
        $switcher = new ArraySwitcher([1]);

        // when
        $first = $switcher->first();

        // then
        $this->assertSame(1, $first);
    }
}
