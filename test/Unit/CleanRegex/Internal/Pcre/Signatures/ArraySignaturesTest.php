<?php
namespace Test\Unit\TRegx\CleanRegex\Internal\Pcre\Signatures;

use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Exception\InternalCleanRegexException;
use TRegx\CleanRegex\Internal\GroupKey\GroupIndex;
use TRegx\CleanRegex\Internal\GroupKey\GroupName;
use TRegx\CleanRegex\Internal\GroupKey\GroupSignature;
use TRegx\CleanRegex\Internal\Pcre\Signatures\ArraySignatures;

/**
 * @covers \TRegx\CleanRegex\Internal\Pcre\Signatures\ArraySignatures
 */
class ArraySignaturesTest extends TestCase
{
    /**
     * @test
     */
    public function shouldGetSignatureByName()
    {
        // given
        $signatures = new ArraySignatures([0, 'first', 1, 2, 'third', 3, 4, 'fifth', 5]);
        // when
        $signature = $signatures->signature(new GroupName('third'));
        // then
        $this->assertEquals(new GroupSignature(3, 'third'), $signature);
    }

    /**
     * @test
     */
    public function shouldGetSignatureByIndex()
    {
        // given
        $signatures = new ArraySignatures([0, 'first', 1, 2, 'third', 3, 4, 'fifth', 5]);
        // when
        $signature = $signatures->signature(new GroupIndex(5));
        // then
        $this->assertEquals(new GroupSignature(5, 'fifth'), $signature);
    }

    /**
     * @test
     */
    public function shouldAssignNullNameToUnnamedGroup()
    {
        // given
        $signatures = new ArraySignatures([0, 'first', 1, 2, 'third']);
        // when
        $signature = $signatures->signature(new GroupIndex(2));
        // then
        $this->assertEquals(new GroupSignature(2, null), $signature);
    }

    /**
     * @test
     */
    public function shouldAssignNullNameToWholeMatch()
    {
        // given
        $signatures = new ArraySignatures([0, 'first', 1]);
        // when
        $signature = $signatures->signature(new GroupIndex(0));
        // then
        $this->assertEquals(new GroupSignature(0, null), $signature);
    }

    /**
     * @test
     */
    public function shouldRaiseForMalformedGroupKeys()
    {
        // given
        $signatures = new ArraySignatures([0, 'unassigned']);
        // then
        $this->expectException(InternalCleanRegexException::class);
        // when
        $signatures->signature(new GroupName('unassigned'));
    }
}
