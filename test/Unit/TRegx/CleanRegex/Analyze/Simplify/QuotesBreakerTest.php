<?php
namespace Test\Unit\TRegx\CleanRegex\Analyze\Simplify;

use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Analyze\Simplify\QuotesBreaker;

class QuotesBreakerTest extends TestCase
{
    /**
     * @test
     * @dataProvider patterns
     * @param string $pattern
     * @param array  $expected
     */
    public function shouldSplit(string $pattern, array $expected)
    {
        // given
        $breaker = new QuotesBreaker($pattern);

        // when
        $split = $breaker->split();

        // then
        $this->assertEquals($expected, $split);
    }

    public function patterns(): array
    {
        return [
            ['unquoted [a-z]', ['unquoted ', '[', 'a-z', ']']],
            ['quoted \[a-z]', ['quoted ', '\[', 'a-z', ']']],
            ['double quoted \\\[a-z]', ['double quoted ', '\\\\', '[', 'a-z', ']']],
            ['closed group []a-z]', ['closed group ', '[', ']', 'a-z', ']']],

            [
                '(a|b) \\\Q \\\\\\[a-z] \E',
                [
                    '(a|b) ',
                    '\\\\',
                    'Q ',
                    '\\\\',
                    '\\[',
                    'a-z',
                    ']',
                    ' ',
                    '\E'
                ]
            ],

            [
                '[a-z] \\\Q\Q \\\\\\[a-z] \E [a-z\.] \E\E',
                [
                    '[',
                    'a-z',
                    ']',
                    ' ',
                    '\\\\',
                    'Q',
                    '\Q \\\\\\[a-z] \E',
                    ' ',
                    '[',
                    'a-z',
                    '\.',
                    ']',
                    ' ',
                    '\E',
                    '\E'
                ]
            ],
        ];
    }
}
