<?php
namespace Test\Feature\CleanRegex\Match\_foreach;

use PHPUnit\Framework\TestCase;
use Test\Utils\DetailFunctions;
use TRegx\CleanRegex\Match\MatchPattern;

class MatchPatternTest extends TestCase
{
    /**
     * @test
     */
    public function shouldIterateMatch()
    {
        // given
        $result = [];

        // when
        foreach ($this->match() as $match) {
            $result[] = $match->text();
        }

        // then
        $this->assertSame(['127', '0', '1', '2'], $result);
    }

    /**
     * @test
     */
    public function shouldIterateMatch_forEach()
    {
        // given
        $pattern = $this->match()->stream()->filter(DetailFunctions::oneOf(['127', '1']));
        $result = [];

        // when
        foreach ($pattern as $match) {
            $result[] = $match->text();
        }

        // then
        $this->assertSame(['127', '1'], $result);
    }

    private function match(): MatchPattern
    {
        return pattern('\d+')->match('127.0.1.2');
    }
}
