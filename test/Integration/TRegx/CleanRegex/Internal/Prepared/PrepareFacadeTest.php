<?php
namespace Test\Integration\TRegx\CleanRegex\Internal\Prepared;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Internal\Prepared\Parser\BindingParser;
use TRegx\CleanRegex\Internal\Prepared\Parser\InjectParser;
use TRegx\CleanRegex\Internal\Prepared\Parser\Parser;
use TRegx\CleanRegex\Internal\Prepared\Parser\PreparedParser;
use TRegx\CleanRegex\Internal\Prepared\PrepareFacade;

class PrepareFacadeTest extends TestCase
{
    /**
     * @test
     * @dataProvider validInput
     * @param Parser $parser
     * @param string $expected
     */
    public function test(Parser $parser, string $expected)
    {
        // given
        $facade = new PrepareFacade($parser, false);

        // when
        $pattern = $facade->getPattern();

        // then
        $this->assertEquals($expected, $pattern);
    }

    public function validInput()
    {
        return [
            // Bind

            [
                // find by @, quote regexp parenthesis
                new BindingParser('(I|We) would like to match: @input (and|or) @input_2', [
                    'input' => 'User (input)',
                    'input_2' => 'User (input_2)',
                ]),
                '/(I|We) would like to match: User \(input\) (and|or) User \(input_2\)/'
            ],
            [
                // find by ``, quote regexp parenthesis
                new BindingParser('(I|We) would like to match: `input` (and|or) `input_2`', [
                    'input' => 'User (input)',
                    'input_2' => 'User (input_2)',
                ]),
                '/(I|We) would like to match: User \(input\) (and|or) User \(input_2\)/'
            ],
            [
                // find placeholders without whitespace
                new BindingParser('(I|We) would like to match: @input@input_2', [
                    'input' => 'User (input)',
                    'input_2' => 'User (input_2)',
                ]),
                '/(I|We) would like to match: User \(input\)User \(input_2\)/'
            ],
            [
                // quote delimiters
                new BindingParser('With delimiters / #@input', ['input' => 'Using / delimiters and %',]),
                '%With delimiters / #Using / delimiters and \%%'
            ],

            // Injection
            // Standard
            [
                // find by @, quote regexp parenthesis
                new InjectParser('(I|We) would like to match: @ (and|or) @', ['User (input)', 'User (input_2)']),
                '/(I|We) would like to match: User \(input\) (and|or) User \(input_2\)/'
            ],
            [
                // find placeholders without whitespace
                new InjectParser('(I|We) would like to match: @@', ['User (input)', 'User (input_2)']),
                '/(I|We) would like to match: User \(input\)User \(input_2\)/'
            ],
            [
                // quote delimiters
                new InjectParser('With delimiters / #@', ['Using / delimiters and %']),
                '%With delimiters / #Using / delimiters and \%%'
            ],
            // Alternation
            [
                new InjectParser('(Me|@) / would like to alternate none: @', ['%Hello%', []]),
                '#(Me|%Hello%) / would like to alternate none: #',
            ],
            [
                new InjectParser('(Me|@) / would like to alternate single: @', ['%Hello%', ['One']]),
                '#(Me|%Hello%) / would like to alternate single: One#',
            ],
            [
                new InjectParser('I / would # like to alternate many: @', [['On(e)#', 'Tw%o', 'Three']]),
                '%I / would # like to alternate many: On\\(e\\)\\#|Tw\\%o|Three%',
            ],
            // Corner values
            [new InjectParser('', []), '//'],
            [new InjectParser('@', [['%']]), '/%/',],

            [
                // quote regexp parenthesis
                new PreparedParser(['(I|We) would like to @match: ', ['User (input)'], ' (and|or) ', ['User (input_2)'], '']),
                '/(I|We) would like to @match: User \(input\) (and|or) User \(input_2\)/'
            ],
            [
                // quote regexp parenthesis - same placeholder
                new PreparedParser(['(I|We) like ', ['User (input)', 'User (input_2)'], '']),
                '/(I|We) like User \(input\)User \(input_2\)/'
            ],
            [
                // quote delimiters
                new PreparedParser(['With delimiters / #', ['Using / delimiters and %'], ' :D']),
                '%With delimiters / #Using / delimiters and \% :D%',
            ],
            [
                // Should treat pattern as not pcre
                new PreparedParser(['/(I|We) would like ', ['(input)'], '/']),
                '#/(I|We) would like \(input\)/#'
            ],

            // Corner values
            [new PreparedParser(['']), '//',],
            [new PreparedParser(['', []]), '//',],
            [new PreparedParser(['a', 'b', 'c']), '/abc/',],
        ];
    }

    /**
     * @test
     * @dataProvider ignoredInputs
     * @param Parser $parser
     * @param string $expected
     */
    public function shouldIgnorePlaceholders(Parser $parser, string $expected)
    {
        // given
        $facade = new PrepareFacade($parser, false);

        // when
        $pattern = $facade->getPattern();

        // then
        $this->assertEquals($expected, $pattern);
    }

    public function ignoredInputs(): array
    {
        return [
            [
                // Should allow for inserting @ placeholders again
                new BindingParser('(I|We) would like to match: @input (and|or) @input2', [
                    'input' => '@input',
                    'input2' => '@input2',
                ]),
                '/(I|We) would like to match: @input (and|or) @input2/',
            ],
            [
                // Should allow for inserting `` placeholders again
                new BindingParser('(I|We) would like to match: `input` (and|or) `input2`', [
                    'input' => '`input`',
                    'input2' => '`input2`',
                ]),
                '/(I|We) would like to match: `input` (and|or) `input2`/',
            ],
            [
                // Should ignore @@ placeholders
                new BindingParser('(I|We) would like to match: @input (and|or) @input_2@', [
                    0 => 'input',
                    1 => 'input_2',
                ]),
                '/(I|We) would like to match: @input (and|or) @input_2@/',
            ],
            [
                // Should allow no placeholders
                new BindingParser('(I|We) would like to match', []),
                '/(I|We) would like to match/',
            ],
            // Corner values
            [new BindingParser('//', []), '#//#'],
            [new BindingParser('//mi', []), '#//mi#'],
            [new BindingParser('', []), '//'],
        ];
    }

    /**
     * @test
     * @dataProvider invalidInputs
     * @param Parser $parser
     * @param string $message
     */
    public function shouldThrow_onInvalidInput(Parser $parser, string $message)
    {
        // given
        $facade = new PrepareFacade($parser, false);

        // then
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage($message);

        // when
        $facade->getPattern();
    }

    public function invalidInputs(): array
    {
        return [
            [
                new BindingParser('@input2', []),
                "Could not find a corresponding value for placeholder 'input2'",
            ],
            [
                new BindingParser('@input and @input3', ['input']),
                "Could not find a corresponding value for placeholder 'input3'",
            ],
            [
                new BindingParser('@input', ['input', 'input2']),
                "Could not find a corresponding placeholder for name 'input2'",
            ],
            [
                new BindingParser('@input', ['input' => 'some value', 0 => 'input']),
                "Name 'input' is used more than once (as a key or as ignored value)",
            ],
            [
                new BindingParser('@input', ['input' => 4]),
                "Invalid bound value for name 'input'. Expected string, but integer (4) given",
            ],
            [
                new BindingParser('@input', ['input' => []]),
                "Invalid bound value for name 'input'. Expected string, but array (0) given",
            ],
            [
                new BindingParser('well', [0 => 21]),
                'Invalid bound parameters. Expected string, but integer (21) given',
            ],
            [
                new BindingParser('well', ['(asd)' => 21]),
                "Invalid name '(asd)'. Expected a string consisting only of alphanumeric characters and an underscore [a-zA-Z0-9_]",
            ],

            // Standard
            [
                new InjectParser('@', []),
                "Could not find a corresponding value for placeholder #0",
            ],
            [
                new InjectParser('@ and @', ['input']),
                "Could not find a corresponding value for placeholder #1",
            ],
            [
                new InjectParser('@', ['input', 'input2']),
                "Superfluous bind value [integer (1) => string ('input2')]",
            ],
            [
                new InjectParser('@@@', ['', '', 'foo' => 4]),
                "Invalid inject value for key - string ('foo'). Expected string, but integer (4) given",
            ],
            // Alternation
            [
                new InjectParser('@@@', ['', '', 'foo' => ['', 4]]),
                "Invalid inject value for alternating key X. Expected string, but integer (4) given",
            ],
            [
                new InjectParser('@@@', ['', '', 'foo' => ['', []]]),
                "Invalid inject value for alternating key X. Expected string, but array (0) given",
            ],

            [
                new PreparedParser(['input', 5]),
                'Invalid prepared pattern part. Expected string, but integer (5) given',
            ],
            [
                new PreparedParser(['input', [4], 'input']),
                'Invalid bound value. Expected string, but integer (4) given',
            ],
            [
                new PreparedParser([]),
                'Empty array of prepared pattern parts',
            ],
        ];
    }
}
