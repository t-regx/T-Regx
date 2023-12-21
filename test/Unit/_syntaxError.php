<?php
namespace Test\Unit;

use PHPUnit\Framework\TestCase;
use Regex\Pattern;
use Regex\SyntaxException;
use TRegx\PhpUnit\DataProviders\DataProvider;
use function Test\Fixture\Functions\catching;
use function Test\Fixture\Functions\since;

class _syntaxError extends TestCase
{
    /**
     * @dataProvider malformedPatterns
     */
    public function test(string $pattern, string $expectedMessage)
    {
        catching(fn() => new Pattern($pattern))
            ->assertException(SyntaxException::class)
            ->assertMessageStartsWith("$expectedMessage, near position");
    }

    public function malformedPatterns(): DataProvider
    {
        return DataProvider::tuples(
            [')', 'Unmatched closing parenthesis'],
            ['+', 'Quantifier does not follow a repeatable item'],
            ['[z-a]', 'Range out of order in character class'],
            ['(?<>)', 'Subpattern name expected'],
            ['(?<123>)', 'Subpattern name must start with a non-digit'],
            ['[[:invalid:]]', 'Unknown POSIX class name'],
            ['(?<=a+)b', 'Lookbehind assertion is not fixed length'],
            ['[\Q]', 'Missing terminating ] for character class'],
        );
    }

    /**
     * @test
     */
    public function nullByte()
    {
        // when
        $call = catching(fn() => new Pattern("\w\0"));
        // then
        if (since('8.2.0')) {
            $call
                ->assertExceptionNone();
        } else {
            $call
                ->assertException(SyntaxException::class)
                ->assertMessageStartsWith('Null byte in regex');
        }
    }

    /**
     * @test
     */
    public function invalidEscape()
    {
        catching(fn() => new Pattern('\i'))
            ->assertException(SyntaxException::class)
            ->assertMessageStartsWith('Unrecognized character follows \\');
    }

    /**
     * @test
     */
    public function invalidEscape_notOverriddenByModifiers()
    {
        catching(fn() => new Pattern('\i', Pattern::MULTILINE))
            ->assertException(SyntaxException::class)
            ->assertMessageStartsWith('Unrecognized character follows \\');
    }
}
