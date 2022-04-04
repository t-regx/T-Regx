<?php
namespace Test\Feature\TRegx\CleanRegex\Match\group\findFirst;

use PHPUnit\Framework\TestCase;
use Test\Utils\ExampleException;
use Test\Utils\Functions;
use TRegx\CleanRegex\Exception\GroupNotMatchedException;
use TRegx\CleanRegex\Exception\NonexistentGroupException;
use TRegx\CleanRegex\Exception\SubjectNotMatchedException;
use TRegx\CleanRegex\Match\Details\Group\Group;
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
            ->findFirst(Functions::constant('result'))
            ->orThrow();

        // then
        $this->assertSame('result', $result);
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
            ->findFirst(function (Group $group) {
                $this->assertSame('omputer', $group->text());
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
            ->findFirst(function (Group $group) {
                $this->assertSame(['omputer', null, 'hree', 'our'], $group->all());
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
            ->findFirst(function (Group $group) {
                $this->assertSame('', $group->text());
            })
            ->orThrow();
    }

    /**
     * @test
     */
    public function shouldNotGetUnmatchedGroup()
    {
        // when
        pattern('Foo(Bar)?(Car)')
            ->match('FooCar')
            ->group(1)
            ->findFirst(Functions::fail())
            ->orElse(Functions::pass());
    }

    /**
     * @test
     */
    public function shouldThrow_unmatchedSubject()
    {
        // then
        $this->expectException(SubjectNotMatchedException::class);
        $this->expectExceptionMessage("Expected to get group #0 from the first match, but subject was not matched at all");

        // when
        pattern('Foo')
            ->match('123')
            ->group(0)
            ->findFirst(Functions::fail())
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
            ->findFirst(Functions::fail())
            ->orThrow();
    }

    /**
     * @test
     */
    public function shouldThrow_unmatchedSubject_customException()
    {
        // then
        $this->expectException(ExampleException::class);

        // when
        pattern('Foo')
            ->match('123')
            ->group(0)
            ->findFirst(Functions::fail())
            ->orThrow(new ExampleException());
    }

    /**
     * @test
     */
    public function shouldThrow_unmatchedGroup_customException()
    {
        // then
        $this->expectException(ExampleException::class);

        // when
        pattern('[A-Z](?<lowercase>[a-z]+)?')
            ->match('L')
            ->group('lowercase')
            ->findFirst(Functions::fail())
            ->orThrow(new ExampleException());
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
            ->findFirst(Functions::fail())
            ->orElse(function (NotMatched $notMatched) {
                $this->assertSame(['one', 'two'], $notMatched->groupNames());
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
            ->findFirst(Functions::fail())
            ->orElse(function (NotMatched $notMatched) {
                $this->assertSame(['one'], $notMatched->groupNames());
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
            ->findFirst(Functions::fail())
            ->orReturn('');
    }
}
