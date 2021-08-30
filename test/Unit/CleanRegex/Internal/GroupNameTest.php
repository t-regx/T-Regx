<?php
namespace Test\Unit\TRegx\CleanRegex\Internal;

use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Internal\GroupKey\GroupName;

/**
 * @covers \TRegx\CleanRegex\Internal\GroupKey\GroupName
 */
class GroupNameTest extends TestCase
{
    /**
     * @test
     * @dataProvider validGroups
     * @param string|int $name
     */
    public function shouldGetValidName(string $name)
    {
        // given
        $group = new GroupName($name);

        // when
        $actual = $group->nameOrIndex();

        // then
        $this->assertSame($name, $actual);
        $this->assertSame("'$name'", "$group");
    }

    public function validGroups(): array
    {
        return [
            ['group'],
            ['_group'],
            ['GROUP'],
            ['g'],
            ['a123_'],
        ];
    }

    /**
     * @test
     * @dataProvider invalidGroup
     * @param string|int $name
     * @param string $message
     */
    public function shouldThrowForMalformedName(string $name, string $message)
    {
        // given
        $group = new GroupName($name);

        // then
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage($message);

        // when
        $group->nameOrIndex();
    }

    public function invalidGroup(): array
    {
        return [
            ['9group', "Group name must be an alphanumeric string, not starting with a digit, but '9group' given"],
            ['group space', "Group name must be an alphanumeric string, not starting with a digit, but 'group space' given"],
            ["group\n", "Group name must be an alphanumeric string, not starting with a digit, but 'group\\n' given"],
            ["a\x7f\x7fb", "Group name must be an alphanumeric string, not starting with a digit, but 'a\\x7f\\x7fb' given"],
            ["a\xc2\xa0b", "Group name must be an alphanumeric string, not starting with a digit, but 'a\\xc2\\xa0b' given"],
        ];
    }

    /**
     * @test
     */
    public function shouldNotLetFuckedUpPhpRuinGroupValidation(): void
    {
        // given
        \setLocale(LC_CTYPE, "pl");
        $groupName = new GroupName('grópa');

        // then
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage("Group name must be an alphanumeric string, not starting with a digit, but 'grópa' given");

        // when
        $groupName->nameOrIndex();
    }
}
