<?php
namespace Test\Interaction\TRegx\CleanRegex\Internal\Prepared;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Internal\Prepared\Parser\BindingParser;
use TRegx\CleanRegex\Internal\Prepared\Parser\InjectParser;
use TRegx\CleanRegex\Internal\Prepared\Parser\Parser;
use TRegx\CleanRegex\Internal\Prepared\Parser\PreparedParser;
use TRegx\CleanRegex\Internal\Prepared\PrepareFacade;
use TRegx\CleanRegex\Internal\Prepared\Template\NoTemplate;

class PrepareFacadeTest extends TestCase
{
    /**
     * @test
     * @dataProvider standard
     * @param Parser $parser
     */
    public function test_standard(Parser $parser)
    {
        // given + when
        $pattern = PrepareFacade::build($parser, false, '');

        // then
        $this->assertSame('/(I|We) want: User\ \(input\) :)/', $pattern->delimited());
    }

    public function standard(): array
    {
        return [
            'bind @'      => [new BindingParser('(I|We) want: @input :)', ['input' => 'User (input)'], new NoTemplate())],
            'bind ``'     => [new BindingParser('(I|We) want: `input` :)', ['input' => 'User (input)'], new NoTemplate())],
            'inject #'    => [new InjectParser('(I|We) want: @ :)', ['User (input)'], new NoTemplate())],
            'prepared []' => [new PreparedParser(['(I|We) want: ', ['User (input)'], ' :)'])]
        ];
    }

    /**
     * @test
     * @dataProvider empty
     * @param Parser $parser
     */
    public function test_empty(Parser $parser)
    {
        // given + when
        $pattern = PrepareFacade::build($parser, false, '');

        // then
        $this->assertSame('//', $pattern->delimited());
    }

    public function empty(): array
    {
        return [
            'bind @'        => [new BindingParser('', [], new NoTemplate())],
            'inject # '     => [new InjectParser('', [], new NoTemplate())],
            "prepared ''"   => [new PreparedParser([''])],
            "prepared ['']" => [new PreparedParser([['']])],
        ];
    }

    /**
     * @test
     * @dataProvider pcre
     * @param Parser $parser
     * @param string $expected
     */
    public function test_ignoresPcre(Parser $parser, string $expected)
    {
        // given + when
        $pattern = PrepareFacade::build($parser, false, '');

        // then
        $this->assertSame($expected, $pattern->delimited());
    }

    public function pcre(): array
    {
        return [
            'bind //'     => [new BindingParser('//', [], new NoTemplate()), '#//#'],
            'inject //'   => [new InjectParser('//', [], new NoTemplate()), '#//#'],
            'prepared //' => [new PreparedParser(['//']), '#//#'],

            'bind //mi'     => [new BindingParser('//mi', [], new NoTemplate()), '#//mi#'],
            'inject //mi'   => [new InjectParser('//mi', [], new NoTemplate()), '#//mi#'],
            'prepared //mi' => [new PreparedParser(['//mi']), '#//mi#'],
        ];
    }

    /**
     * @test
     * @dataProvider onlyUserInput
     * @param Parser $parser
     */
    public function test_onlyUserInput(Parser $parser)
    {
        // given + when
        $pattern = PrepareFacade::build($parser, false, '');

        // then
        $this->assertSame('/\(/', $pattern->delimited());
    }

    public function onlyUserInput(): array
    {
        return [
            'bind @'      => [new BindingParser('@name', ['name' => '('], new NoTemplate())],
            'inject # '   => [new InjectParser('@', ['('], new NoTemplate())],
            'prepared []' => [new PreparedParser([['(']])],
        ];
    }

    /**
     * @test
     * @dataProvider delimiters
     * @param Parser $parser
     */
    public function test_quotesDelimiters(Parser $parser)
    {
        // given + when
        $pattern = PrepareFacade::build($parser, false, '');

        // then
        $this->assertSame('%With delimiters / #Using\ /\ delimiters\ and\ \% :D%', $pattern->delimited());
    }

    public function delimiters(): array
    {
        return [
            'bind @'      => [new BindingParser('With delimiters / #@input :D', ['input' => 'Using / delimiters and %'], new NoTemplate())],
            'inject #'    => [new InjectParser('With delimiters / #@ :D', ['Using / delimiters and %'], new NoTemplate())],
            'prepared []' => [new PreparedParser(['With delimiters / #', ['Using / delimiters and %'], ' :D'])],
        ];
    }

    /**
     * @test
     * @dataProvider whitespace
     * @param Parser $parser
     */
    public function test_whitespace(Parser $parser)
    {
        // given + when
        $pattern = PrepareFacade::build($parser, false, '');

        // then
        $this->assertSame('/(I|We) want: User\ \(input\)User\ \(input_2\)/', $pattern->delimited());
    }

    public function whitespace(): array
    {
        return [
            'bind @'      => [new BindingParser('(I|We) want: @input@input_2', [
                'input'   => 'User (input)',
                'input_2' => 'User (input_2)',
            ], new NoTemplate())],
            'inject #'    => [new InjectParser('(I|We) want: @@', ['User (input)', 'User (input_2)'], new NoTemplate())],
            'prepared []' => [new PreparedParser(['(I|We) want: ', ['User (input)'], ['User (input_2)']])],
        ];
    }

    /**
     * @test
     * @dataProvider ignoredInputs
     * @param Parser $parser
     * @param string $expected
     */
    public function shouldIgnoreBindPlaceholders(Parser $parser, string $expected)
    {
        // when
        $pattern = PrepareFacade::build($parser, false, '');

        // then
        $this->assertSame($expected, $pattern->delimited());
    }

    public function ignoredInputs(): array
    {
        return [
            [
                // Should allow for inserting @ placeholders again
                new BindingParser('(I|We) would like to match: @input (and|or) @input2', [
                    'input'  => '@input',
                    'input2' => '@input2',
                ], new NoTemplate()),
                '/(I|We) would like to match: @input (and|or) @input2/',
            ],
            [
                // Should allow for inserting `` placeholders again
                new BindingParser('(I|We) would like to match: `input` (and|or) `input2`', [
                    'input'  => '`input`',
                    'input2' => '`input2`',
                ], new NoTemplate()),
                '/(I|We) would like to match: `input` (and|or) `input2`/',
            ],
            [
                // Should ignore @@ placeholders
                new BindingParser('(I|We) would like to match: @input (and|or) @input_2@', [
                    0 => 'input',
                    1 => 'input_2',
                ], new NoTemplate()),
                '/(I|We) would like to match: @input (and|or) @input_2@/',
            ]
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
        // then
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage($message);

        // when
        PrepareFacade::build($parser, false, '');
    }

    public function invalidInputs(): array
    {
        return [
            [
                new BindingParser('@placeholder', [], new NoTemplate()),
                "Could not find a corresponding value for placeholder 'placeholder'",
            ],
            [
                new BindingParser('@input1 and @input2', ['input1'], new NoTemplate()),
                "Could not find a corresponding value for placeholder 'input2'",
            ],
            [
                new BindingParser('@input', ['input', 'input2'], new NoTemplate()),
                "Could not find a corresponding placeholder for name 'input2'",
            ],
            [
                new BindingParser('@input', ['input' => 'some value', 0 => 'input'], new NoTemplate()),
                "Name 'input' is used more than once (as a key or as ignored value)",
            ],
            [
                new BindingParser('@input', ['input' => 4], new NoTemplate()),
                "Invalid bound value for name 'input'. Expected string, but integer (4) given",
            ],
            [
                new BindingParser('well', [0 => 21], new NoTemplate()),
                'Invalid bound parameters. Expected string, but integer (21) given',
            ],
            [
                new BindingParser('well', ['(asd)' => 21], new NoTemplate()),
                "Invalid name '(asd)'. Expected a string consisting only of alphanumeric characters and an underscore [a-zA-Z0-9_]",
            ],

            [
                new InjectParser('@', [], new NoTemplate()),
                "Could not find a corresponding value for placeholder #0",
            ],
            [
                new InjectParser('@ and @', ['input'], new NoTemplate()),
                "Could not find a corresponding value for placeholder #1",
            ],
            [
                new InjectParser('@', ['input', 'input2'], new NoTemplate()),
                "Superfluous inject value [1 => string ('input2')]",
            ],
            [
                new InjectParser('@@@', ['', '', 'foo' => 4], new NoTemplate()),
                "Invalid inject value for key 'foo'. Expected string, but integer (4) given",
            ],
            [
                new InjectParser('@', [0 => 21], new NoTemplate()),
                "Invalid inject value for key '0'. Expected string, but integer (21) given",
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
