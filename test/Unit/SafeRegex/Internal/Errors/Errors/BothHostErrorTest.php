<?php
namespace Test\Unit\SafeRegex\Internal\Errors\Errors;

use PHPUnit\Framework\TestCase;
use Test\Fakes\SafeRegex\Internal\Errors\Errors\ClearableError;
use Test\Fakes\SafeRegex\Internal\Errors\Errors\JitStackError;
use TRegx\SafeRegex\Exception\JitStackLimitException;
use TRegx\SafeRegex\Internal\Errors\Errors\BothHostError;
use TRegx\SafeRegex\Internal\Errors\Errors\IrrelevantCompileError;
use TRegx\SafeRegex\Internal\Errors\Errors\RuntimeError;

/**
 * @covers \TRegx\SafeRegex\Internal\Errors\Errors\BothHostError
 */
class BothHostErrorTest extends TestCase
{
    /**
     * @test
     */
    public function shouldAlwaysBeTreatedAsOccurred(): void
    {
        // given
        $hostError = new BothHostError(new IrrelevantCompileError(), new RuntimeError(2));
        // when
        $occurred = $hostError->occurred();
        // then
        $this->assertTrue($occurred);
    }

    /**
     * @test
     */
    public function shouldClear(): void
    {
        // given
        $compile = new ClearableError();
        $runtime = new ClearableError();
        $hostError = new BothHostError($compile, $runtime);
        // when
        $hostError->clear();
        // then
        $this->assertTrue($compile->cleared());
        $this->assertTrue($runtime->cleared());
    }

    /**
     * @test
     */
    public function shouldGetSafeRegexpException(): void
    {
        // given
        $hostError = new BothHostError(new JitStackError(), new ClearableError());
        // when
        $result = $hostError->getSafeRegexpException('method_name', '/foo/');
        // then
        $this->assertEquals(new JitStackLimitException('/foo/', 'method_name', 0, ''), $result);
    }
}
