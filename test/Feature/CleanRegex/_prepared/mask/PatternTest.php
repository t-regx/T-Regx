<?php
namespace Test\Feature\CleanRegex\_prepared\mask;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Test\Utils\Assertion\AssertsPattern;
use TRegx\CleanRegex\Exception\ExplicitDelimiterRequiredException;
use TRegx\CleanRegex\Exception\MaskMalformedPatternException;
use TRegx\CleanRegex\Pattern;

class PatternTest extends TestCase
{
    use AssertsPattern;

    /**
     * @test
     */
    public function test()
    {
        // given
        $pattern = Pattern::mask('(%w:%s\)', [
            '%s' => '\s',
            '%w' => '\w'
        ], 'x');
        // when, then
        $this->assertPatternIs('/\(\w\:\s\\\\\\)/x', $pattern);
    }

    /**
     * @test
     */
    public function shouldChooseDelimiter()
    {
        // given
        $pattern = Pattern::mask('foo', ['x' => '%', '%w' => '#', '%s' => '/'], 'i');
        // when, then
        $this->assertPatternIs('~foo~i', $pattern);
    }

    /**
     * @test
     */
    public function shouldNotUseMaskToDelimiter()
    {
        // given
        $pattern = Pattern::mask('foo/bar', [], 'x');
        // when, then
        $this->assertPatternIs('/foo\/bar/x', $pattern);
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
        Pattern::mask('foo', ['' => 'bar']);
    }

    /**
     * @test
     */
    public function shouldParseUnicode()
    {
        // when
        $pattern = Pattern::mask('$', ['$' => 'ę']);
        // then
        $this->assertConsumesFirst('ę', $pattern);
    }

    /**
     * @test
     */
    public function shouldAcceptControlCharacter()
    {
        // when
        $pattern = Pattern::mask('!s', ['!s' => '\c!']);
        // then
        $this->assertConsumesFirst(\chr(97), $pattern);
    }

    /**
     * @test
     */
    public function shouldAcceptTrailingSlashInControlCharacter()
    {
        // when
        $pattern = Pattern::mask('!s', ['!s' => '\c\\']);
        // then
        $this->assertConsumesFirst(\chr(28), $pattern);
    }

    /**
     * @test
     */
    public function shouldAcceptTrailingSlashInQuote()
    {
        // when
        $pattern = Pattern::mask('!s', ['!s' => '\Q\\']);
        // then
        $this->assertConsumesFirst('\\', $pattern);
    }

    /**
     * @test
     */
    public function shouldAcceptTrailingEscapedSlash()
    {
        // when
        $pattern = Pattern::mask('!s', ['!s' => '\\\\']);
        // then
        $this->assertConsumesFirst('\\', $pattern);
    }

    /**
     * @test
     */
    public function shouldThrowForTrailingEscape()
    {
        // then
        $this->expectException(MaskMalformedPatternException::class);
        $this->expectExceptionMessage("Malformed pattern '\w\' assigned to keyword '%w'");
        // when
        Pattern::mask('(%w:%s\)', ['%s' => '\s', '%w' => '\w\\'], 'x');
    }

    /**
     * @test
     */
    public function shouldThrowForRequiredExplicitDelimiterSingleKeyword()
    {
        // then
        $this->expectException(ExplicitDelimiterRequiredException::class);
        $this->expectExceptionMessage("Failed to select a distinct delimiter to enable mask pattern 's~i/e#++m%a!@*`_-;=,\1' assigned to keyword '@'");
        // when
        Pattern::mask('@', ['@' => "s~i/e#++m%a!@*`_-;=,\1"]);
    }

    /**
     * @test
     */
    public function shouldThrowPreferentiallyTrailingBackslashInsteadOfExplicitDelimiter()
    {
        // then
        $this->expectException(MaskMalformedPatternException::class);
        $this->expectExceptionMessage("Malformed pattern 's~i/e#++m%a!@*`_-;=,\1\' assigned to keyword 's'");
        // when
        Pattern::mask('s', ['s' => "s~i/e#++m%a!@*`_-;=,\1\\"]);
    }

    /**
     * @test
     */
    public function shouldThrowForRequiredExplicitDelimiterMultipleKeywordsUnused()
    {
        // then
        $this->expectException(ExplicitDelimiterRequiredException::class);
        $this->expectExceptionMessage("Failed to select a distinct delimiter to enable mask keywords in their entirety: s~i/e#++, m%a!@*`_-;=,\1");
        // when
        Pattern::mask(' ', ['@' => "s~i/e#++", '&' => "m%a!@*`_-;=,\1"]);
    }

    /**
     * @test
     */
    public function shouldAcceptGroupFlags()
    {
        // given
        $pattern = Pattern::mask('Foo:*', ['*' => '(?i:Bar)']);
        // when, then
        $this->assertPatternTests($pattern, 'Foo:BAR');
        $this->assertPatternTests($pattern, 'Foo:bar');
    }

    /**
     * @test
     */
    public function shouldPrioritizeOrder_BothKeywords()
    {
        // when
        $pattern = Pattern::mask('1a2bc', [
            '1a'   => '(one)',
            '2bc'  => '(two)',
            '1a2b' => '(both)',
        ]);
        // then
        $this->assertPatternIs('/(one)(two)/', $pattern);
    }

    /**
     * @test
     */
    public function shouldPrioritizeOrder_SingleKeyword()
    {
        // when
        $pattern = Pattern::mask('1a2bc', [
            '1a2b' => '(single)',
            '1a'   => '(one)',
            '2bc'  => '(two)',
        ]);
        // then
        $this->assertPatternIs('/(single)c/', $pattern);
    }

    /**
     * @test
     */
    public function shouldBeIdentity_EmptyKeywords(): void
    {
        // given
        $pattern = Pattern::mask('Welcome', []);
        // then
        $this->assertPatternIs('/Welcome/', $pattern);
    }

    /**
     * @test
     */
    public function shouldAcceptKeyword_ForwardSlash(): void
    {
        /**
         * We test for it, since we know that forward slash is currently
         * being used as a delimiter of split by regular expression of the keywords.
         */
        // when
        $pattern = Pattern::mask('~/', ['/' => 'slash']);
        // then
        $this->assertPatternIs('/~slash/', $pattern);
    }

    /**
     * @test
     */
    public function shouldAcceptKeyword_Hash(): void
    {
        // when
        $pattern = Pattern::mask('~#', ['#' => 'hash']);
        // then
        $this->assertPatternIs('/~hash/', $pattern);
    }

    /**
     * @test
     */
    public function shouldAcceptKeyword_Whitespace(): void
    {
        // when
        $pattern = Pattern::mask("\t", ["\t" => 'whitespace']);
        // then
        $this->assertPatternIs('/whitespace/', $pattern);
    }

    /**
     * @test
     */
    public function shouldAcceptKeyword_Unicode(): void
    {
        // when
        $pattern = Pattern::mask("Łomża", ['ż' => 'ż']);
        // then
        $this->assertPatternIs('/Łomża/', $pattern);
    }

    /**
     * @test
     */
    public function shouldAcceptKeyword_RegularExpression(): void
    {
        // when
        $pattern = Pattern::mask('Foo)To]The{{Bar', [
            '{' => '\{',
            ']' => '\]',
            ')' => '\)',
        ]);
        // then
        $this->assertPatternIs('/Foo\)To\]The\{\{Bar/', $pattern);
    }

    /**
     * @test
     */
    public function shouldValidateMaskWithFlags()
    {
        // then
        $this->expectException(MaskMalformedPatternException::class);
        $this->expectExceptionMessage("Malformed pattern '#commen(t\nfoo)' assigned to keyword '*'");
        // when, then
        Pattern::mask('*', ['*' => "#commen(t\nfoo)"], 'x');
    }
}
