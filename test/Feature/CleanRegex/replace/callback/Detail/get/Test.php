<?php
namespace Test\Feature\CleanRegex\replace\callback\Detail\get;

use PHPUnit\Framework\TestCase;
use Test\Utils\Functions;
use TRegx\CleanRegex\Exception\GroupNotMatchedException;
use TRegx\CleanRegex\Exception\NonexistentGroupException;

class Test extends TestCase
{
    /**
     * @test
     */
    public function shouldGet_MatchedGroup()
    {
        // when
        pattern('([A-Z])[a-z]+')
            ->replace('Stark, Eddard')
            ->callback(Functions::collect($details, ''));
        // then
        [$stark, $eddard] = $details;
        $this->assertSame('S', $stark->get(1));
        $this->assertSame('E', $eddard->get(1));
    }

    /**
     * @test
     */
    public function shouldThrow_forUnmatchedGroup_byIndex()
    {
        // given
        pattern('Foo(Bar){0}')->replace('Foo')->callback(Functions::outLast($detail, ''));
        // then
        $this->expectException(GroupNotMatchedException::class);
        $this->expectExceptionMessage("Expected to get group #1, but the group was not matched");
        // when
        $detail->get(1);
    }

    /**
     * @test
     */
    public function shouldThrow_forUnmatchedGroup_byName()
    {
        // given
        pattern('(?<domain>domain){0}')->replace('foo')->callback(Functions::outLast($detail, ''));
        // then
        $this->expectException(GroupNotMatchedException::class);
        $this->expectExceptionMessage("Expected to get group 'domain', but the group was not matched");
        // when
        $detail->get('domain');
    }

    /**
     * @test
     */
    public function shouldThrowForInvalidGroup()
    {
        // given
        pattern('Foo')->replace('Foo')->first()->callback(Functions::outLast($detail, ''));
        // then
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage("Group name must be an alphanumeric string, not starting with a digit, but '2group' given");
        // when
        $detail->get('2group');
    }

    /**
     * @test
     */
    public function shouldThrowForInvalidGroup_NumericString()
    {
        // given
        pattern('Foo')->replace('Foo')->first()->callback(Functions::outLast($detail, ''));
        // then
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage("Group name must be an alphanumeric string, not starting with a digit, but '0' given");
        // when
        $detail->get('0');
    }

    /**
     * @test
     */
    public function shouldGetLastGroup()
    {
        // when
        pattern('Bar(Foo)')->replace('BarFoo')->first()->callback(Functions::outLast($detail, ''));
        // then
        $this->assertSame('Foo', $detail->get(1));
    }

    /**
     * @test
     */
    public function shouldGetLastGroupEmpty()
    {
        // when
        pattern('Bar()')->replace('Bar')->first()->callback(Functions::outLast($detail, ''));
        // then
        $this->assertSame('', $detail->get(1));
    }

    /**
     * @test
     */
    public function shouldGetMiddleGroupNotMatched()
    {
        // given
        pattern('Foo(Bar){0}(Cat)')->replace('FooCat')->first()->callback(Functions::outLast($detail, ''));
        // then
        $this->expectException(GroupNotMatchedException::class);
        $this->expectExceptionMessage('Expected to get group #1, but the group was not matched');
        // when
        $detail->get(1);
    }

    /**
     * @test
     */
    public function shouldGetMiddleGroupEmpty()
    {
        // when
        pattern('Foo()(Bar)')->replace('FooBar')->first()->callback(Functions::outLast($detail, ''));
        // then
        $this->assertSame('', $detail->get(1));
    }

    /**
     * @test
     */
    public function shouldThrowForNonExistentGroup()
    {
        // given
        pattern('Daenerys')->replace('Daenerys')->first()->callback(Functions::outLast($detail, ''));
        // then
        $this->expectException(NonexistentGroupException::class);
        $this->expectExceptionMessage('Nonexistent group: #1');
        // when
        $detail->get(1);
    }

    /**
     * @test
     */
    public function shouldThrowForNonExistentGroupNamed()
    {
        // given
        pattern('Daenerys')->replace('Daenerys')->first()->callback(Functions::outLast($detail, ''));
        // then
        $this->expectException(NonexistentGroupException::class);
        $this->expectExceptionMessage("Nonexistent group: 'missing'");
        // when
        $detail->get('missing');
    }
}
