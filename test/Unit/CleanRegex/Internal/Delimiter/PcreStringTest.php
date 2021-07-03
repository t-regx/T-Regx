<?php
namespace Test\Unit\TRegx\CleanRegex\Internal\Delimiter;

use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Exception\MalformedPcreTemplateException;
use TRegx\CleanRegex\Internal\Delimiter\PcreString;

class PcreStringTest extends TestCase
{
    /**
     * @test
     */
    public function shouldGetPattern()
    {
        // given
        $string = new PcreString('/welcome/');

        // when
        $pattern = $string->pattern();
        $flags = $string->flags();
        $delimiter = $string->delimiter();

        // then
        $this->assertSame('welcome', $pattern);
        $this->assertSame('', $flags);
        $this->assertSame('/', $delimiter);
    }

    /**
     * @test
     */
    public function shouldGetEmptyPattern()
    {
        // given
        $string = new PcreString('//');

        // when
        $pattern = $string->pattern();
        $delimiter = $string->delimiter();

        // then
        $this->assertSame('', $pattern);
        $this->assertSame('/', $delimiter);
    }

    /**
     * @test
     */
    public function shouldGetPatternWithFlags()
    {
        // given
        $string = new PcreString('/welcome/bar');

        // when
        $pattern = $string->pattern();
        $flags = $string->flags();
        $delimiter = $string->delimiter();

        // then
        $this->assertSame('welcome', $pattern);
        $this->assertSame('bar', $flags);
        $this->assertSame('/', $delimiter);
    }

    /**
     * @test
     */
    public function shouldIgnoreInPatternDelimiter()
    {
        // given
        $string = new PcreString('/foo/bar/cat/x');

        // when
        $pattern = $string->pattern();
        $flags = $string->flags();
        $delimiter = $string->delimiter();

        // then
        $this->assertSame('foo/bar/cat', $pattern);
        $this->assertSame('x', $flags);
        $this->assertSame('/', $delimiter);
    }

    /**
     * @test
     */
    public function shouldAcceptPcrePatternWithHashDelimiter()
    {
        // given
        $string = new PcreString('#foo/bar#cat#x');

        // when
        $pattern = $string->pattern();
        $flags = $string->flags();
        $delimiter = $string->delimiter();

        // then
        $this->assertSame('foo/bar#cat', $pattern);
        $this->assertSame('x', $flags);
        $this->assertSame('#', $delimiter);
    }

    /**
     * @test
     * @dataProvider malformedPregPatterns
     * @param string $pattern
     * @param string $expectedMessage
     */
    public function shouldThrowForAlphanumericFirstCharacter(string $pattern, string $expectedMessage)
    {
        // then
        $this->expectException(MalformedPcreTemplateException::class);
        $this->expectExceptionMessage($expectedMessage);

        // given
        new PcreString($pattern);
    }

    public function malformedPregPatterns(): array
    {
        return [
            ['', 'PCRE-compatible template is malformed, pattern is empty'],
            ['&foo', 'PCRE-compatible template is malformed, starting with an unexpected delimiter'],
            ['#foo/', 'PCRE-compatible template is malformed, unclosed pattern'],
            ['/foo', 'PCRE-compatible template is malformed, unclosed pattern'],
            ['ooo', 'PCRE-compatible template is malformed, alphanumeric delimiter'],
            ['OOO', 'PCRE-compatible template is malformed, alphanumeric delimiter'],
            ['4oo', 'PCRE-compatible template is malformed, alphanumeric delimiter'],
        ];
    }
}
