<?php
namespace Test\Interaction\TRegx\CleanRegex\Internal;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Test\DataProviders;
use Test\Utils\Internal;
use TRegx\CleanRegex\Internal\ForArray\FilteredArray;

/**
 * @covers \TRegx\CleanRegex\Internal\ForArray\FilteredArray
 */
class FilteredArrayTest extends TestCase
{
    /**
     * @test
     * @dataProvider patternsAndSubjects
     * @param string $pattern
     * @param array $subjects
     * @param array $expected
     */
    public function shouldFilter(string $pattern, array $subjects, array $expected)
    {
        // given
        $filterArray = new FilteredArray(Internal::pcre($pattern), $subjects);

        // when
        $filtered = $filterArray->filtered();

        // then
        $this->assertSame($expected, $filtered, 'Failed asserting that filterAssoc() returned expected results.');
    }

    public function patternsAndSubjects(): array
    {
        return [
            [
                '/dog/',
                [2 => 'dog', 4 => 'dogs', 6 => 'underdog'],
                [2 => 'dog', 4 => 'dogs', 6 => 'underdog'],
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
     * @dataProvider phpTypes
     * @param null|int|array|callable|resource $listElement
     * @param string $type
     */
    public function shouldThrowOnInvalidArgument($listElement, string $type)
    {
        // given
        $filterArray = new FilteredArray(Internal::pcre(''), ['Foo', $listElement]);

        // then
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("Only elements of type 'string' can be filtered, but $type given");

        // when
        $filterArray->filtered();
    }

    public function phpTypes(): array
    {
        return DataProviders::allPhpTypes('string');
    }
}
