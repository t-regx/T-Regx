<?php
namespace Test\Interaction\TRegx\CleanRegex\ForArray;

use PHPUnit\Framework\TestCase;
use Test\Utils\Internal;
use TRegx\CleanRegex\ForArray\FilterArrayPattern;

/**
 * @covers \TRegx\CleanRegex\ForArray\FilterArrayPattern
 */
class FilterArrayPatternTest extends TestCase
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
        $filterArrayPattern = new FilterArrayPattern(Internal::pcre($pattern), $subjects, false);

        // when
        $filtered = $filterArrayPattern->filter();

        // then
        $this->assertSame($expected, $filtered, 'Failed asserting that filter() returned expected results.');
    }

    public function patternsAndSubjects(): array
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

    /**
     * @test
     * @dataProvider patternsAndSubjects_assoc
     * @param string $pattern
     * @param array $subjects
     * @param array $expected
     */
    public function shouldFilterArray_assoc(string $pattern, array $subjects, array $expected)
    {
        // given
        $filterArrayPattern = new FilterArrayPattern(Internal::pcre($pattern), $subjects, false);

        // when
        $filtered = $filterArrayPattern->filterAssoc();

        // then
        $this->assertSame($expected, $filtered, 'Failed asserting that filterAssoc() returned expected results.');
    }

    public function patternsAndSubjects_assoc(): array
    {
        return [
            [
                '/dog/',
                ['dog', 'dogs', 'underdog'],
                ['dog', 'dogs', 'underdog'],
            ],
            [
                '/^[aoe]$/',
                ['a' => 'a', 'b' => 'b', 'o' => 'o'],
                ['a' => 'a', 'o' => 'o']
            ],
            [
                '/^.$/',
                ['cat', 'dog', 'John Wick'],
                [],
            ],
        ];
    }
}
