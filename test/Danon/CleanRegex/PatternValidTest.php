<?php
namespace Test\Danon\CleanRegex;

use Danon\CleanRegex\Pattern;
use PHPUnit\Framework\TestCase;

class PatternValidTest extends TestCase
{
    /**
     * @test
     * @dataProvider validPatterns
     * @param string $string
     */
    public function shouldValidatePattern(string $string)
    {
        // given
        $pattern = new Pattern($string);

        // when
        $isValid = $pattern->valid();

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
        $pattern = new Pattern($string);

        // when
        $isValid = $pattern->valid();

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
