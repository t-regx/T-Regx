<?php
namespace Test\Danon\CleanRegex;

use Danon\CleanRegex\Pattern;
use Danon\CleanRegex\ValidPattern;
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
            ['~((https?|ftp)://(\S*?\.\S*?))([\s)\[\]{},;"\':<]|\.\s | $)~']
        ];
    }

    /**
     * @test
     * @dataProvider invalidPatterns
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

    public function invalidPatterns()
    {
        return [
            ['/un(closed.group/'],
            ['/*starting.quantifier/'],
        ];
    }
}
