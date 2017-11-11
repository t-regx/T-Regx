<?php
namespace Test\Danon\CleanRegex;

use Danon\CleanRegex\CountPattern;
use Danon\CleanRegex\Pattern;
use PHPUnit\Framework\TestCase;

class CountPatternTest extends TestCase
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
        $countPattern = new CountPattern(new Pattern($pattern), $subject);

        // when
        $count = $countPattern->count();

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
