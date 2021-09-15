<?php
namespace Test\Feature\CleanRegex\_prepared\mask;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Test\Utils\AssertsPattern;
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
}
