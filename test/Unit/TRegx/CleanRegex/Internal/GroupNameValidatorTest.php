<?php
namespace Test\Unit\TRegx\CleanRegex\Internal;

use TRegx\CleanRegex\Internal\GroupNameValidator;
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
        $validator = new GroupNameValidator('string_69_string');

        // when
        $validator->validate();

        // then
        $this->assertTrue(true);
    }

    /**
     * @test
     */
    public function shouldValidate_oneLetterGroup()
    {
        // given
        $validator = new GroupNameValidator('g');

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

    /**
     * @test
     * @dataProvider invalidStringGroupName
     * @param string $groupName
     */
    public function shouldNotValidate_string_nonAlphanumeric(string $groupName)
    {
        // given
        $validator = new GroupNameValidator($groupName);

        // this
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Group name must be an alphanumeric string sequence starting with a letter, or an integer');

        // when
        $validator->validate();
    }

    public function invalidStringGroupName()
    {
        return [
            ['brace('],
            ['9group'],
            ['_group'],
            ['group space'],
        ];
    }
}
