<?php
namespace Test\Legacy\CleanRegex\Internal\Pcre\Signatures;

use PHPUnit\Framework\TestCase;
use Test\Fakes\CleanRegex\Internal\Model\ArrayGroupKeys;
use Test\Fakes\CleanRegex\Internal\Model\ThrowGroupAware;
use TRegx\CleanRegex\Exception\InternalCleanRegexException;
use TRegx\CleanRegex\Internal\GroupKey\GroupIndex;
use TRegx\CleanRegex\Internal\GroupKey\GroupName;
use TRegx\CleanRegex\Internal\GroupKey\GroupSignature;
use TRegx\CleanRegex\Internal\Pcre\Signatures\PerformanceSignatures;

/**
 * @covers \TRegx\CleanRegex\Internal\Pcre\Signatures\PerformanceSignatures
 */
class PerformanceSignaturesTest extends TestCase
{
    /**
     * @test
     */
    public function shouldGetGroupSignatureByName()
    {
        // given
        $signatures = new PerformanceSignatures(new ArrayGroupKeys([0, 'first', 1]), new ThrowGroupAware());
        // when
        $signature = $signatures->signature(new GroupName('first'));
        // then
        $this->assertEquals(new GroupSignature(1, 'first'), $signature);
    }

    /**
     * @test
     */
    public function shouldGetGroupSignatureByIndex()
    {
        // given
        $signatures = new PerformanceSignatures(new ArrayGroupKeys([0, 'foo', 1]), new ThrowGroupAware());
        // when
        $signature = $signatures->signature(new GroupIndex(1));
        // then
        $this->assertEquals(new GroupSignature(1, 'foo'), $signature);
    }

    /**
     * @test
     */
    public function shouldGetGroupSignatureForUnmatchedGroup()
    {
        // given
        $signatures = new PerformanceSignatures(new ArrayGroupKeys([0, 1, 'foo']), new ThrowGroupAware());
        // when
        $signature = $signatures->signature(new GroupIndex(1));
        // then
        $this->assertEquals(new GroupSignature(1, null), $signature);
    }

    /**
     * @test
     */
    public function shouldGetSignatureByIndexFromGroupAware()
    {
        // given
        $signatures = new PerformanceSignatures(new ArrayGroupKeys([0]), new ArrayGroupKeys([0, 'foo', 1]));
        // when
        $signature = $signatures->signature(new GroupIndex(1));
        // then
        $this->assertEquals(new GroupSignature(1, 'foo'), $signature);
    }

    /**
     * @test
     */
    public function shouldGetSignatureByIndexFromGroupAwareUnnamed()
    {
        // given
        $signatures = new PerformanceSignatures(new ArrayGroupKeys([0]), new ArrayGroupKeys([0, 1, 'foo', 2]));
        // when
        $signature = $signatures->signature(new GroupIndex(1));
        // then
        $this->assertEquals(new GroupSignature(1, null), $signature);
    }

    /**
     * @test
     */
    public function shouldGetSignatureByNameFromGroupAware()
    {
        // given
        $signatures = new PerformanceSignatures(new ArrayGroupKeys([0]), new ArrayGroupKeys([0, 'foo', 1]));
        // when
        $signature = $signatures->signature(new GroupName('foo'));
        // then
        $this->assertEquals(new GroupSignature(1, 'foo'), $signature);
    }

    /**
     * @test
     */
    public function shouldThrowForMissingGroupByName()
    {
        // given
        $signatures = new PerformanceSignatures(new ArrayGroupKeys([0]), new ArrayGroupKeys([0, 'foo', 1]));
        // then
        $this->expectException(InternalCleanRegexException::class);
        // when
        $signatures->signature(new GroupName('bar'));
    }

    /**
     * @test
     */
    public function shouldThrowForMissingGroupByIndex()
    {
        // given
        $signatures = new PerformanceSignatures(new ArrayGroupKeys([0]), new ArrayGroupKeys([0, 'foo', 1]));
        // then
        $this->expectException(InternalCleanRegexException::class);
        // when
        $signatures->signature(new GroupIndex(2));
    }
}
