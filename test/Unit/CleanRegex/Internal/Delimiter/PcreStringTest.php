<?php
namespace Test\Unit\TRegx\CleanRegex\Internal\Delimiter;

use PHPUnit\Framework\TestCase;
use Test\Utils\Impl\ConstantPredicate;
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
        $string = new PcreString('/welcome/', new ConstantPredicate(true));

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
        $string = new PcreString('//', new ConstantPredicate(true));

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
        $string = new PcreString('/welcome/bar', new ConstantPredicate(true));

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
        $string = new PcreString('/foo/bar/cat/x', new ConstantPredicate(true));

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
        $string = new PcreString('#foo/bar#cat#x', new ConstantPredicate(true));

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
        new PcreString($pattern, new ConstantPredicate(true));
    }

    public function malformedPregPatterns(): array
    {
        return [
            ['', 'PCRE-compatible template is malformed, pattern is empty'],
            ['&foo', "PCRE-compatible template is malformed, unclosed pattern '&'"],
            ['#foo/', 'PCRE-compatible template is malformed, unclosed pattern'],
            ['/foo', 'PCRE-compatible template is malformed, unclosed pattern'],
        ];
    }

    /**
     * @test
     * @dataProvider malformedPregPatterns2
     * @param string $pattern
     * @param string $expectedMessage
     */
    public function shouldThrowForAlphanumericFirstCharacter2(string $pattern, string $expectedMessage)
    {
        // then
        $this->expectException(MalformedPcreTemplateException::class);
        $this->expectExceptionMessage($expectedMessage);

        // given
        new PcreString($pattern, new ConstantPredicate(false));
    }

    public function malformedPregPatterns2(): array
    {
        return [
            ['&foo', "PCRE-compatible template is malformed, starting with an unexpected delimiter '&'"],
            ['#foo/', "PCRE-compatible template is malformed, starting with an unexpected delimiter '#'"],
            ['/foo', "PCRE-compatible template is malformed, starting with an unexpected delimiter '/'"],
        ];
    }
}
