<?php
namespace Test\Feature\TRegx\CleanRegex\_entry_points;

use PHPUnit\Framework\TestCase;
use Test\Utils\AssertsPattern;
use Test\Utils\ExactExceptionMessage;
use TRegx\CleanRegex\Exception\ExplicitDelimiterRequiredException;
use TRegx\CleanRegex\Exception\MalformedPcreTemplateException;
use TRegx\CleanRegex\Exception\MaskMalformedPatternException;
use TRegx\CleanRegex\Exception\PatternMalformedPatternException;
use TRegx\CleanRegex\Pattern;

class PatternTest extends TestCase
{
    use AssertsPattern, ExactExceptionMessage;

    /**
     * @test
     */
    public function shouldBuild_inject()
    {
        // when
        $figure = 'real? (or are you not real?)';
        $pattern = Pattern::inject('You/her, (are|is) @ (you|her)', [$figure]);

        // then
        $this->assertSamePattern('#You/her, (are|is) real\?\ \(or\ are\ you\ not\ real\?\) (you|her)#', $pattern);
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
        $matches = $pattern->testAll('Frodo');

        // then
        $this->assertTrue($matches);
    }

    /**
     * @test
     */
    public function shouldMask()
    {
        // when
        $pattern = Pattern::mask('(Super):{%s.%d.%%}', [
            '%s' => '\s+',
            '%d' => '\d+',
            '%%' => '%/'
        ], 'i');

        // then
        $this->assertConsumesFirst('(super):{  .12.%/}', $pattern);
        $this->assertSamePattern('#\(Super\)\:\{\s+\.\d+\.%/\}#i', $pattern);
    }

    /**
     * @test
     */
    public function shouldBuild_mask_Delimiter(): void
    {
        // given
        $pattern = Pattern::mask('%', ['%%' => '/', '%e' => '#']);

        // then
        $this->assertSamePattern('%\%%', $pattern);
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
    public function shouldThrowForInvalidPatternInMask(): void
    {
        // then
        $this->expectException(MaskMalformedPatternException::class);
        $this->expectExceptionMessage("Malformed pattern '*' assigned to keyword '%e'");

        // when
        Pattern::mask('%e', ['%e' => '*']);
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
    public function shouldBuild_builder_literal_mask_literal_build(): void
    {
        // when
        $pattern = Pattern::builder('^@ v@s. &@ or `s` %', 'i')
            ->literal('&')
            ->mask('This-is: %3 pattern %4', [
                '%3' => 'x{3,}#',
                '%4' => 'x{4,}/',
            ])
            ->literal('~')
            ->build();

        // then
        $this->assertSamePattern('~^& vThis\-is\:\ x{3,}#\ pattern\ x{4,}/s. &\~ or `s` %~i', $pattern);
    }

    /**
     * @test
     */
    public function shouldBuild_builder_mask_literal_mask_build(): void
    {
        // when
        $pattern = Pattern::builder('^@ v@s. @$ or `s`', 'i')
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

        // then
        $this->assertSamePattern('/^This\-is\:\ x{3,}\ pattern\ x{4,} v@s. \(e{2,3}\:%e\)$ or `s`/i', $pattern);
    }

    /**
     * @test
     */
    public function shouldBuild_template_mask_flag(): void
    {
        // when
        $pattern = Pattern::template('^@ vs/$', 's')->mask('This-is: %3', ['%3' => 'x{3,}']);

        // then
        $this->assertSamePattern('#^This\-is\:\ x{3,} vs/$#s', $pattern);
    }

    /**
     * @test
     */
    public function shouldBuild_template_mask(): void
    {
        // when
        $pattern = Pattern::template('/@')->mask('%%:%h%f%s', [
            '%%' => '%',
            '%h' => '#',
            '%f' => '/',
            '%s' => '\s*',
        ]);

        // then
        $this->assertConsumesFirst('/%:#/   ', $pattern);
        $this->assertSamePattern('~/%\:#/\s*~', $pattern);
    }

    /**
     * @test
     */
    public function shouldBuild_template_literal(): void
    {
        // when
        $pattern = Pattern::template('^@ vs/ $', 's')->literal('&');

        // then
        $this->assertSamePattern('#^& vs/ $#s', $pattern);
    }

    /**
     * @test
     */
    public function shouldBuild_template_alteration()
    {
        // when
        $pattern = Pattern::template('You/her, @ (her)', 's')->alteration(['{hi}', '50#']);

        // then
        $this->assertConsumesFirst('You/her, {hi} her', $pattern);
        $this->assertConsumesFirst('You/her, 50# her', $pattern);
        $this->assertSamePattern('#You/her, (?:\{hi\}|50\#) (her)#s', $pattern);
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

        // then
        $this->assertConsumesAll('FO{2} \d fo{2} \w', ['FO{2}', '\d', 'fo{2}', '\w'], $pattern);
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
        Pattern::pcre()->builder($pattern)->build();
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
    public function shouldPcreQuoteNonStandardDelimiter()
    {
        // given
        $delimiter = \chr(58);

        // when
        $pattern = Pattern::pcre()->inject($delimiter . 'foo(@)' . $delimiter, [$delimiter]);

        // then
        $this->assertConsumesFirst("foo$delimiter", $pattern);
        $this->assertSamePattern("\x3Afoo(\\\x3A)\x3A", $pattern);
    }

    /**
     * @test
     */
    public function shouldThrowForRequiredExplicitDelimiter()
    {
        // then
        $this->expectException(ExplicitDelimiterRequiredException::class);
        $this->expectExceptionMessage("Failed to select a distinct delimiter to enable pattern: s~i/e#++m%a!@*`_-;=,\1");

        // when
        Pattern::of("s~i/e#++m%a!@*`_-;=,\1");
    }

    /**
     * @test
     */
    public function shouldThrowForRequiredExplicitDelimiterMask()
    {
        // then
        $this->expectException(ExplicitDelimiterRequiredException::class);
        $this->expectExceptionMessage("Failed to select a distinct delimiter to enable mask keywords in their entirety: s~i/e#, m+m+%a!, @*`_-;=,\1");

        // when
        Pattern::mask('@', [
            'foo' => 's~i/e#',
            'bar' => 'm+m+%a!',
            'cat' => "@*`_-;=,\1",
        ]);
    }

    /**
     * @test
     */
    public function shouldThrowForRequiredExplicitDelimiterTemplateMask()
    {
        // then
        $this->expectException(ExplicitDelimiterRequiredException::class);
        $this->expectExceptionMessage("Failed to select a distinct delimiter to enable template in its entirety");

        // when
        Pattern::template('@')->mask('foo', [
            'foo' => 's~i/e#',
            'bar' => 'm+m+%a!',
            'cat' => "@*`_-;=,\1",
        ]);
    }

    /**
     * @test
     */
    public function shouldMatchDoubleGroups()
    {
        // when
        $pattern = Pattern::inject('()(?:)', []);

        // then
        $this->assertConsumesFirst('', $pattern);
    }

    /**
     * @test
     */
    public function shouldAcceptControlCharacter()
    {
        // when
        $pattern = Pattern::of('\c?');

        // then
        $this->assertConsumesFirst(\chr(127), $pattern);
    }

    /**
     * @test
     */
    public function shouldAcceptTrailingSlashInControlCharacter()
    {
        // when
        $pattern = Pattern::of('\c\\');

        // then
        $this->assertConsumesFirst(\chr(28), $pattern);
    }

    /**
     * @test
     */
    public function shouldAcceptTrailingSlashInQuote()
    {
        // when
        $pattern = Pattern::of('\Q\\');

        // then
        $this->assertConsumesFirst('\\', $pattern);
    }
}
