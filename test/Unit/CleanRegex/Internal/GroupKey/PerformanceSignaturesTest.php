<?php
namespace Test\Unit\TRegx\CleanRegex\Internal\GroupKey;

use PHPUnit\Framework\TestCase;
use Test\Fakes\CleanRegex\Internal\Model\GroupKeys;
use Test\Fakes\CleanRegex\Internal\Model\ThrowGroupAware;
use TRegx\CleanRegex\Exception\InternalCleanRegexException;
use TRegx\CleanRegex\Internal\GroupKey\GroupIndex;
use TRegx\CleanRegex\Internal\GroupKey\GroupName;
use TRegx\CleanRegex\Internal\GroupKey\GroupSignature;
use TRegx\CleanRegex\Internal\GroupKey\PerformanceSignatures;

/**
 * @covers \TRegx\CleanRegex\Internal\GroupKey\PerformanceSignatures
 */
class PerformanceSignaturesTest extends TestCase
{
    /**
     * @test
     */
    public function shouldGetGroupSignatureByName()
    {
        // given
        $performance = new PerformanceSignatures(new GroupKeys([0, 'first', 1]), new ThrowGroupAware());
        // when
        $signature = $performance->signature(new GroupName('first'));
        // then
        $this->assertEquals(new GroupSignature(1, 'first'), $signature);
    }

    /**
     * @test
     */
    public function shouldGetGroupSignatureByIndex()
    {
        // given
        $performance = new PerformanceSignatures(new GroupKeys([0, 'foo', 1]), new ThrowGroupAware());
        // when
        $signature = $performance->signature(new GroupIndex(1));
        // then
        $this->assertEquals(new GroupSignature(1, 'foo'), $signature);
    }

    /**
     * @test
     */
    public function shouldGetGroupSignatureForUnmatchedGroup()
    {
        // given
        $performance = new PerformanceSignatures(new GroupKeys([0, 1, 'foo']), new ThrowGroupAware());
        // when
        $signature = $performance->signature(new GroupIndex(1));
        // then
        $this->assertEquals(new GroupSignature(1, null), $signature);
    }

    /**
     * @test
     */
    public function shouldGetSignatureByIndexFromGroupAware()
    {
        // given
        $performance = new PerformanceSignatures(new GroupKeys([0]), new GroupKeys([0, 'foo', 1]));
        // when
        $signature = $performance->signature(new GroupIndex(1));
        // then
        $this->assertEquals(new GroupSignature(1, 'foo'), $signature);
    }

    /**
     * @test
     */
    public function shouldGetSignatureByIndexFromGroupAwareUnnamed()
    {
        // given
        $performance = new PerformanceSignatures(new GroupKeys([0]), new GroupKeys([0, 1, 'foo', 2]));
        // when
        $signature = $performance->signature(new GroupIndex(1));
        // then
        $this->assertEquals(new GroupSignature(1, null), $signature);
    }

    /**
     * @test
     */
    public function shouldGetSignatureByNameFromGroupAware()
    {
        // given
        $performance = new PerformanceSignatures(new GroupKeys([0]), new GroupKeys([0, 'foo', 1]));
        // when
        $signature = $performance->signature(new GroupName('foo'));
        // then
        $this->assertEquals(new GroupSignature(1, 'foo'), $signature);
    }

    /**
     * @test
     */
    public function shouldThrowForMissingGroupByName()
    {
        // given
        $performance = new PerformanceSignatures(new GroupKeys([0]), new GroupKeys([0, 'foo', 1]));
        // then
        $this->expectException(InternalCleanRegexException::class);
        // when
        $performance->signature(new GroupName('bar'));
    }

    /**
     * @test
     */
    public function shouldThrowForMissingGroupByIndex()
    {
        // given
        $performance = new PerformanceSignatures(new GroupKeys([0]), new GroupKeys([0, 'foo', 1]));
        // then
        $this->expectException(InternalCleanRegexException::class);
        // when
        $performance->signature(new GroupIndex(2));
    }
}
