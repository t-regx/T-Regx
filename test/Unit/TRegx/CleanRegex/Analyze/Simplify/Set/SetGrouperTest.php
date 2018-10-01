<?php
namespace Test\Unit\TRegx\CleanRegex\Analyze\Simplify\Set;

use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Analyze\Simplify\Set\SetGrouper;
use TRegx\CleanRegex\Analyze\Simplify\Model\EscapedLiteral;
use TRegx\CleanRegex\Analyze\Simplify\Model\Group;
use TRegx\CleanRegex\Analyze\Simplify\Model\Literal;
use TRegx\CleanRegex\Analyze\Simplify\Model\Quote;
use TRegx\CleanRegex\Analyze\Simplify\ModelFactory;
use TRegx\CleanRegex\Analyze\Simplify\QuotesBreaker;

class GrouperTest extends TestCase
{
    /**
     * @test
     * @dataProvider patterns
     * @param string $pattern
     * @param array  $expected
     */
    public function shouldCreate(string $pattern, array $expected)
    {
        // given
        $factory = new SetGrouper(new QuotesBreaker($pattern), new ModelFactory());

        // when
        $models = $factory->getGrouped();

        // then
        $this->assertEquals($expected, $models);
    }

    public function patterns(): array
    {
        return [
            [
                'opened group #2 [[]',
                [
                    new Literal('opened group #2 '),
                    new Group(['['])
                ]
            ],
            [
                'closed group []a-z]',
                [
                    new Literal('closed group '),
                    new Group([']', 'a-z'])
                ]
            ],
            [
                'opened group #1 [[a-z]',
                [
                    new Literal('opened group #1 '),
                    new Group(['[', 'a-z'])
                ]
            ],
            [
                'unquoted [a-z]',
                [
                    new Literal('unquoted '),
                    new Group(['a-z'])
                ]
            ],

            [
                'double quoted \\\[a-z]',
                [
                    new Literal('double quoted '),
                    new EscapedLiteral('\\\\'),
                    new Group(['a-z'])
                ]
            ],
            [
                'quote \\\Q \\\\\\[a-z] \E',
                [
                    new Literal('quote '),
                    new EscapedLiteral('\\\\'),
                    new Literal('Q '),
                    new EscapedLiteral('\\\\'),
                    new EscapedLiteral('\\['),
                    new Literal('a-z'),
                    new Literal(']'),
                    new Literal(' '),
                    new EscapedLiteral('\E')
                ]
            ],

            [
                '[a-z] embedded quotes \\\Q\Q \\\\\\[a-z] \E [a-z\.] \E\E',
                [
                    new Group(['a-z']),
                    new Literal(' embedded quotes '),
                    new EscapedLiteral('\\\\'),
                    new Literal('Q'),
                    new Quote('\Q \\\\\\[a-z] \E'),
                    new Literal(' '),
                    new Group(['a-z', '\.']),
                    new Literal(' '),
                    new EscapedLiteral('\E'),
                    new EscapedLiteral('\E')
                ]
            ],

            [
                '\\\\ \Q not ended quote',
                [
                    new EscapedLiteral('\\\\'),
                    new Literal(' '),
                    new Quote('\Q not ended quote'),
                ]
            ],
        ];
    }
}
