<?php
namespace Test\Integration\TRegx\CleanRegex\Internal\Prepared\PrepareFacade\prepared;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Internal\Prepared\Parser\PreparedParser;
use TRegx\CleanRegex\Internal\Prepared\PrepareFacade;

class PrepareFacadeTest extends TestCase
{
    /**
     * @test
     * @dataProvider validInput
     * @param array $input
     * @param string $expected
     */
    public function shouldPrepare(array $input, string $expected)
    {
        // given
        $facade = new PrepareFacade(new PreparedParser($input), false);

        // when
        $pattern = $facade->getPattern();

        // then
        $this->assertEquals($expected, $pattern);
    }

    public function validInput()
    {
        return [
            [
                // quote regexp parenthesis
                ['(I|We) would like to @match: ', ['User (input)'], ' (and|or) ', ['User (input_2)'], ''],
                '/(I|We) would like to @match: User \(input\) (and|or) User \(input_2\)/'
            ],
            [
                // quote regexp parenthesis - same placeholder
                ['(I|We) like ', ['User (input)', 'User (input_2)'], ''],
                '/(I|We) like User \(input\)User \(input_2\)/'
            ],
            [
                // quote delimiters
                ['With delimiters / #', ['Using / delimiters and %'], ' :D'],
                '%With delimiters / #Using / delimiters and \% :D%',
            ],
            [
                // Should treat pattern as not pcre
                ['/(I|We) would like ', ['(input)'], '/'],
                '#/(I|We) would like \(input\)/#'
            ],

            // Corner values
            [[''], '//',],
            [['', []], '//',],
            [['a', 'b', 'c'], '/abc/',],
        ];
    }

    /**
     * @test
     * @dataProvider invalidInputs
     * @param array $input
     * @param string $message
     */
    public function shouldThrow_onInvalidInput(array $input, string $message)
    {
        // given
        $facade = new PrepareFacade(new PreparedParser($input), false);

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage($message);

        // when
        $facade->getPattern();
    }

    public function invalidInputs(): array
    {
        return [
            [
                ['input', 5],
                'Invalid prepared pattern part. Expected string, but integer (5) given',
            ],
            [
                ['input', [4], 'input'],
                'Invalid bound value. Expected string, but integer (4) given',
            ],
            [
                ['input', [[]], 'input'],
                'Invalid bound value. Expected string, but array (0) given',
            ],
            [
                [],
                'Empty array of prepared pattern parts',
            ],
        ];
    }
}
