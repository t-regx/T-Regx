<?php
namespace Test\Integration\TRegx\CleanRegex\Split\ex;

use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Internal\InternalPattern;
use TRegx\CleanRegex\Internal\Subject;
use TRegx\CleanRegex\Split\FilteredSplitPattern;
use TRegx\CleanRegex\Split\SplitPatternInterface;

class FilteredSplitPatternTest extends TestCase
{
    /**
     * @test
     */
    public function shouldSplit()
    {
        // given
        $splitPattern = $this->split('([.+|])', '192..168+++172|||16');

        // when
        $result = $splitPattern->ex();

        // then
        $this->assertEquals(['192', '168', '172', '16'], $result);
    }

    /**
     * @test
     */
    public function shouldReturn_unchanged()
    {
        // given
        $splitPattern = $this->split('9', 'Foo,Bar,Cat');

        // when
        $matches = $splitPattern->ex();

        // then
        $this->assertEquals(['Foo,Bar,Cat'], $matches);
    }

    private function split($pattern, $subject): SplitPatternInterface
    {
        return new FilteredSplitPattern(InternalPattern::standard($pattern), new Subject($subject));
    }
}
