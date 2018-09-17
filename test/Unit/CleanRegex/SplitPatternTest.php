<?php
namespace Test\Unit\CleanRegex;

use CleanRegex\Internal\Pattern;
use CleanRegex\SplitPattern;
use PHPUnit\Framework\TestCase;

class SplitPatternTest extends TestCase
{
    /**
     * @test
     */
    public function shouldSplit()
    {
        // given
        $splitPattern = new SplitPattern(new Pattern('([.+|])'), '192.168+172|16');

        // when
        $result = $splitPattern->split();

        // then
        $this->assertEquals(['192', '168', '172', '16'], $result);
    }

    /**
     * @test
     */
    public function shouldSeparate()
    {
        // given
        $splitPattern = new SplitPattern(new Pattern('([.+|])'), '192.168+172|16');

        // when
        $result = $splitPattern->separate();

        // then
        $this->assertEquals(['192', '.', '168', '+', '172', '|', '16'], $result);
    }
}
