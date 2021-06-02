<?php
namespace Test\Interaction\TRegx\CleanRegex\Internal\Prepared;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Test\Utils\AssertsPattern;
use Test\Utils\Functions;
use Test\Utils\Impl\ConstantDelimiter;
use Test\Utils\Impl\MappingAlternation;
use TRegx\CleanRegex\Internal\Delimiter\Strategy\StandardStrategy;
use TRegx\CleanRegex\Internal\Prepared\Parser\InjectParser;
use TRegx\CleanRegex\Internal\Prepared\Parser\Parser;
use TRegx\CleanRegex\Internal\Prepared\Parser\PreparedParser;
use TRegx\CleanRegex\Internal\Prepared\PrepareFacade;
use TRegx\CleanRegex\Internal\Prepared\Template\NoTemplate;

class PrepareFacadeTest extends TestCase
{
    use AssertsPattern;

    /**
     * @test
     * @dataProvider standard
     * @param Parser $parser
     */
    public function test_standard(Parser $parser)
    {
        // given + when
        $pattern = PrepareFacade::build($parser, new ConstantDelimiter(new MappingAlternation(Functions::json())));

        // then
        $this->assertSamePattern('(I|We) want: "User (input)" :)', $pattern);
    }

    public function standard(): array
    {
        return [
            'inject #'    => [new InjectParser('(I|We) want: @ :)', ['User (input)'], new NoTemplate())],
            'prepared []' => [new PreparedParser(['(I|We) want: ', ['User (input)'], ' :)'])]
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
        PrepareFacade::build($parser, new StandardStrategy(''));
    }

    public function invalidInputs(): array
    {
        return [
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
