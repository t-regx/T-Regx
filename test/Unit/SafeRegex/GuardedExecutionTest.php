<?php
namespace Test\Unit\SafeRegex;

use Exception;
use PHPUnit\Framework\TestCase;
use SafeRegex\Exception\CompileSafeRegexException;
use SafeRegex\Exception\RuntimeSafeRegexException;
use SafeRegex\Guard\GuardedExecution;
use Test\Warnings;

class GuardedExecutionTest extends TestCase
{
    use Warnings;

    /**
     * @test
     */
    public function shouldNotCatchException()
    {
        // when
        $invocation = GuardedExecution::catch('preg_match', function () {
            return 13;
        });

        // then
        $this->assertNull($invocation->getException());
        $this->assertFalse($invocation->hasException());
    }

    public function possibleObsoleteWarnings()
    {
        return [
            [function () {
                $this->causeRuntimeWarning();
            }],
            [function () {
                $this->causeCompileWarning();
            }],
        ];
    }

    /**
     * @test
     */
    public function shouldCatchRuntimeWarning()
    {
        // when
        $invocation = GuardedExecution::catch('preg_match', function () {
            $this->causeRuntimeWarning();
            return false;
        });

        // then
        $this->assertTrue($invocation->hasException());
        $this->assertInstanceOf(RuntimeSafeRegexException::class, $invocation->getException());
    }

    /**
     * @test
     */
    public function shouldCatchCompileWarning()
    {
        // when
        $invocation = GuardedExecution::catch('preg_match', function () {
            $this->causeCompileWarning();
            return false;
        });

        // then
        $this->assertTrue($invocation->hasException());
        $this->assertInstanceOf(CompileSafeRegexException::class, $invocation->getException());
    }

    /**
     * @test
     */
    public function shouldCatchRuntimeWarningWhenInvoking()
    {
        // then
        $this->expectException(RuntimeSafeRegexException::class);

        // when
        GuardedExecution::invoke('preg_match', function () {
            $this->causeRuntimeWarning();
            return false;
        });
    }

    /**
     * @test
     */
    public function shouldCatchCompileWarningWhenInvoking()
    {
        // then
        $this->expectException(CompileSafeRegexException::class);

        // when
        GuardedExecution::invoke('preg_match', function () {
            $this->causeCompileWarning();
            return false;
        });
    }

    /**
     * @test
     */
    public function shouldRethrowException()
    {
        // then
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Rethrown exception');

        // when
        GuardedExecution::invoke('preg_match', function () {
            throw new Exception('Rethrown exception');
        });
    }

    /**
     * @test
     */
    public function shouldInvokeReturnResult()
    {
        // when
        $result = GuardedExecution::invoke('preg_match', function () {
            return 13;
        });

        // then
        $this->assertEquals(13, $result);
    }

    /**
     * @test
     */
    public function shouldCatchReturnResult()
    {
        // when
        $invocation = GuardedExecution::catch('preg_match', function () {
            return 13;
        });

        // then
        $this->assertEquals(13, $invocation->getResult());
    }

    /**
     * @test
     * @dataProvider possibleObsoleteWarnings
     * @param callable $obsoleteWarning
     */
    public function shouldIgnorePreviousWarnings(callable $obsoleteWarning)
    {
        // given
        call_user_func($obsoleteWarning);

        // when
        $invocation = GuardedExecution::catch('preg_match', function () {
            return 1;
        });

        // then
        $this->assertNull($invocation->getException());
        $this->assertFalse($invocation->hasException());
    }

    /**
     * @test
     */
    public function shouldSilenceAnException()
    {
        // when
        $silenced = GuardedExecution::silenced('preg_match', function () {
            $this->causeCompileWarning();
        });

        // then
        $this->assertTrue($silenced);
    }

    /**
     * @test
     */
    public function shouldNotSilenceAnException()
    {
        // when
        $silenced = GuardedExecution::silenced('preg_match', function () {
            return 2;
        });

        // then
        $this->assertFalse($silenced);
    }
}
