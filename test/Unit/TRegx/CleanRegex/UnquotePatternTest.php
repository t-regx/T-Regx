<?php
namespace Test\Unit\TRegx\CleanRegex;

use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\UnquotePattern;
use TRegx\SafeRegex\preg;

class UnquotePatternTest extends TestCase
{
    /**
     * @test
     */
    public function shouldUnquote()
    {
        // given
        $unquotePattern = new UnquotePattern('Did you\\?');

        // when
        $unquoted = $unquotePattern->unquote();

        // then
        $this->assertEquals('Did you?', $unquoted);
    }

    /**
     * @test
     * @dataProvider quotable
     * @param string $input
     */
    public function shouldPreserveContract(string $input)
    {
        // given
        $unquotePattern = new UnquotePattern(preg::quote($input));

        // when
        $output = $unquotePattern->unquote();

        // then
        $this->assertEquals($input, $output);
    }

    function quotable()
    {
        return [
            ['https://stackoverflow.com/search?q=preg_match#anchor'],
            ['preg_quote(\'an\\y s … \.tri\*ng\') //'],
            ['.\+*?[^]$(){}=!<>|:-'],
            ['\\\\\\'],
            ['\\\\'],
            ['"Quoted?"'],
        ];
    }

    /**
     * @test
     */
    public function shouldNotUnquote_regularCharacters()
    {
        // given
        $input = '\\\' \\" \\/ \\;';
        $unquotePattern = new UnquotePattern($input);

        // when
        $unquoted = $unquotePattern->unquote();

        // then
        $this->assertEquals($input, $unquoted);
    }

    /**
     * @test
     * @dataProvider \Test\DataProviders::invalidPregPatterns()
     * @param string $invalidPattern
     */
    public function shouldUnquoteWithoutException(string $invalidPattern)
    {
        // given
        $unquotePattern = new UnquotePattern($invalidPattern);

        // when
        $unquotePattern->unquote();

        // then
        $this->assertTrue(true);
    }
}
