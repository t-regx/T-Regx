<?php
namespace Test\Integration\TRegx\CleanRegex\Internal\Prepared\PrepareFacade\inject;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Internal\Prepared\Parser\InjectParser;
use TRegx\CleanRegex\Internal\Prepared\PrepareFacade;

class PrepareFacadeTest extends TestCase
{
    /**
     * @test
     * @dataProvider validInput
     * @param string $input
     * @param array $values
     * @param string $expected
     */
    public function shouldInject(string $input, array $values, string $expected)
    {
        // given
        $facade = new PrepareFacade(new InjectParser($input, $values), false);

        // when
        $pattern = $facade->getPattern();

        // then
        $this->assertEquals($expected, $pattern);
    }

    public function validInput()
    {
        return [
            [
                '(I|We) would like to match: @ (and|or) @',
                ['User (input)', 'User (input_2)'],
                '/(I|We) would like to match: User \(input\) (and|or) User \(input_2\)/'
            ],
            [
                '(I|We) would like to match: @@',
                ['User (input)', 'User (input_2)'],
                '/(I|We) would like to match: User \(input\)User \(input_2\)/'
            ],
            [
                'With delimiters / #@',
                ['Using / delimiters # and %',],
                '%With delimiters / #Using / delimiters \# and \%%'
            ],
        ];
    }

    /**
     * @test
     * @dataProvider invalidInputs
     * @param string $input
     * @param array $values
     * @param string $message
     */
    public function shouldThrow_onInvalidInput(string $input, array $values, string $message)
    {
        // given
        $facade = new PrepareFacade(new InjectParser($input, $values), false);

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage($message);

        // when
        $facade->getPattern();
    }

    public function invalidInputs(): array
    {
        return [
            [
                '@',
                [],
                "Could not find a corresponding value for placeholder #0",
            ],
            [
                '@ and @',
                ['input'],
                "Could not find a corresponding value for placeholder #1",
            ],
            [
                '@',
                ['input', 'input2',],
                "Superfluous bind value [integer (1) => string ('input2')]",
            ],
            [
                '@@@',
                ['', '', 'foo' => 4],
                "Invalid inject value for key - string ('foo'). Expected string, but integer (4) given",
            ],
            [
                '@@',
                ['', []],
                "Invalid inject value for key - integer (1). Expected string, but array (0) given",
            ],
        ];
    }
}
