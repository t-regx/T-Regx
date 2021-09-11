<?php
namespace Test\Unit\TRegx\CleanRegex\Composite\CompositePattern\chainedRemove;

use PHPUnit\Framework\TestCase;
use Test\Utils\Definitions;
use TRegx\CleanRegex\Composite\CompositePattern;

/**
 * @covers \TRegx\CleanRegex\Composite\CompositePattern::chainedRemove
 */
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
        $composite = new CompositePattern($this->nthPatterns($times, [
            "/at's ai/",
            "/thr you're bre/",
            "/nk ath/",
            "/thi{2}ng/",
            '/(\s+|\?)/',
            "/[ou]/"
        ]));

        // when
        $replaced = $composite->chainedRemove("Do you think that's air you're breathing now?");

        // then
        $this->assertSame($expected, $replaced);
    }

    public function times(): array
    {
        return [
            [0, "Do you think that's air you're breathing now?"],
            [1, "Do you think thr you're breathing now?"],
            [2, "Do you think athing now?"],
            [3, "Do you thiing now?"],
            [4, "Do you  now?"],
            [5, "Doyounow"],
            [6, "Dynw"],
        ];
    }

    private function nthPatterns(int $times, array $patterns): array
    {
        return \array_map([Definitions::class, 'pcre'], \array_slice($patterns, 0, $times));
    }
}
