<?php
namespace Test\Feature\TRegx\CleanRegex\Match\_foreach;

use PHPUnit\Framework\TestCase;
use Test\Utils\Functions;
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
    public function shouldIterateMatch_asInt()
    {
        // when
        $result = \iterator_to_array($this->match()->asInt());

        // then
        $this->assertSame([127, 0, 1, 2], $result);
    }

    /**
     * @test
     */
    public function shouldIterateMatch_remaining_forEach()
    {
        // given
        $pattern = $this->match()->stream()->filter(Functions::oneOf(['127', '1']));
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
