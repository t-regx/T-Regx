<?php
namespace Test\Feature\CleanRegex\_delimiter;

use PHPUnit\Framework\TestCase;
use Test\Utils\Assertion\AssertsPattern;
use Test\Utils\TestCase\TestCasePasses;
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
    use TestCasePasses, AssertsPattern;

    /**
     * @test
     * @dataProvider legalDelimiters
     */
    public function shouldAcceptLegalDelimiterInject(string $delimiter): void
    {
        // when
        PcrePattern::inject($delimiter . $delimiter, []);
        // then
        $this->pass();
    }

    /**
     * @test
     * @dataProvider legalDelimiters
     */
    public function shouldAcceptLegalDelimiterTemplate(string $delimiter): void
    {
        // when
        PcrePattern::template($delimiter . '@' . $delimiter)->literal('value');
        // then
        $this->pass();
    }

    /**
     * @test
     * @dataProvider legalDelimiters
     */
    public function shouldAcceptLegalDelimiterBuilder(string $delimiter): void
    {
        // when
        PcrePattern::builder($delimiter . $delimiter)->build();
        // then
        $this->pass();
    }

    /**
     * @test
     * @dataProvider illegalDelimiters
     */
    public function shouldThrowForIllegalDelimiterInject(string $delimiter): void
    {
        // then
        $this->expectException(MalformedPcreTemplateException::class);
        $this->expectExceptionMessage($this->expectedMalformedPatternMessage($delimiter));
        // when
        PcrePattern::inject($delimiter, []);
    }

    /**
     * @test
     * @dataProvider illegalDelimiters
     */
    public function shouldThrowForIllegalDelimiterTemplate(string $delimiter): void
    {
        // then
        $this->expectException(MalformedPcreTemplateException::class);
        $this->expectExceptionMessage($this->expectedMalformedPatternMessage($delimiter));
        // when
        PcrePattern::template($delimiter)->literal('value');
    }

    /**
     * @test
     * @dataProvider illegalDelimiters
     */
    public function shouldThrowForIllegalDelimiterBuilder(string $delimiter): void
    {
        // then
        $this->expectException(MalformedPcreTemplateException::class);
        $this->expectExceptionMessage($this->expectedMalformedPatternMessage($delimiter));
        // when
        PcrePattern::builder($delimiter)->build();
    }

    public function expectedMalformedPatternMessage(string $delimiter): string
    {
        if (\in_array(\ord($delimiter), [9, 10, 11, 12, 13, 32])) {
            return 'PCRE-compatible template is malformed, pattern is empty';
        }
        if (\ctype_alnum($delimiter)) {
            return "PCRE-compatible template is malformed, alphanumeric delimiter '$delimiter'";
        }
        return "PCRE-compatible template is malformed, starting with an unexpected delimiter '$delimiter'";
    }

    public function legalDelimiters(): \Generator
    {
        foreach (\range(0, 255) as $byte) {
            if ($this->isLegalDelimiter($byte)) {
                $character = \chr($byte);
                yield "$character (#$byte)" => [\chr($byte)];
            }
        }
    }

    public function illegalDelimiters(): \Generator
    {
        foreach (\range(0, 255) as $byte) {
            if (!$this->isLegalDelimiter($byte)) {
                $character = \chr($byte);
                yield "'$character' (#$byte)" => [\chr($byte)];
            }
        }
    }

    private function isLegalDelimiter(int $delimiterByte): bool
    {
        return \in_array($delimiterByte, $this->legalDelimiterBytes());
    }

    private function legalDelimiterBytes(): array
    {
        return [
            1, 2, 3, 4, 5, 6, 7, 8,
            14, 15, 16, 17, 18, 19,
            20, 21, 22, 23, 24, 25, 26, 27, 28, 29,
            30, 31, 33, 34, 35, 36, 37, 38, 39,
            41, 42, 43, 44, 45, 46, 47,
            58, 59,
            61, 62, 63, 64,
            93, 94, 95, 96,
            124, 125, 126, 127
        ];
    }

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
        $pattern = PcrePattern::inject(" /foo/i\n m\r \t\f\v", []);
        // then
        $this->assertPatternIs("/foo/im\t\f\v", $pattern);
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
