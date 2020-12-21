<?php
namespace Test\Interaction\TRegx\CleanRegex\Internal\Prepared\alternation\prepared;

use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Internal\Prepared\Parser\PreparedParser;
use TRegx\CleanRegex\Internal\Prepared\PrepareFacade;

class PrepareFacadeTest extends TestCase
{
    /**
     * @test
     */
    public function shouldThrow_getPattern_onAlteration()
    {
        // given
        $parser = new PreparedParser(['Either ', [[]], ' :)']);

        // then
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage("Method prepare() doesn't support alteration; expected string, but array (0) given");

        // when
        (new PrepareFacade($parser, false, ''))->getPattern();
    }

    /**
     * @test
     */
    public function shouldThrow_getPattern_onMultipleValues()
    {
        // given
        $parser = new PreparedParser(['Either ', ['one', 'two'], ' :)']);

        // then
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage("Method prepare() doesn't support alteration; only one bound value allowed");

        // when
        (new PrepareFacade($parser, false, ''))->getPattern();
    }

    /**
     * @test
     */
    public function shouldThrow_getPattern_onMissingBoundValue()
    {
        // given
        $parser = new PreparedParser(['Foo', []]);

        // then
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage("Method prepare() doesn't support alteration; bound value is required");

        // when
        (new PrepareFacade($parser, false, ''))->getPattern();
    }
}
