<?php
namespace Test\Feature\CleanRegex\_prepared\template;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Test\Utils\Assertion\AssertsPattern;
use TRegx\CleanRegex\Exception\ExplicitDelimiterRequiredException;
use TRegx\CleanRegex\Exception\MaskMalformedPatternException;
use TRegx\CleanRegex\Exception\PlaceholderFigureException;
use TRegx\CleanRegex\Pattern;

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
        $this->assertPatternIs($expected, $pattern);
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
     */
    public function shouldThrowForMissingTemplateAlteration()
    {
        // given
        $template = Pattern::template('@@@@@');
        // then
        $this->expectException(PlaceholderFigureException::class);
        $this->expectExceptionMessage('Not enough corresponding figures supplied. Used 5 placeholders, but 1 figures supplied.');
        // when
        $template->alteration(['foo', 'bar']);
    }

    /**
     * @test
     */
    public function shouldThrowForSuperfluousTemplateAlteration()
    {
        // given
        $template = Pattern::template('Foo');
        // then
        $this->expectException(PlaceholderFigureException::class);
        $this->expectExceptionMessage('Found a superfluous figure: array (2). Used 0 placeholders, but 1 figures supplied.');
        // when
        $template->alteration(['foo', 'bar']);
    }

    /**
     * @test
     */
    public function shouldBuildTemplateWithPattern()
    {
        // when
        $pattern = Pattern::template('foo:@')->pattern('#https?/www%');

        // then
        $this->assertPatternIs('~foo:#https?/www%~', $pattern);
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
        $this->assertPatternIs('#/#', $pattern);
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
    public function shouldTemplatePatternAcceptTrailingControlBackslash()
    {
        // when
        $pattern = Pattern::template('/foo:@')->pattern('\c\\');

        // then
        $this->assertConsumesFirst('/foo:' . \chr(28), $pattern);
        $this->assertConsumesFirst("/foo:\x1c", $pattern);
        $this->assertPatternIs('#/foo:\c\{1}#', $pattern);
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
    public function shouldMaskAcceptTrailingEscapedSlash()
    {
        // when
        $pattern = Pattern::template('@')->mask('!s', ['!s' => '\\\\']);

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

    /**
     * @test
     */
    public function shouldAcceptGroupFlags()
    {
        // given
        $pattern = Pattern::template('Foo:(?i:@)')->literal('Bar');
        // when, then
        $this->assertPatternTests($pattern, 'Foo:BAR');
        $this->assertPatternTests($pattern, 'Foo:bar');
    }

    /**
     * @test
     */
    public function shouldInjectInCommentWithoutExtendedMode()
    {
        // given
        $pattern = Pattern::template("/#@\n", 'i')->literal('cat~');
        // when, then
        $this->assertConsumesFirst("/#cat~\n", $pattern);
        $this->assertPatternIs("%/#cat~\n%i", $pattern);
    }

    /**
     * @test
     */
    public function shouldNotInjectPlaceholderInCommentExtendedMode()
    {
        // given
        $pattern = Pattern::template('^@$#@', 'x')->literal('Foo');
        // when, then
        $this->assertConsumesFirst('Foo', $pattern);
        $this->assertPatternIs('/^Foo$#@/x', $pattern);
    }

    /**
     * @test
     */
    public function testSubpatternFlags()
    {
        // given
        $pattern = Pattern::template('(?m-i:@')->literal('one');
        // when, then
        $this->assertPatternIs('/(?m-i:one/', $pattern);
    }
}
