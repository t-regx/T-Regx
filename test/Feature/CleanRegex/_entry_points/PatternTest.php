<?php
namespace Test\Feature\TRegx\CleanRegex\_entry_points;

use PHPUnit\Framework\TestCase;
use Test\Utils\AssertsPattern;
use TRegx\CleanRegex\Exception\MalformedPcreTemplateException;
use TRegx\CleanRegex\Exception\MaskMalformedPatternException;
use TRegx\CleanRegex\Exception\PatternMalformedPatternException;
use TRegx\CleanRegex\Pattern;

/**
 * @coversNothing
 */
class PatternTest extends TestCase
{
    use AssertsPattern;

    /**
     * @test
     */
    public function shouldBuild_inject()
    {
        // given
        $pattern = Pattern::inject('You/&her, (are|is) @ (you|her)', [
            'real? (or are you not real?)'
        ]);

        // when
        $pattern = $pattern->delimited();

        // then
        $this->assertSame('#You/&her, (are|is) real\?\ \(or\ are\ you\ not\ real\?\) (you|her)#', $pattern);
    }

    /**
     * @test
     */
    public function shouldBuild_compose()
    {
        // given
        $pattern = Pattern::compose([
            pattern('^Fro'),
            Pattern::of('rod'),
            Pattern::pcre()->of('/do$/'),
        ]);

        // when
        $matches = $pattern->allMatch('Frodo');

        // then
        $this->assertTrue($matches);
    }

    /**
     * @test
     */
    public function shouldBuild_mask(): void
    {
        // given
        $pattern = Pattern::mask('%%:%e%w:%c', [
            '%%' => '%',
            '%e' => '\\/',
            '%w' => '\s*',
            '%c' => '.',
        ], 's');

        // when
        $delimited = $pattern->delimited();

        // then
        $this->assertSame('#%\:\\/\s*\:.#s', $delimited);
    }

    /**
     * @test
     */
    public function shouldBuild_mask_Delimiter(): void
    {
        // given
        $pattern = Pattern::mask('%', [
            '%%' => '/',
            '%e' => '#',
        ]);

        // when
        $delimited = $pattern->delimited();

        // then
        $this->assertSame('%\%%', $delimited);
    }

    /**
     * @test
     */
    public function shouldBuild_mask_Trailing(): void
    {
        // then
        $this->expectException(MaskMalformedPatternException::class);
        $this->expectExceptionMessage("Malformed pattern '\' assigned to keyword '%e'");

        // when
        Pattern::mask('%e', ['%e' => '\\']);
    }

    /**
     * @test
     */
    public function shouldBuild_mask_QuotedTrailing(): void
    {
        // then
        $this->expectException(MaskMalformedPatternException::class);
        $this->expectExceptionMessage("Malformed pattern '\' assigned to keyword '%e'");

        // when
        Pattern::mask('%e', ['%e' => '\\', '%f' => 'e']);
    }

    /**
     * @test
     */
    public function shouldBuild_template_literal_mask_literal_build(): void
    {
        // given
        $pattern = Pattern::template('^@ v@s. &@ or `s`', 'i')
            ->literal('&')
            ->mask('This-is: %3 pattern %4', [
                '%3' => 'x{3,}',
                '%4' => 'x{4,}',
            ])
            ->literal('&')
            ->build();

        // when
        $delimited = $pattern->delimited();

        // then
        $this->assertSame('/^& vThis\-is\:\ x{3,}\ pattern\ x{4,}s. && or `s`/i', $delimited);
    }

    /**
     * @test
     */
    public function shouldBuild_template_mask_literal_mask_build(): void
    {
        // given
        $pattern = Pattern::template('^@ v@s. @$ or `s`', 'i')
            ->mask('This-is: %3 pattern %4', [
                '%3' => 'x{3,}',
                '%4' => 'x{4,}',
            ])
            ->literal('@')
            ->mask('(%e:%%e)', [
                '%%' => '%',
                '%e' => 'e{2,3}'
            ])
            ->build();

        // when
        $delimited = $pattern->delimited();

        // then
        $this->assertSame('/^This\-is\:\ x{3,}\ pattern\ x{4,} v@s. \(e{2,3}\:%e\)$ or `s`/i', $delimited);
    }

    /**
     * @test
     */
    public function shouldBuild_template_mask_build(): void
    {
        // given
        $pattern = Pattern::template('^@ vs/$', 's')
            ->mask('This-is: %3', ['%3' => 'x{3,}'])
            ->build();

        // when
        $delimited = $pattern->delimited();

        // then
        $this->assertSame('#^This\-is\:\ x{3,} vs/$#s', $delimited);
    }

    /**
     * @test
     */
    public function shouldBuild_template_literal_build(): void
    {
        // given
        $pattern = Pattern::template('^@ vs/ $', 's')->literal('&')->build();

        // when
        $delimited = $pattern->delimited();

        // then
        $this->assertSame('#^& vs/ $#s', $delimited);
    }

    /**
     * @test
     */
    public function shouldThrowMalformedPatternException_forUndelimitedPcrePattern()
    {
        // then
        $this->expectException(PatternMalformedPatternException::class);
        $this->expectExceptionMessage("PCRE-compatible template is malformed, unclosed pattern '%'");

        // when
        Pattern::pcre()->inject("%Foo", [])->test('bar');
    }

    /**
     * @test
     */
    public function shouldGetAlterationPattern()
    {
        // when
        $pattern = Pattern::alteration(['foo', 'bar']);

        // then
        $this->assertSamePattern('/(?:foo|bar)/', $pattern);
    }

    /**
     * @test
     */
    public function shouldGetAlterationFlags()
    {
        // given
        $pattern = Pattern::alteration(['fo{2}', '\w', '\d'], 'i');

        // when
        $texts = $pattern->match('FO{2} \d fo{2} \w')->all();

        // then
        $this->assertSame(['FO{2}', '\d', 'fo{2}', '\w'], $texts);
        $this->assertSamePattern('/(?:fo\{2\}|\\\\w|\\\\d)/i', $pattern);
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
        Pattern::pcre()->template($pattern)->build();
    }

    public function malformedPregPatterns(): array
    {
        return [
            ['', 'PCRE-compatible template is malformed, pattern is empty'],
            ['&foo', "PCRE-compatible template is malformed, starting with an unexpected delimiter '&'"],
            ['#foo/', 'PCRE-compatible template is malformed, unclosed pattern'],
            ['/foo', 'PCRE-compatible template is malformed, unclosed pattern'],
            ['ooo', "PCRE-compatible template is malformed, alphanumeric delimiter 'o'"],
            ['4oo', 'PCRE-compatible template is malformed, alphanumeric delimiter'],
        ];
    }
}
