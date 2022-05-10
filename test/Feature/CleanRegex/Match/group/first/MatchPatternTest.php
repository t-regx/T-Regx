<?php
namespace Test\Feature\TRegx\CleanRegex\Match\group\first;

use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Exception\GroupNotMatchedException;
use TRegx\CleanRegex\Exception\NonexistentGroupException;
use TRegx\CleanRegex\Exception\SubjectNotMatchedException;
use TRegx\CleanRegex\Match\Details\Group\Group;

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
        $this->assertSame('omputer', $groups);
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
        $this->assertSame('', $groups);
    }

    /**
     * @test
     */
    public function shouldCall_withDetails()
    {
        // given
        $subject = 'Computer L Three Four';

        // when
        pattern('[A-Z](?<lowercase>[a-z]+)?')->match($subject)->group('lowercase')->first(function (Group $group) {
            $this->assertSame('omputer', $group->text());
        });
    }

    /**
     * @test
     */
    public function shouldCall_withDetails_all()
    {
        // given
        $subject = 'Computer L Three Four';

        // when
        pattern('[A-Z](?<lowercase>[a-z]+)?')->match($subject)->group('lowercase')->first(function (Group $group) {
            $this->assertSame(['omputer', null, 'hree', 'our'], $group->all());
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
            $this->assertSame('omputer', $group);
        });
    }

    /**
     * @test
     */
    public function shouldThrow_unmatched()
    {
        // then
        $this->expectException(GroupNotMatchedException::class);
        $this->expectExceptionMessage("Expected to get group #1 from the first match, but the group was not matched");

        // when
        pattern('Foo(Bar)?')->match('Foo')->group(1)->first();
    }

    /**
     * @test
     */
    public function shouldGetMatchedEmptyGroup()
    {
        // when
        $group = pattern('Foo()')->match('Foo')->group(1)->first();
        // then
        $this->assertSame('', $group);
    }

    /**
     * @test
     */
    public function shouldGetMatchedEmptyGroupNotFirst()
    {
        // when
        $group = pattern('Foo()(Bar)')->match('FooBar')->group(1)->first();
        // then
        $this->assertSame('', $group);
    }

    /**
     * @test
     */
    public function shouldThrow_SubjectNotMatched()
    {
        // then
        $this->expectException(SubjectNotMatchedException::class);
        $this->expectExceptionMessage("Expected to get group #0 from the first match, but subject was not matched at all");

        // when
        pattern('Foo')->match('Bar')->group(0)->first();
    }

    /**
     * @test
     */
    public function shouldThrow_SubjectNotMatched_getSubject()
    {
        try {
            pattern('Family, Duty, Honor')->match('Winter is coming')->group(0)->first();
        } catch (SubjectNotMatchedException $exception) {
            $this->assertSame('Winter is coming', $exception->getSubject());
        }
    }

    /**
     * @test
     */
    public function shouldThrow_OnUnmatchedSubject_OnNonexistentGroup()
    {
        // then
        $this->expectException(NonexistentGroupException::class);
        $this->expectExceptionMessage("Nonexistent group: #1");

        // when
        pattern('Foo')->match('Bar')->group(1)->first();
    }

    /**
     * @test
     */
    public function shouldThrow_nonexistent()
    {
        // then
        $this->expectException(NonexistentGroupException::class);
        $this->expectExceptionMessage("Nonexistent group: 'missing'");

        // when
        pattern('(?<existing>foo)')->match('foo')->group('missing')->first();
    }
}
