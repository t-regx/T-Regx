<?php
namespace Test\Unit\TRegx\CleanRegex\Internal;

use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Internal\Integer;

class IntegerTest extends TestCase
{

    /**
     * @test
     * @dataProvider validIntegers
     * @param string $string
     */
    public function shouldInteger_beValid(string $string)
    {
        // given
        $result = Integer::isValid($string);

        // then
        $this->assertTrue($result);
    }

    public function validIntegers()
    {
        return [
            ['1'],
            ['-1'],
            ['0'],
            ['000'],
            ['011'],
            ['0001'],
        ];
    }

    /**
     * @test
     * @dataProvider pseudoIntegers
     * @param string $pseudoInteger
     */
    public function shouldPseudoInteger_notBeValid(string $pseudoInteger)
    {
        // given
        $result = Integer::isValid($pseudoInteger);

        // then
        $this->assertFalse($result, "Failed asserting that '$pseudoInteger' is not a valid integer");
    }

    public function pseudoIntegers()
    {
        return [
            [' 1'],
            ['1 '],
            ['-1 '],
            ["1\n"],
            ["\n1"],
            ['  '],
            [''],
            ['-'],
            ['1-1'],
            ['1 1'],
            ["1\n1"],
            ["-1\n1"],
            ['1e3'],
            ['0x'],
            ['0x10'],
            ['123foo'],
        ];
    }

    /**
     * @test
     * @dataProvider integerOverflowingStrings
     * @param string $string
     */
    public function shouldThrow_forIntegerOverflow(string $string)
    {
        // given
        $result = Integer::isValid($string);

        // then
        $this->assertFalse($result);
    }

    public function integerOverflowingStrings()
    {
        return [
            [PHP_INT_MAX . '0'],
            [PHP_INT_MIN . '0'],
        ];
    }
}
