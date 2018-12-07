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
    public function shouldInject(array $input, string $expected)
    {
        // given
        $facade = new PrepareFacade(new PreparedParser($input));

        // when
        $pattern = $facade->getPattern();

        // then
        $this->assertEquals($expected, $pattern);
    }

    public function validInput()
    {
        return [
            [
                ['(I|We) would like to @match: ', ['User (input)'], ' (and|or) ', ['User (input_2)'], ''],
                '/(I|We) would like to @match: User \(input\) (and|or) User \(input_2\)/'
            ],
            [
                ['(I|We) like ', ['User (input)', 'User (input_2)'], ''],
                '/(I|We) like User \(input\)User \(input_2\)/'
            ],
            [
                ['(I|We) would like to match: ', ['User (input)'], ' ', ['User (input_2)']],
                '/(I|We) would like to match: User \(input\) User \(input_2\)/'
            ],
            [
                ['With delimiters / #', ['Using / delimiters # and %'], ' :D'],
                '%With delimiters / #Using / delimiters \# and \% :D%',
            ],
            [
                [''],
                '//',
            ],
            [
                ['', []],
                '//',
            ],
            [
                ['a', 'b', 'c'],
                '/abc/',
            ],
            [
                ['(I|We) would like to match: '],
                '/(I|We) would like to match: /'
            ],
            [
                ['/(I|We) would like ', ['(input)'], '/'],
                '/(I|We) would like \(input\)/'
            ],
            [
                ['#(I|We) would like ', ['(input)'], '#'],
                '#(I|We) would like \(input\)#'
            ],
            [
                ['#(I|We) would like ', ['(input)'], '#mi'],
                '#(I|We) would like \(input\)#mi'
            ],
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
        $facade = new PrepareFacade(new PreparedParser($input));

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
                'Invalid injected value. Expected string, but integer (4) given',
            ],
            [
                ['input', [[]], 'input'],
                'Invalid injected value. Expected string, but array (0) given',
            ],
            [
                [],
                'Empty array of prepared pattern parts',
            ],
        ];
    }
}
