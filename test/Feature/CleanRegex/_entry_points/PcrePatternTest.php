<?php
namespace Test\Feature\CleanRegex\_entry_points;

use PHPUnit\Framework\TestCase;
use Test\Utils\Assertion\AssertsPattern;
use Test\Utils\TestCase\TestCaseExactMessage;
use TRegx\CleanRegex\Exception\MalformedPcreTemplateException;
use TRegx\CleanRegex\PcrePattern;
use TRegx\Exception\MalformedPatternException;

class PcrePatternTest extends TestCase
{
    use AssertsPattern, TestCaseExactMessage;

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
        PcrePattern::builder($pattern)->build();
    }

    public function malformedPregPatterns(): array
    {
        return [
            ['', 'PCRE-compatible template is malformed, pattern is empty'],
            ['&foo', "PCRE-compatible template is malformed, unclosed pattern '&'"],
            ['#foo/', "PCRE-compatible template is malformed, unclosed pattern '#'"],
            ['/foo', "PCRE-compatible template is malformed, unclosed pattern '/'"],
            ['ooo', "PCRE-compatible template is malformed, alphanumeric delimiter 'o'"],
            ['4oo', "PCRE-compatible template is malformed, alphanumeric delimiter '4'"],
        ];
    }

    /**
     * @test
     */
    public function shouldThrowMalformedPatternException_forUndelimitedPcrePattern()
    {
        // then
        $this->expectException(MalformedPatternException::class);
        $this->expectExceptionMessage("PCRE-compatible template is malformed, alphanumeric delimiter 'f'");
        // when
        PcrePattern::of('foo')->test('bar');
    }

    /**
     * @test
     */
    public function shouldThrowMalformedPatternException_forPatternWithNullByte()
    {
        // then
        $this->expectException(MalformedPatternException::class);
        $this->expectExceptionMessage('Pattern may not contain null-byte');
        // when
        PcrePattern::of("/\0/")->test('bar');
    }

    /**
     * @test
     */
    public function shouldThrowMalformedPatternException_forNullByteDelimiters()
    {
        // then
        $this->expectException(MalformedPatternException::class);
        $this->expectExceptionMessage('PCRE-compatible template is malformed, null-byte delimiter');
        // when
        PcrePattern::of("\0pattern\0")->test('bar');
    }
}
