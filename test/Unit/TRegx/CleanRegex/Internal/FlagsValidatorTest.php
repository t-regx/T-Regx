<?php
namespace Test\Unit\TRegx\CleanRegex\Internal;

use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Internal\FlagsValidator;

class FlagsValidatorTest extends TestCase
{
    /**
     * @test
     */
    public function shouldBeValid()
    {
        // given
        $flags = new FlagsValidator();

        // when
        $isValid = $flags->isValid('mi');

        // then
        $this->assertTrue($isValid);
    }

    /**
     * @test
     */
    public function shouldBeValid_empty()
    {
        // given
        $flags = new FlagsValidator();

        // when
        $isValid = $flags->isValid('');

        // then
        $this->assertTrue($isValid);
    }

    /**
     * @test
     * @dataProvider invalidFlags
     * @param string $flag
     */
    public function shouldNotBeValid(string $flag)
    {
        // given
        $validator = new FlagsValidator();

        // when
        $isValid = $validator->isValid($flag);

        // then
        $this->assertFalse($isValid, "Failed asserting that flags '$flag' is invalid");
    }

    public function invalidFlags()
    {
        return [
            // whitespace
            ['g g'],

            // flags
            ['+g'],
            ['-g'],
            ['/'],
            ['G'],
        ];
    }
}
