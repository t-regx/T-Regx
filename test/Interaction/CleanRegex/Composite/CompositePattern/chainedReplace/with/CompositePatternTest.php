<?php
namespace Test\Interaction\TRegx\CleanRegex\Composite\CompositePattern\chainedReplace\with;

use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Composite\CompositePattern;
use TRegx\CleanRegex\Internal\InternalPattern;

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
        $pattern = new CompositePattern($this->nthPatterns($times, [
            "/at's ai/",
            "/th__r you're bre/",
            '/nk __ath/',
            '/thi__ing/',
            '/(\s+|\?)/',
        ]));

        // when
        $replaced = $pattern->chainedReplace("Do you think that's air you're breathing now?")->with('__');

        // then
        $this->assertSame($expected, $replaced);
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
        $pattern = new CompositePattern([
            InternalPattern::pcre('/One(1)/'),
            InternalPattern::pcre('/Two(2)/'),
            InternalPattern::pcre('/Three(3)/'),
        ]);

        // when
        $replaced = $pattern->chainedReplace("One1 Two2 Three3")->with('$1');

        // then
        $this->assertSame('$1 $1 $1', $replaced);
    }

    /**
     * @test
     */
    public function shouldReplaceWithReferences()
    {
        // given
        $pattern = new CompositePattern([
            InternalPattern::pcre('/One(1)/'),
            InternalPattern::pcre('/Two(2)/'),
            InternalPattern::pcre('/Three(3)/'),
        ]);

        // when
        $replaced = $pattern->chainedReplace("One1 Two2 Three3")->withReferences('$1');

        // then
        $this->assertSame('1 2 3', $replaced);
    }

    private function nthPatterns(int $times, array $patterns): array
    {
        return \array_map([InternalPattern::class, 'pcre'], \array_slice($patterns, 0, $times));
    }
}
