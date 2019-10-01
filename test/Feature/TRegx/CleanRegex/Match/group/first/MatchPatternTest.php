<?php
namespace Test\Feature\TRegx\CleanRegex\Match\group\first;

use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Exception\CleanRegex\GroupNotMatchedException;
use TRegx\CleanRegex\Exception\CleanRegex\NonexistentGroupException;
use TRegx\CleanRegex\Exception\CleanRegex\SubjectNotMatchedException;
use TRegx\CleanRegex\Match\Details\Group\MatchGroup;

class MatchPatternTest extends TestCase
{
    /**
     * @test
     */
    public function test()
    {
        // given
        $subject = 'Computer L Three Four';

        // when
        $groups = pattern('[A-Z](?<lowercase>[a-z]+)?')->match($subject)->group('lowercase')->first();

        // then
        $this->assertEquals('omputer', $groups);
    }

    /**
     * @test
     */
    public function shouldGet_forEmptyMatch()
    {
        // given
        $subject = 'Foo NOT MATCH';

        // when
        $groups = pattern('Foo (?<bar>[a-z]*)')->match($subject)->group('bar')->first();

        // then
        $this->assertEquals('', $groups);
    }

    /**
     * @test
     */
    public function shouldCall_withDetails()
    {
        // given
        $subject = 'Computer L Three Four';

        // when
        pattern('[A-Z](?<lowercase>[a-z]+)?')->match($subject)->group('lowercase')->first(function (MatchGroup $group) {
            $this->assertEquals('omputer', $group->text());
        });
    }

    /**
     * @test
     */
    public function shouldCall_withDetails_string()
    {
        // given
        $subject = 'Computer L Three Four';

        // when
        pattern('[A-Z](?<lowercase>[a-z]+)?')->match($subject)->group('lowercase')->first(function (string $group) {
            $this->assertEquals('omputer', $group);
        });
    }

    /**
     * @test
     */
    public function shouldThrow_unmatched()
    {
        // given
        $subject = 'L Three Four';

        // then
        $this->expectException(GroupNotMatchedException::class);
        $this->expectExceptionMessage("Expected to get group 'lowercase' from the first match, but the group was not matched");

        // when
        pattern('[A-Z](?<lowercase>[a-z]+)?')->match($subject)->group('lowercase')->first();
    }

    /**
     * @test
     */
    public function shouldThrow_subject_unmatched()
    {
        // given
        $subject = '123';

        // then
        $this->expectException(SubjectNotMatchedException::class);
        $this->expectExceptionMessage("Expected to get group 'lowercase' from the first match, but subject was not matched at all");

        // when
        pattern('[A-Z](?<lowercase>[a-z]+)?')->match($subject)->group('lowercase')->first();
    }

    /**
     * @test
     */
    public function shouldThrow_nonexistent()
    {
        // given
        $subject = 'L Three Four';

        // then
        $this->expectException(NonexistentGroupException::class);
        $this->expectExceptionMessage("Nonexistent group: 'missing'");

        // when
        pattern('[A-Z](?<lowercase>[a-z]+)?')->match($subject)->group('missing')->first();
    }

    /**
     * @test
     */
    public function shouldGet_offsets()
    {
        // when
        $first = pattern('[A-Z](?<lowercase>[a-z]+)?')
            ->match('xd Computer L Three Four')
            ->group('lowercase')
            ->offsets()
            ->first();

        // then
        $this->assertEquals(4, $first);
    }
}
