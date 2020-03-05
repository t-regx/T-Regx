<?php
namespace Test\Feature\TRegx\CleanRegex\Match\group\findFirst;

use PHPUnit\Framework\TestCase;
use Test\Feature\TRegx\CleanRegex\Replace\by\group\CustomException;
use TRegx\CleanRegex\Exception\GroupNotMatchedException;
use TRegx\CleanRegex\Exception\NonexistentGroupException;
use TRegx\CleanRegex\Exception\SubjectNotMatchedException;
use TRegx\CleanRegex\Match\Details\Group\MatchGroup;
use TRegx\CleanRegex\Match\Details\NotMatched;

class MatchPatternTest extends TestCase
{
    /**
     * @test
     */
    public function shouldReturnMappedValue()
    {
        // when
        $result = pattern('Computer')
            ->match('Computer')
            ->group(0)
            ->findFirst(function () {
                return "result";
            })
            ->orThrow();

        // then
        $this->assertEquals('result', $result);
    }

    /**
     * @test
     */
    public function shouldCall_withDetails()
    {
        // when
        pattern('[A-Z](?<lowercase>[a-z]+)?')
            ->match('Computer L Three Four')
            ->group('lowercase')
            ->findFirst(function (MatchGroup $group) {
                $this->assertEquals('omputer', $group->text());
            })
            ->orThrow();
    }

    /**
     * @test
     */
    public function shouldCall_withDetails_all()
    {
        // when
        pattern('[A-Z](?<lowercase>[a-z]+)?')
            ->match('Computer L Three Four')
            ->group('lowercase')
            ->findFirst(function (MatchGroup $group) {
                $this->assertEquals(['omputer', null, 'hree', 'our'], $group->all());
            })
            ->orThrow();
    }

    /**
     * @test
     */
    public function shouldGet_forEmptyMatch()
    {
        // when
        pattern('Foo (?<bar>[a-z]*)')
            ->match('Foo NOT MATCH')
            ->group('bar')
            ->findFirst(function (MatchGroup $group) {
                $this->assertEquals('', $group->text());
            })
            ->orThrow();
    }

    /**
     * @test
     */
    public function shouldThrow_unmatchedSubject()
    {
        // then
        $this->expectException(SubjectNotMatchedException::class);
        $this->expectExceptionMessage("Expected to get group '0' from the first match, but subject was not matched at all");

        // when
        pattern('Foo')
            ->match('123')
            ->group(0)
            ->findFirst(function () {
                $this->fail();
            })
            ->orThrow();
    }

    /**
     * @test
     */
    public function shouldThrow_unmatchedGroup()
    {
        // then
        $this->expectException(GroupNotMatchedException::class);
        $this->expectExceptionMessage("Expected to get group 'group' from the first match, but the group was not matched");

        // when
        pattern('Foo(?<group>Bar)?')
            ->match('Foo')
            ->group('group')
            ->findFirst(function () {
                $this->fail();
            })
            ->orThrow();
    }

    /**
     * @test
     */
    public function shouldThrow_unmatchedSubject_customException()
    {
        // then
        $this->expectException(CustomException::class);
        $this->expectExceptionMessage("Expected to get group '0' from the first match, but subject was not matched at all");

        // when
        pattern('Foo')
            ->match('123')
            ->group(0)
            ->findFirst(function () {
                $this->fail();
            })
            ->orThrow(CustomException::class);
    }

    /**
     * @test
     */
    public function shouldThrow_unmatchedGroup_customException()
    {
        // then
        $this->expectException(CustomException::class);
        $this->expectExceptionMessage("Expected to get group 'lowercase' from the first match, but the group was not matched");

        // when
        pattern('[A-Z](?<lowercase>[a-z]+)?')
            ->match('L')
            ->group('lowercase')
            ->findFirst(function () {
                $this->fail();
            })
            ->orThrow(CustomException::class);
    }

    /**
     * @test
     */
    public function shouldPass_NotMatched_unmatchedSubject()
    {
        // when
        pattern('Foo(?<one>)(?<two>)')
            ->match('123')
            ->group(0)
            ->findFirst(function () {
                $this->fail();
            })
            ->orElse(function (NotMatched $notMatched) {
                $this->assertEquals(['one', 'two'], $notMatched->groupNames());
            });
    }

    /**
     * @test
     */
    public function shouldPass_NotMatched_unmatchedGroup()
    {
        // when
        pattern('Foo(?<one>Bar)?')
            ->match('Foo')
            ->group(1)
            ->findFirst(function () {
                $this->fail();
            })
            ->orElse(function (NotMatched $notMatched) {
                $this->assertEquals(['one'], $notMatched->groupNames());
            });
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
        pattern('[A-Z](?<lowercase>[a-z]+)?')->match($subject)
            ->group('missing')
            ->findFirst(function () {
            })
            ->orReturn('');
    }
}
