<?php
namespace Test\Integration\TRegx\CleanRegex\Internal\Prepared\alternation\prepared;

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
        $parser = new PreparedParser(['Either ', ['5/6'], ' or ', [[]], ' :)']);

        // then
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage("Method prepare() doesn't support alteration; expected string, but array (0) given");

        // when
        (new PrepareFacade($parser, false, ''))->getPattern();
    }
}
