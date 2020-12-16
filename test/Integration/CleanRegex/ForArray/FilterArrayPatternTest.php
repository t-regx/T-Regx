<?php
namespace Test\Integration\TRegx\CleanRegex\ForArray;

use PHPUnit\Framework\TestCase;
use Test\DataProviders;
use TRegx\CleanRegex\ForArray\FilterArrayPattern;
use TRegx\CleanRegex\Internal\InternalPattern;
use TRegx\DataProvider\CrossDataProviders;

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
        $filterArrayPattern = new FilterArrayPattern(InternalPattern::pcre($pattern), $subjects, false);

        // when
        $filtered = $filterArrayPattern->filter();

        // then
        $this->assertEquals($expected, $filtered, 'Failed asserting that filter() returned expected results.');
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
        $filterArrayPattern = new FilterArrayPattern(InternalPattern::pcre($pattern), $subjects, false);

        // when
        $filtered = $filterArrayPattern->filterAssoc();

        // then
        $this->assertEquals($expected, $filtered, 'Failed asserting that filterAssoc() returned expected results.');
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

    /**
     * @test
     * @dataProvider filterMethods
     * @param string $method
     * @param null|int|array|callable|resource $listElement
     */
    public function shouldFilter_safe(string $method, $listElement)
    {
        // given
        $input = ['Foo', 1, $listElement];

        // when
        $output = pattern('')->forArray($input)->$method();

        // then
        $this->assertEquals(['Foo'], $output);
    }

    function filterMethods(): array
    {
        return CrossDataProviders::cross(
            [['filter'], ['filterAssoc']],
            DataProviders::allPhpTypes('string')
        );
    }
}
