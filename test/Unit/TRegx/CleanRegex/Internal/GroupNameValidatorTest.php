<?php
namespace Test\Unit\TRegx\CleanRegex\Internal;

use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Internal\GroupNameValidator;

class GroupNameValidatorTest extends TestCase
{
    /**
     * @test
     * @dataProvider validGroups
     * @param string $nameOrIndex
     */
    public function shouldValidate($nameOrIndex)
    {
        // given
        $validatorString = new GroupNameValidator($nameOrIndex);

        // when
        $validatorString->validate();

        // then
        $this->assertTrue(true);
    }

    /**
     * @test
     * @dataProvider validGroups
     * @param string $nameOrIndex
     */
    public function shouldBeValid($nameOrIndex)
    {
        // given
        $validatorString = new GroupNameValidator($nameOrIndex);

        // when
        $isValid = $validatorString->isGroupValid();

        // then
        $this->assertTrue($isValid);
    }

    /**
     * @test
     * @dataProvider invalidGroup
     * @param string $nameOrIndex
     * @param string $message
     */
    public function shouldNotValidate($nameOrIndex, string $message)
    {
        // given
        $validatorString = new GroupNameValidator($nameOrIndex);

        // then
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage($message);

        // when
        $validatorString->validate();
    }

    /**
     * @test
     * @dataProvider invalidGroup
     * @param string $nameOrIndex
     * @param string $message
     */
    public function shouldNotBeValid($nameOrIndex, string $message)
    {
        // given
        $validatorString = new GroupNameValidator($nameOrIndex);

        // when
        $isValid = $validatorString->isGroupValid();

        // then
        $this->assertFalse($isValid);
    }

    public function validGroups(): array
    {
        return [
            ['group'],
            ['GROUP'],
            ['g'],
            ['a123_'],
            [0],
            [14],
        ];
    }

    public function invalidGroup(): array
    {
        return [
            ['9group', "Group name must be an alphanumeric string starting with a letter, given: '9group"],
            ['_group', "Group name must be an alphanumeric string starting with a letter, given: '_group"],
            ['group space', "Group name must be an alphanumeric string starting with a letter, given: 'group space"],
            [-15, 'Group index can only be a positive integer, given: -15'],
            [2.23, 'Group index can only be an integer or a string, given: double (2.23)'],
            [null, 'Group index can only be an integer or a string, given: null'],
        ];
    }
}
