<?php
namespace Test\Integration\TRegx\CleanRegex;

use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\CountPattern;
use TRegx\CleanRegex\Internal\InternalPattern as Pattern;
use TRegx\CleanRegex\Internal\SubjectableImpl;

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
        $countPattern = new CountPattern(new Pattern($pattern), new SubjectableImpl($subject));

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
