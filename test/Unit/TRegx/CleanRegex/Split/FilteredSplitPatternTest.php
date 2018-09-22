<?php
namespace Test\Unit\TRegx\CleanRegex\Split;

use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Internal\InternalPattern as Pattern;
use TRegx\CleanRegex\Split\FilteredSplitPattern;

class FilteredSplitPatternTest extends TestCase
{
    /**
     * @test
     */
    public function shouldSplit_filtered_excludingDelimiter()
    {
        // given
        $splitPattern = new FilteredSplitPattern(new Pattern('([.+|])'), '192..168+++172|||16');

        // when
        $result = $splitPattern->ex();

        // then
        $this->assertEquals(['192', '168', '172', '16'], $result);
    }

    /**
     * @test
     */
    public function shouldSplit_filtered_includingDelimiter()
    {
        // given
        $splitPattern = new FilteredSplitPattern(new Pattern('([.+|])'), '192..168+++172||16');

        // when
        $result = $splitPattern->inc();

        // then
        $this->assertEquals(['192', '.', '.', '168', '+', '+', '+', '172', '|', '|', '16'], $result);
    }
}
