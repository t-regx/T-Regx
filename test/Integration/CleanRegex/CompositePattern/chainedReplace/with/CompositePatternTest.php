<?php
namespace Test\Integration\TRegx\CleanRegex\CompositePattern\chainedReplace\with;

use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\CompositePattern;
use TRegx\CleanRegex\Internal\CompositePatternMapper;
use function array_slice;

class CompositePatternTest extends TestCase
{
    /**
     * @test
     * @dataProvider times
     * @param int $times
     * @param string $expected
     */
    public function test(int $times, string $expected)
    {
        // given
        $patterns = [
            "at's ai",
            "th__r you're bre",
            "nk __ath",
            "thi__ing",
            '(\s+|\?)',
        ];
        $slicedPatterns = array_slice($patterns, 0, $times);
        $pattern = new CompositePattern((new CompositePatternMapper($slicedPatterns))->createPatterns());

        // when
        $replaced = $pattern->chainedReplace("Do you think that's air you're breathing now?")->with('__');

        // then
        $this->assertEquals($expected, $replaced);
    }

    public function times(): array
    {
        return [
            [0, "Do you think that's air you're breathing now?"],
            [1, "Do you think th__r you're breathing now?"],
            [2, 'Do you think __athing now?'],
            [3, 'Do you thi__ing now?'],
            [4, 'Do you __ now?'],
            [5, 'Do__you______now__'],
            [6, 'Do__you______now__'],
            [7, 'Do__you______now__'],
        ];
    }

    /**
     * @test
     */
    public function shouldQuoteReferences()
    {
        // given
        $patterns = ['One(1)', 'Two(2)', 'Three(3)'];
        $pattern = new CompositePattern((new CompositePatternMapper($patterns))->createPatterns());

        // when
        $replaced = $pattern->chainedReplace("One1 Two2 Three3")->with('$1');

        // then
        $this->assertEquals('$1 $1 $1', $replaced);
    }

    /**
     * @test
     */
    public function shouldReplaceWithReferences()
    {
        // given
        $pattern = new CompositePattern((new CompositePatternMapper(['One(1)', 'Two(2)', 'Three(3)']))->createPatterns());

        // when
        $replaced = $pattern->chainedReplace("One1 Two2 Three3")->withReferences('$1');

        // then
        $this->assertEquals('1 2 3', $replaced);
    }
}
