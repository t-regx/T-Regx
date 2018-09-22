<?php
namespace Test\Unit\TRegx\CleanRegex\Split;

use TRegx\CleanRegex\Internal\InternalPattern as Pattern;
use TRegx\CleanRegex\SplitPattern;
use PHPUnit\Framework\TestCase;

class SplitPatternTest extends TestCase
{
    /**
     * @test
     */
    public function shouldSplit_excludingDelimiter()
    {
        // given
        $splitPattern = new SplitPattern(new Pattern('([.+|])'), '192..168+++172||16');

        // when
        $result = $splitPattern->ex();

        // then
        $this->assertEquals(['192', '', '168', '', '', '172', '', '16'], $result);
    }

    /**
     * @test
     */
    public function shouldSplit_includingDelimiter()
    {
        // given
        $splitPattern = new SplitPattern(new Pattern('([.+|])'), '192..168+++172||16');

        // when
        $result = $splitPattern->inc();

        // then
        $this->assertEquals(['192', '.', '', '.', '168', '+', '', '+', '', '+', '172', '|', '', '|', '16'], $result);
    }
}
