<?php
namespace Test\Feature\CleanRegex\Match\Detail\group;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Exception\NonexistentGroupException;
use TRegx\CleanRegex\Pattern;

class DetailTest extends TestCase
{
    /**
     * @test
     */
    public function shouldThrow_onMissingGroup()
    {
        // given
        $detail = pattern('(?<one>hello)')->match('hello')->first();
        // then
        $this->expectException(NonexistentGroupException::class);
        $this->expectExceptionMessage("Nonexistent group: 'two'");
        // when
        $detail->group('two');
    }

    /**
     * @test
     */
    public function shouldThrow_forInvalidGroupNameType()
    {
        // given
        $detail = Pattern::of('Yikes!')->match('Yikes!')->first();
        // then
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Group index must be an integer or a string, but boolean (true) given');
        // when
        $detail->group(true);
    }

    /**
     * @test
     */
    public function shouldThrow_forMalformedGroupName()
    {
        // given
        $detail = Pattern::of('Yikes!')->match('Yikes!')->first();
        // then
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("Group name must be an alphanumeric string, not starting with a digit, but '2group' given");
        // when
        $detail->group('2group');
    }

    /**
     * @test
     */
    public function shouldGetThreeDigitGroup()
    {
        // given
        $groups = \str_repeat('()', 100);
        $pattern = Pattern("$groups(Foo)");
        $detail = $pattern->match('Foo')->first();
        // when
        $group = $detail->group(101);
        // then
        $this->assertSame('Foo', $group->text());
    }

    /**
     * @test
     * @dataProvider invalidGroupNames
     * @param string|int $name
     * @param string $message
     */
    public function shouldThrowForMalformedName(string $name, string $message)
    {
        // given
        $detail = Pattern::of('Foo')->match('Foo')->first();
        // then
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage($message);
        // when
        $detail->group($name);
    }

    public function invalidGroupNames(): array
    {
        return [
            ['9group', "Group name must be an alphanumeric string, not starting with a digit, but '9group' given"],
            ['group space', "Group name must be an alphanumeric string, not starting with a digit, but 'group space' given"],
            ["group\n", 'Group name must be an alphanumeric string, not starting with a digit, but \'group\n\' given'],
            ["a\x7f\x7fb", 'Group name must be an alphanumeric string, not starting with a digit, but \'a\x7f\x7fb\' given'],
            ["a\xc2\xa0b", 'Group name must be an alphanumeric string, not starting with a digit, but \'a\xc2\xa0b\' given'],
        ];
    }
}
