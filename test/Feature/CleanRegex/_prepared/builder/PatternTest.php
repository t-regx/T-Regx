<?php
namespace Test\Feature\CleanRegex\_prepared\builder;

use PHPUnit\Framework\TestCase;
use Test\Utils\Assertion\AssertsPattern;
use TRegx\CleanRegex\Exception\ExplicitDelimiterRequiredException;
use TRegx\CleanRegex\Internal\Prepared\Figure\PlaceholderFigureException;
use TRegx\CleanRegex\Pattern;

class PatternTest extends TestCase
{
    use AssertsPattern;

    /**
     * @test
     * @dataProvider templatesWithoutPlaceholders
     * @param string $pattern
     * @param string $expected
     */
    public function shouldNotMistakeLiteralForPlaceholder(string $pattern, string $expected)
    {
        // when
        $pattern = Pattern::builder($pattern)->build();

        // then
        $this->assertPatternIs($expected, $pattern);
    }

    public function templatesWithoutPlaceholders(): array
    {
        return [
            "placeholder '@' in []"      => ['You/her [@] her?', '#You/her [@] her?#'],
            "placeholder '@' in \Q\E"    => ['You/her \Q@\E her?', '#You/her \Q@\E her?#'],
            "placeholder '@' escaped"    => ['You/her \@ her?', '#You/her \@ her?#'],
            "placeholder '@' in comment" => ["You/her (?x:#@\n) her?", "%You/her (?x:#@\n) her?%"],
            "placeholder '@' in control" => ["You/her \c@ her?", "#You/her \c@ her?#"],
        ];
    }

    /**
     * @test
     */
    public function shouldNotMistakePlaceholderInCommentInExtendedMode()
    {
        // when
        $pattern = Pattern::builder("You/her #@\n her?", 'x')->build();

        // then
        $this->assertPatternIs("%You/her #@\n her?%x", $pattern);
    }

    /**
     * @test
     */
    public function shouldUsePlaceholderInCommentInExtendedMode_butExtendedModeIsSwitchedOff()
    {
        // when
        $pattern = Pattern::builder("You/her (?-x:#@\n) her?", 'x')->literal('X')->build();

        // then
        $this->assertPatternIs("%You/her (?-x:#X\n) her?%x", $pattern);
    }

    /**
     * @test
     */
    public function shouldThrowForSuperfluousTemplateFigure()
    {
        // given
        $builder = Pattern::builder('You/her, (are|is) @ (you|her)')
            ->literal('foo')
            ->literal('bar')
            ->literal('cat');

        // then
        $this->expectException(PlaceholderFigureException::class);
        $this->expectExceptionMessage("Found a superfluous figure: string ('bar'). Used 1 placeholders, but 3 figures supplied.");

        // when
        $builder->build();
    }

    /**
     * @test
     */
    public function shouldThrowForSuperfluousTemplateMask()
    {
        // given
        $builder = Pattern::builder('Foo')->mask('foo', ['foo', 'bar']);

        // then
        $this->expectException(PlaceholderFigureException::class);
        $this->expectExceptionMessage("Found a superfluous figure: mask (2). Used 0 placeholders, but 1 figures supplied.");

        // when
        $builder->build();
    }

    /**
     * @test
     */
    public function shouldThrowForSuperfluousBuilderAlteration()
    {
        // given
        $builder = Pattern::builder('Foo')->alteration(['foo', 'bar']);

        // then
        $this->expectException(PlaceholderFigureException::class);
        $this->expectExceptionMessage("Found a superfluous figure: array (2). Used 0 placeholders, but 1 figures supplied.");

        // when
        $builder->build();
    }

    /**
     * @test
     */
    public function shouldThrowForSuperfluousTemplatePattern()
    {
        // given
        $builder = Pattern::builder('Foo')->pattern('bar');

        // then
        $this->expectException(PlaceholderFigureException::class);
        $this->expectExceptionMessage('Found a superfluous figure: pattern (bar). Used 0 placeholders, but 1 figures supplied.');

        // when
        $builder->build();
    }

    /**
     * @test
     */
    public function shouldThrowForRequiredExplicitDelimiter()
    {
        // given
        $builder = Pattern::builder("s~i/e#++m%a!\@*`_-;=,\1");

        // then
        $this->expectException(ExplicitDelimiterRequiredException::class);
        $this->expectExceptionMessage("Failed to select a distinct delimiter to enable template in its entirety");

        // when
        $builder->build();
    }

    /**
     * @test
     */
    public function shouldParseUnicode()
    {
        // when
        $pattern = Pattern::builder('ę')->build();

        // then
        $this->assertConsumesFirst('ę', $pattern);
    }

    /**
     * @test
     */
    public function shouldAcceptTrailingSlashInQuote()
    {
        // when
        $pattern = Pattern::builder('\Q\\\E!\Q\\')->build();

        // then
        $this->assertConsumesFirst('\\!\\', $pattern);
    }

    /**
     * @test
     */
    public function shouldTemplateAcceptTrailingControlBackslash()
    {
        // when
        $pattern = Pattern::builder('\c\\')->build();

        // then
        $this->assertConsumesFirst(\chr(28), $pattern);
    }

    /**
     * @test
     */
    public function shouldInjectAcceptTrailingCommentBackslash()
    {
        // given
        $pattern = Pattern::inject('#\\', []);

        // when
        $valid = $pattern->valid();

        // then
        $this->assertFalse($valid);
    }

    /**
     * @test
     */
    public function shouldTemplatePatternAcceptTrailingControlBackslash_emptyPattern()
    {
        // when
        $pattern = Pattern::builder('foo:@@')->pattern('\c\\')->pattern('')->build();

        // then
        $this->assertConsumesFirst('foo:' . \chr(28), $pattern);
        $this->assertPatternIs('/foo:\c\{1}/', $pattern);
    }

    /**
     * @test
     */
    public function shouldTemplatePatternAcceptTrailingControlBackslash_nextToLastPattern()
    {
        // when
        $pattern = Pattern::builder('foo:@@')->pattern('\c\\')->pattern('>')->build();

        // then
        $this->assertConsumesFirst("foo:\x1C>", $pattern);
        $this->assertPatternIs('/foo:\c\>/', $pattern);
    }

    /**
     * @test
     */
    public function shouldTemplatePatternAcceptTrailingControlBackslash_nextToLastLiteral()
    {
        // when
        $pattern = Pattern::builder('foo:@@')->pattern('\c\\')->literal('|')->build();

        // then
        $this->assertConsumesFirst("foo:\x1C|", $pattern);
        $this->assertPatternIs('/foo:\c\\\|/', $pattern);
    }
}
