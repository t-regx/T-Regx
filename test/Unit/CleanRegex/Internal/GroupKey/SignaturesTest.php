<?php
namespace Test\Unit\TRegx\CleanRegex\Internal\GroupKey;

use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Exception\InternalCleanRegexException;
use TRegx\CleanRegex\Internal\GroupKey\ArraySignatures;
use TRegx\CleanRegex\Internal\GroupKey\GroupIndex;
use TRegx\CleanRegex\Internal\GroupKey\GroupName;
use TRegx\CleanRegex\Internal\GroupKey\GroupSignature;

/**
 * @covers \TRegx\CleanRegex\Internal\GroupKey\ArraySignatures
 */
class SignaturesTest extends TestCase
{
    /**
     * @test
     */
    public function shouldGetSignatureByName()
    {
        // given
        $groups = new ArraySignatures([0, 'first', 1, 2, 'third', 3, 4, 'fifth', 5]);

        // when
        $wiseGroup = $groups->signature(new GroupName('third'));

        // then
        $this->assertEquals(new GroupSignature(3, 'third'), $wiseGroup);
    }

    /**
     * @test
     */
    public function shouldGetWiseGroupByIndex()
    {
        // given
        $groups = new ArraySignatures([0, 'first', 1, 2, 'third', 3, 4, 'fifth', 5]);

        // when
        $wiseGroup = $groups->signature(new GroupIndex(5));

        // then
        $this->assertEquals(new GroupSignature(5, 'fifth'), $wiseGroup);
    }

    /**
     * @test
     */
    public function shouldAssignNullNameToUnnamtedGroup()
    {
        // given
        $groups = new ArraySignatures([0, 'first', 1, 2, 'third']);

        // when
        $wiseGroup = $groups->signature(new GroupIndex(2));

        // then
        $this->assertEquals(new GroupSignature(2, null), $wiseGroup);
    }

    /**
     * @test
     */
    public function shouldAssignNullNameToWholeMatch()
    {
        // given
        $groups = new ArraySignatures([0, 'first', 1]);

        // when
        $wiseGroup = $groups->signature(new GroupIndex(0));

        // then
        $this->assertEquals(new GroupSignature(0, null), $wiseGroup);
    }

    /**
     * @test
     */
    public function shouldRaiseForMalformedGroupKeys()
    {
        // given
        $groups = new ArraySignatures([0, 'unassigned']);

        // then
        $this->expectException(InternalCleanRegexException::class);

        // when
        $groups->signature(new GroupName('unassigned'));
    }
}
