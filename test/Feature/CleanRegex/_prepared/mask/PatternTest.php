<?php
namespace Test\Feature\CleanRegex\_prepared\mask;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Test\Utils\AssertsPattern;
use TRegx\CleanRegex\Exception\ExplicitDelimiterRequiredException;
use TRegx\CleanRegex\Exception\MaskMalformedPatternException;
use TRegx\CleanRegex\Pattern;

class PatternTest extends TestCase
{
    use AssertsPattern;

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
}
