<?php
namespace Test\CleanRegex;

use CleanRegex\FilterArrayPattern;
use CleanRegex\Internal\Pattern;
use PHPUnit\Framework\TestCase;

class FilterPatternTest extends TestCase
{
    /**
     * @test
     * @dataProvider patternsAndSubjects
     * @param string $pattern
     * @param array $subjects
     * @param array $expected
     */
    public function shouldFilterArray(string $pattern, array $subjects, array $expected)
    {
        // given
        $filterArrayPattern = new FilterArrayPattern(new Pattern($pattern), $subjects);

        // when
        $filtered = $filterArrayPattern->filter();

        // then
        $this->assertEquals($expected, $filtered, 'Failed asserting that filter() returned expected results.');
    }

    public function patternsAndSubjects()
    {
        return [
            [
                '/dog/',
                ['dog', 'dogs', 'underdog'],
                ['dog', 'dogs', 'underdog'],
            ],
            [
                '/^[aoe]$/',
                ['a', 'b', 'o'],
                ['a', 'o']
            ],
            [
                '/^.$/',
                ['cat', 'dog', 'John Wick'],
                [],
            ],
        ];
    }
}
