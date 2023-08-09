<?php
namespace Test\Unit;

use PHPUnit\Framework\TestCase;
use Regex\Pattern;
use Regex\SyntaxException;
use TRegx\PhpUnit\DataProviders\DataProvider;
use function Test\Fixture\Functions\catching;

class _syntaxError extends TestCase
{
    /**
     * @dataProvider malformedPatterns
     */
    public function test(string $pattern, string $expectedMessage)
    {
        catching(fn() => new Pattern($pattern))
            ->assertException(SyntaxException::class)
            ->assertMessage($expectedMessage);
    }

    protected function tearDown(): void
    {
        \error_clear_last();
    }

    public function malformedPatterns(): DataProvider
    {
        return DataProvider::tuples(
            [')', 'Unmatched closing parenthesis at offset 0.'],
            ['+', 'Quantifier does not follow a repeatable item at offset 0.'],
            ['[z-a]', 'Range out of order in character class at offset 3.'],
            ['(?<>)', 'Subpattern name expected at offset 3.'],
            ['(?<123>)', 'Subpattern name must start with a non-digit at offset 3.'],
            ['[[:invalid:]]', 'Unknown POSIX class name at offset 3.'],
            ['(?<=a+)b', 'Lookbehind assertion is not fixed length at offset 0.'],
            ['[\Q]', 'Missing terminating ] for character class at offset 4.'],
        );
    }
}
