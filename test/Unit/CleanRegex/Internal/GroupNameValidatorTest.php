<?php
namespace Test\Unit\CleanRegex\Internal;

use CleanRegex\Internal\GroupNameValidator;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class GroupNameValidatorTest extends TestCase
{
    /**
     * @test
     */
    public function shouldValidate_string()
    {
        // given
        $validator = new GroupNameValidator('string');

        // when
        $validator->validate();

        // then
        $this->assertTrue(true);
    }

    /**
     * @test
     */
    public function shouldValidate_int()
    {
        // given
        $validator = new GroupNameValidator(2);

        // when
        $validator->validate();

        // then
        $this->assertTrue(true);
    }

    /**
     * @test
     */
    public function shouldNotValidate_otherTypes()
    {
        // given
        $validator = new GroupNameValidator(new \stdClass());

        // this
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Group index can only be an integer or string, given: stdClass');

        // when
        $validator->validate();
    }
}
