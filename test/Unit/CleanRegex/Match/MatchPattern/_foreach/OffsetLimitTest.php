<?php
namespace Test\Unit\TRegx\CleanRegex\Match\MatchPattern\_foreach;

use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Match\MatchPattern;

class OffsetLimitTest extends TestCase
{
    /**
     * @test
     */
    public function shouldIterateMatchOffset()
    {
        // given
        $result = [];

        // when
        foreach ($this->matchGroup()->offsets() as $offset) {
            $result[] = $offset;
        }

        // then
        $this->assertSame([0, 5, 11], $result);
    }

    /**
     * @test
     */
    public function shouldIterateMatchGroupOffset()
    {
        // given
        $result = [];

        // when
        foreach ($this->matchGroup()->group(1)->offsets() as $offset) {
            $result[] = $offset;
        }

        // then
        $this->assertSame([2, 8, 13], $result);
    }

    private function matchGroup(): MatchPattern
    {
        return pattern('\d+([cm]?m)')->match('14cm 127mm 18m');
    }
}
