<?php
namespace Test\Unit\TRegx\CleanRegex\Internal\Delimiter;

use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Exception\MalformedPcreTemplateException;
use TRegx\CleanRegex\Internal\Delimiter\PcreString;

/**
 * @covers \TRegx\CleanRegex\Internal\Delimiter\PcreString
 */
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
        $delimiter = $string->delimiter();
        $pattern = $string->pattern();
        $flags = $string->flags();

        // then
        $this->assertSame('/', $delimiter);
        $this->assertSame('welcome', $pattern);
        $this->assertSame('', $flags);
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
     * @dataProvider unclosedPatterns
     * @param string $pattern
     * @param string $expectedMessage
     */
    public function shouldThrowForUnclosedPatterns(string $pattern, string $expectedMessage)
    {
        // then
        $this->expectException(MalformedPcreTemplateException::class);
        $this->expectExceptionMessage($expectedMessage);

        // given
        new PcreString($pattern);
    }

    public function unclosedPatterns(): array
    {
        return [
            ['', 'PCRE-compatible template is malformed, pattern is empty'],
            ['&foo', "PCRE-compatible template is malformed, unclosed pattern '&'"],
            ['#foo/', 'PCRE-compatible template is malformed, unclosed pattern'],
            ['/foo', 'PCRE-compatible template is malformed, unclosed pattern'],
            ['/', 'PCRE-compatible template is malformed, unclosed pattern'],
        ];
    }

    /**
     * @test
     */
    public function shouldThrowForUnacceptedPredicate()
    {
        // given
        $invalid = chr(128); // non-ascii code points are never valid delimiters

        // then
        $this->expectException(MalformedPcreTemplateException::class);
        $this->expectExceptionMessage("PCRE-compatible template is malformed, starting with an unexpected delimiter '$invalid'");

        // given
        new PcreString($invalid . 'foo');
    }

    /**
     * @test
     * @link https://github.com/php/php-src/blob/5355cf33948299b2c1ee95b7140a464beecdfb12/ext/pcre/php_pcre.c#L642
     */
    public function shouldAcceptLeadingSpace()
    {
        // given
        $string = new PcreString("\t \n\v\f\r/foo/");

        // when
        $pattern = $string->pattern();

        // then
        $this->assertSame('foo', $pattern);
    }

    /**
     * @test
     * @link https://github.com/php/php-src/blob/5355cf33948299b2c1ee95b7140a464beecdfb12/ext/pcre/php_pcre.c#L703
     */
    public function shouldNotIgnoreNullByte()
    {
        // then
        $this->expectException(MalformedPcreTemplateException::class);

        // given
        new PcreString("\0/foo/");
    }

    /**
     * @test
     * @link https://github.com/php/php-src/blob/5355cf33948299b2c1ee95b7140a464beecdfb12/ext/pcre/php_pcre.c#L751
     * @note For some reason, PHP PCRE integration ignores line feeds and spaces after delimiter
     */
    public function shouldIgnoreNewLinesInModifiers()
    {
        // given
        $string = new PcreString(" /foo/i\n m\r \0\t\f\v");

        // when
        $flags = $string->flags();

        // then
        $this->assertSame("im\0\t\f\v", $flags);
    }

    /**
     * @test
     */
    public function shouldPreserveNewLinesInPattern()
    {
        // given
        $string = new PcreString(" /foo\n\r /i ");

        // when
        $pattern = $string->pattern();

        // then
        $this->assertSame("foo\n\r ", $pattern);
    }
}
