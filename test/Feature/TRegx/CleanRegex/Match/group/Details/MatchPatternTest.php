<?php
namespace Test\Feature\TRegx\CleanRegex\Match\group\Details;

use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Exception\CleanRegex\GroupNotMatchedException;
use TRegx\CleanRegex\Match\Details\Group\MatchGroup;

class MatchPatternTest extends TestCase
{
    /**
     * @test
     */
    public function test()
    {
        // given
        $subject = 'Foo Bar Car';

        // when
        $result = pattern('[A-Z](?<lowercase>[a-z]+)?')->match($subject)->group('lowercase')->map(function (MatchGroup $group) {
            return $group->text();
        });

        // then
        $this->assertEquals(['oo', 'ar', 'ar'], $result);
    }

    /**
     * @test
     */
    public function shouldGetOffset()
    {
        // given
        $subject = 'Foo Bar Car';

        // when
        $result = pattern('[A-Z](?<lowercase>[a-z]+)?')->match($subject)->group('lowercase')->map(function (MatchGroup $group) {
            return $group->offset();
        });

        // then
        $this->assertEquals([1, 5, 9], $result);
    }

    /**
     * @test
     */
    public function test_unmatchedGroup()
    {
        // given
        $subject = 'Computer L Three Four';

        // then
        $this->expectException(GroupNotMatchedException::class);
        $this->expectExceptionMessage("Expected to call text() for group 'lowercase', but group was not matched");

        // when
        pattern('[A-Z](?<lowercase>[a-z]+)?')->match($subject)->group('lowercase')->map(function (MatchGroup $group) {
            return $group->text();
        });
    }
}
