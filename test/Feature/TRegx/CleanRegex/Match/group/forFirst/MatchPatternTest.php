<?php
namespace Test\Feature\TRegx\CleanRegex\Match\group\forFirst;

use PHPUnit\Framework\TestCase;
use Test\Feature\TRegx\CleanRegex\Replace\by\group\CustomException;
use TRegx\CleanRegex\Exception\GroupNotMatchedException;
use TRegx\CleanRegex\Exception\NonexistentGroupException;
use TRegx\CleanRegex\Exception\SubjectNotMatchedException;
use TRegx\CleanRegex\Match\Details\Group\MatchGroup;

class MatchPatternTest extends TestCase
{
    /**
     * @test
     */
    public function shouldReturnMappedValue()
    {
        // when
        $result = pattern('[A-Z](?<lowercase>[a-z]+)?')
            ->match('Computer L Three Four')
            ->group('lowercase')
            ->forFirst(function () {
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
            ->forFirst(function (MatchGroup $group) {
                $this->assertEquals('omputer', $group->text());
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
            ->forFirst(function (MatchGroup $group) {
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
        $this->expectExceptionMessage("Expected to get group 'lowercase' from the first match, but subject was not matched at all");

        // when
        pattern('[A-Z](?<lowercase>[a-z]+)?')
            ->match('123')
            ->group('lowercase')
            ->forFirst(function () {
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
            ->forFirst(function () {
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
        $this->expectExceptionMessage("Expected to get group 'lowercase' from the first match, but subject was not matched at all");

        // when
        pattern('[A-Z](?<lowercase>[a-z]+)?')
            ->match('123')
            ->group('lowercase')
            ->forFirst(function () {
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
            ->match('L Three Four')
            ->group('lowercase')
            ->forFirst(function () {
            })
            ->orThrow(CustomException::class);
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
            ->forFirst(function () {
            })
            ->orReturn('');
    }
}
