<?php
namespace Test\Integration\TRegx\CleanRegex;

use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Internal\InternalPattern as Pattern;
use TRegx\CleanRegex\Match\MatchPattern;

class MatchCountPatternTest extends TestCase
{
    /**
     * @test
     * @dataProvider patternsAndSubjects
     * @param $pattern
     * @param $subject
     * @param $expectedCount
     */
    public function shouldCountMatches(string $pattern, string $subject, int $expectedCount)
    {
        // given
        $matchPattern = new MatchPattern(new Pattern($pattern), $subject);

        // when
        $count = $matchPattern->count();

        // then
        $this->assertEquals($expectedCount, $count, "Failed asserting that count() returned $expectedCount.");
    }

    public function patternsAndSubjects()
    {
        return [
            ['/dog/', 'cat', 0],
            ['/[aoe]/', 'match vowels', 3],
            ['/car(pet)?/', 'car carpet', 2],
            ['/car(p(e(t)))?/', 'car carpet car carpet', 4],
        ];
    }
}
