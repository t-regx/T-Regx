<?php

use Danon\CleanRegex\Pattern;
use Danon\CleanRegex\ValidPattern;
use PHPUnit\Framework\TestCase;

class FlagsValidatorTest extends TestCase
{
    /**
     * @test
     * @dataProvider validPatterns
     * @param string $string
     */
    public function shouldPatternBeValid(string $string)
    {
        // given
        $validPattern = new ValidPattern(new Pattern($string));

        // when
        $isValid = $validPattern->isValid();

        // then
        $this->assertTrue($isValid);
    }

    public function validPatterns()
    {
        return [
            ['/pattern/'],
        ];
    }

    /**
     * @test
     * @dataProvider invalidPatterns
     * @param string $string
     */
    public function shouldPatternNotBeValid(string $string)
    {
        // given
        $validPattern = new ValidPattern(new Pattern($string));

        // when
        $isValid = $validPattern->isValid();

        // then
        $this->assertFalse($isValid);
    }

    public function invalidPatterns()
    {
        return [
            ['/un(closed.group/'],
            ['/*starting.quantifier/'],
        ];
    }
}
