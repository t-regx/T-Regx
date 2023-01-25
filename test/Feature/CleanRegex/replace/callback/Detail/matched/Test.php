<?php
namespace Test\Feature\CleanRegex\replace\callback\Detail\matched;

use PHPUnit\Framework\TestCase;
use Test\Utils\Functions;
use TRegx\CleanRegex\Exception\NonexistentGroupException;
use TRegx\CleanRegex\Pattern;

class Test extends TestCase
{
    /**
     * @test
     */
    public function shouldGroupNotBeMatched()
    {
        // given
        Pattern::of('(Foo)?(Bar)')->replace('Bar')->callback(Functions::out($detail, ''));
        // when, then
        $this->assertFalse($detail->matched(1));
    }

    /**
     * @test
     */
    public function shouldEmptyGroupBeMatched()
    {
        // given
        Pattern::of('()')->replace('')->callback(Functions::out($detail, ''));
        // when, then
        $this->assertTrue($detail->matched(1));
    }

    /**
     * @test
     */
    public function shouldGroupBeMatched()
    {
        // given
        Pattern::of('(Foo)')->replace('Foo')->callback(Functions::out($detail, ''));
        // when, then
        $this->assertTrue($detail->matched(1));
    }

    /**
     * @test
     */
    public function shouldThrowForNonexistentGroup()
    {
        // given
        Pattern::of('Foo')->replace('Foo')->callback(Functions::out($detail, ''));
        // then
        $this->expectException(NonexistentGroupException::class);
        $this->expectExceptionMessage("Nonexistent group: 'missing'");
        // when
        $detail->matched('missing');
    }

    /**
     * @test
     */
    public function shouldThrowForInvalidGroupName()
    {
        // given
        Pattern::of('Foo')->replace('Foo')->callback(Functions::out($detail, ''));
        // then
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage("Group name must be an alphanumeric string, not starting with a digit, but '2group' given");
        // when
        $detail->matched('2group');
    }

    /**
     * @test
     */
    public function shouldThrowForInvalidGroupIndex()
    {
        // given
        Pattern::of('Foo')->replace('Foo')->callback(Functions::out($detail, ''));
        // then
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Group index must be a non-negative integer, but -1 given');
        // when
        $detail->matched(-1);
    }

    /**
     * @test
     */
    public function shouldLastGroupBeMatched()
    {
        // given
        Pattern::of('(Foo)(Bar)')->replace('FooBar')->callback(Functions::out($detail, ''));
        // when, then
        $this->assertTrue($detail->matched(2));
    }

    /**
     * @test
     */
    public function shouldLastGroupNotBeMatched()
    {
        // given
        Pattern::of('(Foo)(Bar)?')->replace('Foo')->callback(Functions::out($detail, ''));
        // when, then
        $this->assertFalse($detail->matched(2));
    }
}
