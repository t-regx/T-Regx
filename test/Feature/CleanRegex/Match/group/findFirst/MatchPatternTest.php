<?php
namespace Test\Feature\CleanRegex\Match\group\findFirst;

use PHPUnit\Framework\TestCase;
use Test\Utils\Assertion\AssertsOptionalEmpty;
use Test\Utils\Classes\ExampleException;
use Test\Utils\Functions;
use TRegx\CleanRegex\Exception\NonexistentGroupException;
use TRegx\CleanRegex\Match\Details\Group\Group;

class MatchPatternTest extends TestCase
{
    use AssertsOptionalEmpty;

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
            ->get();

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
            ->get();
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
            ->get();
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
            ->get();
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
    public function shouldReturnEmptyOptional_forUnmatchedSubject()
    {
        // when
        $optional = pattern('Foo')
            ->match('123')
            ->group(0)
            ->findFirst(Functions::fail());
        // then
        $this->assertOptionalEmpty($optional);
    }

    /**
     * @test
     */
    public function shouldThrow_unmatchedGroup()
    {
        // when
        $optional = pattern('Foo(?<group>Bar)?')
            ->match('Foo')
            ->group('group')
            ->findFirst(Functions::fail());
        // then
        $this->assertOptionalEmpty($optional);
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
    public function shouldPass_NotMatched_unmatchedGroup()
    {
        // when
        $optional = pattern('Foo(?<one>Bar)?')
            ->match('Foo')
            ->group(1)
            ->findFirst(Functions::fail());
        // then
        $this->assertOptionalEmpty($optional);
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
            ->findFirst(Functions::fail());
    }
}
