<?php
namespace Test\Feature\TRegx\CleanRegex\Match\Details;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Test\Utils\Functions;
use Test\Utils\StringCasts;
use TRegx\CleanRegex\Match\Details\NotMatched;

/**
 * @covers \TRegx\CleanRegex\Match\Details\NotMatched
 */
class NotMatchedTest extends TestCase
{
    use StringCasts;

    /**
     * @test
     */
    public function shouldGetSubject()
    {
        // when
        pattern('Foo')
            ->match('Bar')
            ->findFirst(Functions::fail())
            ->orElse(Functions::out($notMatched));

        // when
        $subject = $notMatched->subject();

        // then
        $this->assertSame('Bar', $subject);
    }

    /**
     * @test
     * @dataProvider existingGroups
     * @param string|int $group
     */
    public function shouldHaveGroup($group)
    {
        // given
        pattern('(?<first>first)(?<second>second)')
            ->match('Bar')
            ->findFirst(Functions::fail())
            ->orElse(Functions::out($notMatched));

        // when
        $hasGroup = $notMatched->hasGroup($group);

        // then
        $this->assertTrue($hasGroup, "Failed asserting that group $group exists");
    }

    /**
     * @test
     * @dataProvider missingGroups
     * @param string|int $groupIdentifier
     */
    public function shouldNotHaveGroup($groupIdentifier)
    {
        // given
        pattern('(?<first>first)(?<second>second)')
            ->match('Bar')
            ->findFirst(Functions::fail())
            ->orElse(Functions::out($notMatched));

        // when
        $hasGroup = $notMatched->hasGroup($groupIdentifier);

        // then
        $this->assertFalse($hasGroup, "Failed asserting that group $groupIdentifier is missing");
    }

    public function existingGroups(): array
    {
        return [[0], [1], [2], ['first'], ['second']];
    }

    public function missingGroups(): array
    {
        return [[3], [4], [5], ['missing']];
    }

    /**
     * @test
     * @dataProvider invalidGroupIdentifiers
     * @param string|int $groupIdentifier
     * @param string $message
     */
    public function shouldThrowForInvalidGroupIdentifier($groupIdentifier, string $message)
    {
        // given
        pattern('Foo')
            ->match('Bar')
            ->findFirst(Functions::fail())
            ->orElse(Functions::out($notMatched));

        // then
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage($message);

        // when
        $notMatched->hasGroup($groupIdentifier);
    }

    public function invalidGroupIdentifiers(): array
    {
        return [
            [-1, 'Group index must be a non-negative integer, but -1 given'],
            [-3, 'Group index must be a non-negative integer, but -3 given'],
            ['2startingWithDigit', "Group name must be an alphanumeric string, not starting with a digit, but '2startingWithDigit' given"],
            ['dashed-dashed', "Group name must be an alphanumeric string, not starting with a digit, but 'dashed-dashed' given"],
            ['', "Group name must be an alphanumeric string, not starting with a digit, but '' given"],
        ];
    }

    /**
     * @test
     */
    public function shouldGetGroupNames()
    {
        // given
        pattern('(?<first>first)(?<second>second)')
            ->match('Bar')
            ->findFirst(Functions::fail())
            ->orElse(Functions::out($notMatched));

        // when
        $groupNames = $notMatched->groupNames();

        // then
        $this->assertSame(['first', 'second'], $groupNames);
    }

    /**
     * @test
     * @depends shouldGetGroupNames
     */
    public function shouldGetGroupNames_someMissing()
    {
        // given
        pattern('(?<group>a)(b)(?<bar>c)(d)')
            ->match('Bar')
            ->findFirst(Functions::fail())
            ->orElse(Functions::out($notMatched));

        // when
        $groupNames = $notMatched->groupNames();

        // then
        $this->assertSame(['group', null, 'bar', null], $groupNames);
    }

    /**
     * @test
     */
    public function shouldGetGroupsCount()
    {
        // given
        pattern('(?<group>a)(b)(?<bar>c)(d)')
            ->match('Bar')
            ->findFirst(Functions::fail())
            ->orElse(Functions::out($notMatched));

        // when
        $groupsCount = $notMatched->groupsCount();

        // then
        $this->assertSame(4, $groupsCount);
    }

    /**
     * @test
     */
    public function shouldNotCastToString()
    {
        // given
        pattern('Foo')
            ->match('Bar')
            ->findFirst(Functions::fail())
            ->orElse(Functions::out($notMatched));

        // when
        $this->expectExceptionCastsToString($notMatched, NotMatched::class);
    }
}
