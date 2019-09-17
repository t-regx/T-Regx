<?php
namespace Test\Feature\TRegx\CleanRegex\Match\group;

use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Exception\CleanRegex\GroupNotMatchedException;
use TRegx\CleanRegex\Exception\CleanRegex\NonexistentGroupException;
use TRegx\CleanRegex\Exception\CleanRegex\SubjectNotMatchedException;

class MatchPatternTest extends TestCase
{
    /**
     * @test
     */
    public function shouldGet_all()
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
    public function shouldGet_iterator()
    {
        // given
        $subject = 'Computer L Three Four';

        // when
        $iterator = pattern('[A-Z](?<lowercase>[a-z]+)?')->match($subject)->group('lowercase')->iterator();

        // then
        $this->assertEquals(['omputer', null, 'hree', 'our'], iterator_to_array($iterator));
    }

    /**
     * @test
     */
    public function shouldGet_all_unmatched()
    {
        // given
        $subject = 'NOT MATCHING';

        // when
        $all = pattern('[A-Z](?<lowercase>[a-z]+)')->match($subject)->group('lowercase')->all();

        // then
        $this->assertEmpty($all);
    }

    /**
     * @test
     */
    public function shouldGet_onlyOne_unmatched()
    {
        // given
        $subject = 'NOT MATCHING';

        // when
        $all = pattern('[A-Z](?<lowercase>[a-z]+)')->match($subject)->group('lowercase')->only(1);

        // then
        $this->assertEmpty($all);
    }

    /**
     * @test
     */
    public function shouldGet_first()
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
    public function shouldGet_first_forEmptyMatch()
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
    public function shouldThrow_first_unmatched()
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
    public function shouldThrow_first_subject_unmatched()
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
    public function shouldThrow_first_nonexistent()
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
    public function shouldThrow_all_nonexistent()
    {
        // given
        $subject = 'L Three Four';

        // then
        $this->expectException(NonexistentGroupException::class);
        $this->expectExceptionMessage("Nonexistent group: 'missing'");

        // when
        pattern('[A-Z](?<lowercase>[a-z]+)?')->match($subject)->group('missing')->all();
    }

    /**
     * @test
     */
    public function shouldThrow_onlyOne_nonexistent()
    {
        // given
        $subject = 'L Three Four';

        // then
        $this->expectException(NonexistentGroupException::class);
        $this->expectExceptionMessage("Nonexistent group: 'missing'");

        // when
        pattern('[A-Z](?<lowercase>[a-z]+)?')->match($subject)->group('missing')->only(1);
    }

    /**
     * @test
     */
    public function shouldThrow_first()
    {
        // given
        $subject = 'unmatching subject';

        // then
        $this->expectException(SubjectNotMatchedException::class);
        $this->expectExceptionMessage("Expected to get group 'lowercase' from the first match, but subject was not matched at all");

        // when
        pattern('[A-Z](?<lowercase>[a-z]+)?')->match($subject)->group('lowercase')->first();
    }

    /**
     * @test
     */
    public function shouldGet_only()
    {
        // given
        $subject = 'D Computer';

        // when
        $groups1 = pattern('[A-Z](?<lowercase>[a-z]+)?')->match($subject)->group('lowercase')->only(1);
        $groups2 = pattern('[A-Z](?<lowercase>[a-z]+)?')->match($subject)->group('lowercase')->only(2);

        // then
        $this->assertEquals([null], $groups1);
        $this->assertEquals([null, 'omputer'], $groups2);
    }

    /**
     * @test
     */
    public function shouldGet_offsets()
    {
        // given
        $offsets = pattern('[A-Z](?<lowercase>[a-z]+)?')
            ->match('xd Computer L Three Four')
            ->group('lowercase')
            ->offsets();

        // when
        $first = $offsets->first();
        $only1 = $offsets->only(1);
        $only2 = $offsets->only(2);
        $all = $offsets->all();

        // then
        $this->assertEquals(4, $first);
        $this->assertEquals([4], $only1);
        $this->assertEquals([4, null], $only2);
        $this->assertEquals([4, null, 15, 21], $all);
    }

    /**
     * @test
     */
    public function shouldGet_offsets_onlyOne_null()
    {
        // given
        $offsets = pattern('[A-Z](?<lowercase>[a-z]+)?')
            ->match('xd L Three Four')
            ->group('lowercase')
            ->offsets();

        // when
        $only1 = $offsets->only(1);

        // then
        $this->assertEquals([null], $only1);
    }

}
