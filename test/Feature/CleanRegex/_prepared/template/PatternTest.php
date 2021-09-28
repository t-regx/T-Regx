<?php
namespace Test\Feature\TRegx\CleanRegex\_prepared\template;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Test\Utils\AssertsPattern;
use TRegx\CleanRegex\Exception\ExplicitDelimiterRequiredException;
use TRegx\CleanRegex\Exception\MaskMalformedPatternException;
use TRegx\CleanRegex\Internal\Prepared\Figure\PlaceholderFigureException;
use TRegx\CleanRegex\Pattern;

/**
 * @coversNothing
 */
class PatternTest extends TestCase
{
    use AssertsPattern;

    /**
     * @test
     * @dataProvider templatesWithPlaceholder
     * @param string $pattern
     * @param string $expected
     */
    public function shouldUsePlaceholder(string $pattern, string $expected)
    {
        // when
        $pattern = Pattern::template($pattern)->literal('X');

        // then
        $this->assertSamePattern($expected, $pattern);
    }

    public function templatesWithPlaceholder(): array
    {
        return [
            'standard'                               => ['You/her @ her?', '#You/her X her?#'],
            'comment (but no "x" flag)'              => ["You/her #@\n her?", "%You/her #X\n her?%"],
            'comment ("x" flag, but also "-x" flag)' => ["You/her (?x:(?-x:#@\n)) her?", "%You/her (?x:(?-x:#X\n)) her?%"],
        ];
    }

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
        $this->assertSamePattern($expected, $pattern);
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
        $this->assertSamePattern("%You/her #@\n her?%x", $pattern);
    }

    /**
     * @test
     */
    public function shouldUsePlaceholderInCommentInExtendedMode_butExtendedModeIsSwitchedOff()
    {
        // when
        $pattern = Pattern::builder("You/her (?-x:#@\n) her?", 'x')->literal('X')->build();

        // then
        $this->assertSamePattern("%You/her (?-x:#X\n) her?%x", $pattern);
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
    public function shouldThrowForSuperfluousTemplateAlteration()
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
    public function shouldBuildTemplateWithPattern()
    {
        // when
        $pattern = Pattern::template('foo:@')->pattern('#https?/www%');

        // then
        $this->assertSamePattern('~foo:#https?/www%~', $pattern);
        $this->assertConsumesFirst('foo:#http/www%', $pattern);
    }

    /**
     * @test
     */
    public function shouldMatchDelimiterPattern()
    {
        // when
        $pattern = Pattern::template('@')->pattern('/');

        // then
        $this->assertSamePattern('#/#', $pattern);
        $this->assertConsumesFirst('/', $pattern);
    }

    /**
     * @test
     */
    public function shouldThrowForEmptyKeyword()
    {
        // then
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Keyword cannot be empty, must consist of at least one character');

        // when
        Pattern::template('@')->mask('foo', ['' => 'Bar']);
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
    public function shouldTemplatePatternAcceptTrailingControlBackslash()
    {
        // when
        $pattern = Pattern::template('/foo:@')->pattern('\c\\');

        // then
        $this->assertConsumesFirst('/foo:' . \chr(28), $pattern);
        $this->assertSamePattern('#/foo:\c\{1}#', $pattern);
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
        $this->assertSamePattern('/foo:\c\{1}/', $pattern);
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
        $this->assertSamePattern('/foo:\c\>/', $pattern);
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
        $this->assertSamePattern('/foo:\c\\\|/', $pattern);
    }

    /**
     * @test
     */
    public function shouldMaskAcceptTrailingSlashInControlCharacter()
    {
        // when
        $pattern = Pattern::template('@')->mask('!s', ['!s' => '\c\\']);

        // then
        $this->assertConsumesFirst(\chr(28), $pattern);
    }

    /**
     * @test
     */
    public function shouldMaskAcceptTrailingSlashInQuote()
    {
        // when
        $pattern = Pattern::template('@')->mask('!s', ['!s' => '\Q\\']);

        // then
        $this->assertConsumesFirst('\\', $pattern);
    }

    /**
     * @test
     */
    public function shouldTemplateMaskThrowForRequiredExplicitDelimiterSingleKeyword()
    {
        // then
        $this->expectException(ExplicitDelimiterRequiredException::class);
        $this->expectExceptionMessage("Failed to select a distinct delimiter to enable mask pattern 's~i/e#++m%a!@*`_-;=,\1' assigned to keyword '@'");

        // when
        Pattern::template('@')->mask('@', ['@' => "s~i/e#++m%a!@*`_-;=,\1"]);
    }

    /**
     * @test
     */
    public function shouldTemplateMaskThrowPreferentiallyTrailingBackslashInsteadOfExplicitDelimiter()
    {
        // then
        $this->expectException(MaskMalformedPatternException::class);
        $this->expectExceptionMessage("Malformed pattern 's~i/e#++m%a!@*`_-;=,\1\' assigned to keyword 's'");

        // when
        Pattern::template('@')->mask('s', ['s' => "s~i/e#++m%a!@*`_-;=,\1\\"]);
    }

    /**
     * @test
     */
    public function shouldTemplateMaskThrowForRequiredExplicitDelimiterMultipleKeywordsUnused()
    {
        // then
        $this->expectException(ExplicitDelimiterRequiredException::class);
        $this->expectExceptionMessage("Failed to select a distinct delimiter to enable template in its entirety");

        // when
        Pattern::template('@')->mask(' ', ['@' => "s~i/e#++", '&' => "m%a!@*`_-;=,\1"]);
    }
}
