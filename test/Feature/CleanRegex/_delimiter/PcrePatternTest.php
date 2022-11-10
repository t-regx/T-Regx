<?php
namespace Test\Feature\CleanRegex\_delimiter;

use PHPUnit\Framework\TestCase;
use Test\Utils\Assertion\AssertsPattern;
use TRegx\CleanRegex\Exception\MalformedPcreTemplateException;
use TRegx\CleanRegex\PcrePattern;

/**
 * @covers \TRegx\CleanRegex\Internal\Delimiter\PcreDelimiter
 * @covers \TRegx\CleanRegex\Internal\Delimiter\PcreString
 * @covers \TRegx\CleanRegex\Internal\Prepared\Orthography\PcreSpelling
 * @covers \TRegx\CleanRegex\PcrePattern
 */
class PcrePatternTest extends TestCase
{
    use AssertsPattern;

    /**
     * @test
     * @dataProvider pcreStrings
     */
    public function shouldGetPatternInject(string $pattern, string $subject)
    {
        // given
        $pattern = PcrePattern::inject($pattern, []);
        // then
        $this->assertPatternIs($pattern, $pattern);
        $this->assertConsumesFirst($subject, $pattern);
    }

    /**
     * @test
     * @dataProvider pcreStrings
     */
    public function shouldGetPatternInjectBuilder(string $pattern, string $subject)
    {
        // given
        $pattern = PcrePattern::builder($pattern)->build();
        // then
        $this->assertPatternIs($pattern, $pattern);
        $this->assertConsumesFirst($subject, $pattern);
    }

    public function pcreStrings(): array
    {
        return [
            ['//', ''],
            ['/welcome/', 'welcome'],
            ['/welcome/i', 'WELCOME'],
        ];
    }

    /**
     * @test
     */
    public function shouldIgnoreInPatternDelimiter()
    {
        // given
        $pattern = PcrePattern::inject("/foo/(?x)#@\n/i", []);
        // then
        $this->assertPatternIs("/foo/(?x)#@\n/i", $pattern);
    }

    /**
     * @test
     */
    public function shouldIgnoreInPatternDelimiterPercent()
    {
        // given
        $pattern = PcrePattern::inject("%foo%(?x)#@\n%i", []);
        // then
        $this->assertPatternIs("%foo%(?x)#@\n%i", $pattern);
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
        PcrePattern::inject($pattern, []);
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
        PcrePattern::inject($invalid . 'foo', []);
    }

    /**
     * @test
     * @link https://github.com/php/php-src/blob/5355cf33948299b2c1ee95b7140a464beecdfb12/ext/pcre/php_pcre.c#L642
     */
    public function shouldAcceptLeadingSpace()
    {
        // given
        $pattern = PcrePattern::inject("\t \n\v\f\r/foo/", []);
        // then
        $this->assertPatternIs('/foo/', $pattern);
        $this->assertConsumesFirst('foo', $pattern);
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
        PcrePattern::inject("\0/foo/", []);
    }

    /**
     * @test
     * @link https://github.com/php/php-src/blob/5355cf33948299b2c1ee95b7140a464beecdfb12/ext/pcre/php_pcre.c#L751
     * @note For some reason, PHP PCRE integration ignores line feeds and spaces after delimiter
     */
    public function shouldIgnoreNewLinesInModifiers()
    {
        // given
        $pattern = PcrePattern::inject(" /foo/i\n m\r \0\t\f\v", []);
        // then
        $this->assertPatternIs("/foo/im\0\t\f\v", $pattern);
    }

    /**
     * @test
     */
    public function shouldPreserveNewLinesInPattern()
    {
        // given
        $pattern = PcrePattern::inject(" /foo\n\r /i ", []);
        // then
        $this->assertPatternIs("/foo\n\r /i", $pattern);
        $this->assertConsumesFirst("foo\n\r ", $pattern);
    }
}
