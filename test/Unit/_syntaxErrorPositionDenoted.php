<?php
namespace Test\Unit;

use PHPUnit\Framework\TestCase;
use Regex\Pattern;
use Regex\SyntaxException;
use function Test\Fixture\Functions\catching;
use function Test\Fixture\Functions\since;

class _syntaxErrorPositionDenoted extends TestCase
{
    public function test()
    {
        catching(fn() => new Pattern('[a-zA-Z]+(?n)+', 'u'))
            ->assertException(SyntaxException::class)
            ->assertMessage("Quantifier does not follow a repeatable item, near position 13.

'[a-zA-Z]+(?n)+'
              ^");
    }

    /**
     * @test
     */
    public function trailingBackslash()
    {
        catching(fn() => new Pattern('€[ą-zA-Z]+\\', 'u'))
            ->assertException(SyntaxException::class)
            ->assertMessage("Trailing backslash in regular expression, near position 13.

'€[ą-zA-Z]+\'
           ^");
    }

    /**
     * @test
     */
    public function nullByte()
    {
        // when
        $call = catching(fn() => new Pattern("€[ą-ę]\0", 'u'));
        // then
        if (since('8.2.0')) {
            $call->assertExceptionNone();
        } else {
            $call
                ->assertException(SyntaxException::class)
                ->assertMessage("Null byte in regex, near position 10.

'€[ą-ę] '
       ^
(contains non-printable characters)");
        }
    }

    /**
     * @test
     */
    public function unicode()
    {
        catching(fn() => new Pattern('€[ą-ęA-Z]+(?n)+', 'u'))
            ->assertException(SyntaxException::class)
            ->assertMessage("Quantifier does not follow a repeatable item, near position 18.

'€[ą-ęA-Z]+(?n)+'
               ^");
    }

    /**
     * @test
     */
    public function leadingNewline()
    {
        catching(fn() => new Pattern("\n[z-a]"))
            ->assertException(SyntaxException::class)
            ->assertMessage("Range out of order in character class, near position 4.

'
[z-a]'
");
    }

    /**
     * @test
     */
    public function multiline()
    {
        catching(fn() => new Pattern("[a-z]\n\w+ (?<>)"))
            ->assertException(SyntaxException::class)
            ->assertMessage("Subpattern name expected, near position 13.

'[a-z]
\w+ (?<>)'
");
    }

    /**
     * @test
     */
    public function nonPrintable()
    {
        catching(fn() => new Pattern("(group))\t", 'u'))
            ->assertException(SyntaxException::class)
            ->assertMessage("Unmatched closing parenthesis, near position 7.

'(group)) '
        ^
(contains non-printable characters)");
    }
}
