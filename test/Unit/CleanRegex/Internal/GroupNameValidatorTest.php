<?php
namespace Test\Unit\TRegx\CleanRegex\Internal;

use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Internal\GroupNameValidator;

/**
 * @covers \TRegx\CleanRegex\Internal\GroupNameValidator
 */
class GroupNameValidatorTest extends TestCase
{
    /**
     * @test
     * @dataProvider validGroups
     * @param string|int $nameOrIndex
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
     * @param string|int $nameOrIndex
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
     * @param string|int $nameOrIndex
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
     * @param string|int $nameOrIndex
     */
    public function shouldNotBeValid($nameOrIndex)
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
            ['_group'],
            ['GROUP'],
            ['g'],
            ['a123_'],
            [0],
            [14],
        ];
    }

    /**
     * @test
     */
    public function shouldNotLetFuckedUpPhpRuinGroupValidation(): void
    {
        // given
        setlocale(LC_CTYPE, "pl");
        $validator = new GroupNameValidator('grópa');

        // when
        $valid = $validator->isGroupValid();

        // then
        $this->assertFalse($valid, 'Failed asserting that group validator ignores locale');
    }

    public function invalidGroup(): array
    {
        return [
            ['9group', "Group name must be an alphanumeric string, not starting with a digit, but '9group' given"],
            ['group space', "Group name must be an alphanumeric string, not starting with a digit, but 'group space' given"],
            [-15, 'Group index must be a non-negative integer, but -15 given'],
            [2.23, 'Group index must be an integer or a string, but double (2.23) given'],
            [null, 'Group index must be an integer or a string, but null given'],
            ["group\n", "Group name must be an alphanumeric string, not starting with a digit, but 'group\\n' given"],
            ["a\x7f\x7fb", "Group name must be an alphanumeric string, not starting with a digit, but 'a\\x7f\\x7fb' given"],
            ["a\xc2\xa0b", "Group name must be an alphanumeric string, not starting with a digit, but 'a\\xc2\\xa0b' given"],
        ];
    }
}
