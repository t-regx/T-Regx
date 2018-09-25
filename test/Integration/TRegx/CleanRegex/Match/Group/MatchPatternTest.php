<?php
namespace Test\Integration\TRegx\CleanRegex\Match\Group;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Exception\CleanRegex\NonexistentGroupException;
use TRegx\CleanRegex\Match\Details\Match;

class MatchPatternTest extends TestCase
{
    /**
     * @test
     */
    public function shouldThrowOnMissingGroup()
    {
        // then
        $this->expectException(NonexistentGroupException::class);

        // when
        pattern('(?<one>hello)')
            ->match('hello')
            ->first(function (Match $match) {
                $match->group('two');
            });
    }

    /**
     * @test
     */
    public function shouldGetGroups()
    {
        // given
        $subject = 'Computer L Three Four';

        // when
        $groups = pattern('[A-Z](?<lowercase>[a-z]+)?')->match($subject)->group('lowercase')->all();

        // then
        $this->assertEquals(['omputer', null, 'hree', 'our'], $groups);
    }

    /**
     * @test
     */
    public function shouldGetGroup_onlyOne()
    {
        // given
        $subject = 'D Computer';

        // when
        $groups = pattern('[A-Z](?<lowercase>[a-z]+)?')->match($subject)->group('lowercase')->only(1);

        // then
        $this->assertEquals([null], $groups);
    }

    /**
     * @test
     */
    public function shouldValidateGroupName()
    {
        // then
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Group index can only be an integer or string, given: boolean (true)');

        // given
        pattern('(?<one>first) and (?<two>second)')
            ->match('first and second')
            ->first(function (Match $match) {
                // when
                $match->group(true);
            });
    }
}
