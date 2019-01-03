<?php
namespace Test\Integration\TRegx\CleanRegex\ForArray;

use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Filter\PregGrepArrayIntersectStrategy;
use TRegx\CleanRegex\Filter\PregMatchForEachStrategy;
use TRegx\CleanRegex\ForArray\FilterArrayKeysPattern;
use TRegx\CleanRegex\Internal\InternalPattern as Pattern;

class FilterArrayKeysPatternTest extends TestCase
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
        $filterArrayPattern = new FilterArrayKeysPattern(new Pattern($pattern), $subjects);
        $strategy1 = new PregGrepArrayIntersectStrategy();
        $strategy2 = new PregMatchForEachStrategy();

        // when
        $filtered1 = $filterArrayPattern->filterByKeys($strategy1);
        $filtered2 = $filterArrayPattern->filterByKeys($strategy2);

        // then
        $this->assertEquals($expected, $filtered1);
        $this->assertEquals($expected, $filtered2);
    }

    public function patternsAndSubjects()
    {
        return [
            [
                '/dog/',
                ['dog' => 0, 'dogs' => 1, 'underdog' => 2],
                ['dog' => 0, 'dogs' => 1, 'underdog' => 2],
            ],
            [
                '/^[ao]$/',
                ['a' => 0, 'b' => 1, 'o' => 2],
                ['a' => 0, 'o' => 2]
            ],
            [
                '/^.$/',
                ['cat' => 0, 'dog' => 1, 'John Wick' => 2],
                [],
            ],
        ];
    }
}
