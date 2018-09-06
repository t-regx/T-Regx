<?php
namespace Test\Unit\CleanRegex;

use CleanRegex\Internal\Pattern;
use CleanRegex\ValidPattern;
use PHPUnit\Framework\TestCase;

class ValidPatternTest extends TestCase
{
    /**
     * @test
     * @dataProvider validPatterns
     * @param string $string
     */
    public function shouldValidatePattern(string $string)
    {
        // given
        $pattern = new ValidPattern(new Pattern($string));

        // when
        $isValid = $pattern->isValid();

        // then
        $this->assertTrue($isValid, "Failed asserting that pattern is valid");
    }

    public function validPatterns()
    {
        return [
            ['~((https?|ftp)://(\S*?\.\S*?))([\s)\[\]{},;"\':<]|\.\s | $)~'],
            ['!exclamation marks!'],
        ];
    }

    /**
     * @test
     * @dataProvider \Test\DataProviders::invalidPregPatterns()
     * @param string $string
     */
    public function shouldNotValidatePattern(string $string)
    {
        // given
        $pattern = new ValidPattern(new Pattern($string));

        // when
        $isValid = $pattern->isValid();

        // then
        $this->assertFalse($isValid, "Failed asserting that pattern is invalid");
    }
}
