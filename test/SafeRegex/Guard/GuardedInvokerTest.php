<?php
namespace Test\SafeRegex\Guard;

use PHPUnit\Framework\TestCase;
use Test\Warnings;
use TRegx\SafeRegex\Exception\CompileSafeRegexException;
use TRegx\SafeRegex\Exception\RuntimeSafeRegexException;
use TRegx\SafeRegex\Guard\GuardedInvoker;

class GuardedInvocationTest extends TestCase
{
    use Warnings;

    /**
     * @test
     */
    public function shouldNotCatchException()
    {
        // given
        $invoker = new GuardedInvoker('preg_match', function () {
            return 13;
        });

        // when
        $invocation = $invoker->catch();

        // then
        $this->assertNull($invocation->getException());
        $this->assertFalse($invocation->hasException());
    }

    /**
     * @test
     */
    public function shouldCatchRuntimeWarning()
    {
        // given
        $invoker = new GuardedInvoker('preg_match', function () {
            $this->causeRuntimeWarning();
            return false;
        });

        // when
        $invocation = ($invoker)->catch();

        // then
        $this->assertTrue($invocation->hasException());
        $this->assertInstanceOf(RuntimeSafeRegexException::class, $invocation->getException());
    }

    /**
     * @test
     */
    public function shouldCatchCompileWarning()
    {
        // given
        $invoker = new GuardedInvoker('preg_match', function () {
            $this->causeCompileWarning();
            return false;
        });

        // when
        $invocation = $invoker->catch();

        // then
        $this->assertTrue($invocation->hasException());
        $this->assertInstanceOf(CompileSafeRegexException::class, $invocation->getException());
    }

    /**
     * @test
     */
    public function shouldCatchReturnResult()
    {
        // given
        $invoker = new GuardedInvoker('preg_match', function () {
            return 13;
        });

        // when
        $invocation = $invoker->catch();

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
        $obsoleteWarning();

        // when
        $callback = function () {
            return 1;
        };
        $invocation = (new GuardedInvoker('preg_match', $callback))->catch();

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
}
