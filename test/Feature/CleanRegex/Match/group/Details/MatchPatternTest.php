<?php
namespace Test\Feature\TRegx\CleanRegex\Match\group\Details;

use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Exception\GroupNotMatchedException;
use TRegx\CleanRegex\Match\Details\Group\Group;

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
        $result = pattern('[A-Z](?<lowercase>[a-z]+)?')->match($subject)->group('lowercase')->map(function (Group $group) {
            return $group->text();
        });

        // then
        $this->assertSame(['oo', 'ar', 'ar'], $result);
    }

    /**
     * @test
     */
    public function shouldFlatMap()
    {
        // given
        $subject = 'XXX:abc YYY:efg ZZZ:ijk';

        // when
        $result = pattern('[A-Z]+:(?<lowercase>[a-z]+)')->match($subject)->group('lowercase')->flatMap(function (Group $group) {
            return ['word:' . $group->text() => 'offset:' . $group->offset(), $group->all()];
        });

        // then
        $expected = [
            'word:abc' => 'offset:4', ['abc', 'efg', 'ijk'],
            'word:efg' => 'offset:12', ['abc', 'efg', 'ijk'],
            'word:ijk' => 'offset:20', ['abc', 'efg', 'ijk'],
        ];
        $this->assertSame($expected, $result);
    }

    /**
     * @test
     */
    public function shouldGetOffset()
    {
        // given
        $subject = 'Foo Bar Car';

        // when
        $result = pattern('[A-Z](?<lowercase>[a-z]+)?')->match($subject)->group('lowercase')->map(function (Group $group) {
            return $group->offset();
        });

        // then
        $this->assertSame([1, 5, 9], $result);
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
        $this->expectExceptionMessage("Expected to call text() for group 'lowercase', but the group was not matched");

        // when
        pattern('[A-Z](?<lowercase>[a-z]+)?')->match($subject)->group('lowercase')->map(function (Group $group) {
            return $group->text();
        });
    }
}
