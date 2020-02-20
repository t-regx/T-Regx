<?php
namespace Test\SafeRegex\Guard;

use PHPUnit\Framework\TestCase;
use Test\Warnings;
use TRegx\SafeRegex\Errors\ErrorsCleaner;
use TRegx\SafeRegex\Exception\CompilePregException;
use TRegx\SafeRegex\Exception\RuntimePregException;
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
        [$result, $exception] = $invoker->catch();

        // then
        $this->assertEquals(13, $result);
        $this->assertNull($exception);
    }

    /**
     * @test
     */
    public function shouldCatchRuntimeWarning()
    {
        // given
        $invoker = new GuardedInvoker('preg_match', function () {
            $this->causeRuntimeWarning();
            return 14;
        });

        // when
        [$result, $exception] = $invoker->catch();

        // then
        $this->assertEquals(14, $result);
        $this->assertInstanceOf(RuntimePregException::class, $exception);
    }

    /**
     * @test
     */
    public function shouldCatchCompileWarning()
    {
        // given
        $invoker = new GuardedInvoker('preg_match', function () {
            $this->causeCompileWarning();
            return 15;
        });

        // when
        [$result, $exception] = $invoker->catch();

        // then
        $this->assertEquals(15, $result);
        $this->assertInstanceOf(CompilePregException::class, $exception);
    }

    /**
     * @test
     */
    public function shouldReturnResult()
    {
        // given
        $invoker = new GuardedInvoker('preg_match', function () {
            return 16;
        });

        // when
        [$result, $exception] = $invoker->catch();

        // then
        $this->assertEquals(16, $result);
        $this->assertNull($exception);
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
        $invoker = new GuardedInvoker('preg_match', function () {
            return 17;
        });

        // when
        [$result, $exception] = $invoker->catch();

        // then
        $this->assertEquals(17, $result);
        $this->assertNull($exception);
    }

    /**
     * @test
     * @dataProvider possibleObsoleteWarnings
     * @param callable $obsoleteWarning
     */
    public function shouldNotLeaveOutWarnings(callable $obsoleteWarning)
    {
        // given
        $invoker = new GuardedInvoker('preg_match', function () use ($obsoleteWarning) {
            $obsoleteWarning();
        });

        // when
        $invoker->catch();

        // then
        $this->assertFalse((new ErrorsCleaner())->getError()->occurred());
    }

    public function possibleObsoleteWarnings(): array
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
    public function shouldNotSilenceExceptions()
    {
        // given
        $invoker = new GuardedInvoker('preg_match', function () {
            throw new \Exception("For Frodo");
        });

        // then
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("For Frodo");

        // when
        $invoker->catch();
    }
}
