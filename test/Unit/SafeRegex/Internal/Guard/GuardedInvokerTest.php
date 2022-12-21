<?php
namespace Test\Unit\SafeRegex\Internal\Guard;

use PHPUnit\Framework\TestCase;
use Test\Utils\Assertion\AssertsHasClass;
use Test\Utils\Functions;
use Test\Utils\Runtime\CausesWarnings;
use Test\Utils\TestCase\TestCasePasses;
use TRegx\Exception\MalformedPatternException;
use TRegx\SafeRegex\Exception\RuntimePregException;
use TRegx\SafeRegex\Internal\Guard\GuardedInvoker;
use TRegx\SafeRegex\Internal\Guard\Strategy\DefaultSuspectedReturnStrategy;
use TRegx\SafeRegex\preg;

/**
 * @covers \TRegx\SafeRegex\Internal\Guard\GuardedInvoker
 */
class GuardedInvokerTest extends TestCase
{
    use CausesWarnings, AssertsHasClass, TestCasePasses;

    /**
     * @test
     */
    public function shouldNotCatchException()
    {
        // given
        $invoker = new GuardedInvoker('preg_match', '/p/', Functions::constant(13), new DefaultSuspectedReturnStrategy());
        // when
        [$result, $exception] = $invoker->catch();
        // then
        $this->assertSame(13, $result);
        $this->assertNull($exception);
    }

    /**
     * @test
     */
    public function shouldCatchRuntimeWarning()
    {
        // given
        $invoker = new GuardedInvoker('preg_match', '/p/', function () {
            $this->causeRuntimeWarning();
            return 14;
        }, new DefaultSuspectedReturnStrategy());

        // when
        [$result, $exception] = $invoker->catch();

        // then
        $this->assertSame(14, $result);
        $this->assertInstanceOf(RuntimePregException::class, $exception);
        $this->assertSame("/p/", $exception->getPregPattern());
    }

    /**
     * @test
     */
    public function shouldCatchMalformedWarning()
    {
        // given
        $invoker = new GuardedInvoker('preg_match', '/p/', function () {
            $this->causeMalformedPatternWarning();
            return 15;
        }, new DefaultSuspectedReturnStrategy());

        // when
        [$result, $exception] = $invoker->catch();

        // then
        $this->assertSame(15, $result);
        $this->assertInstanceOf(MalformedPatternException::class, $exception);
        $this->assertSame("/p/", $exception->getPregPattern());
    }

    /**
     * @test
     */
    public function shouldReturnResult()
    {
        // given
        $invoker = new GuardedInvoker('preg_match', '/p/', Functions::constant(16), new DefaultSuspectedReturnStrategy());

        // when
        [$result, $exception] = $invoker->catch();

        // then
        $this->assertSame(16, $result);
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
        try {
            $obsoleteWarning();
        } catch (\Throwable $ignored) {
        }
        // when
        preg::match('/foo/', 'foo');
        // then
        $this->pass();
    }

    /**
     * @test
     * @dataProvider possibleObsoleteWarnings
     * @param callable $obsoleteWarning
     */
    public function shouldNotLeaveOutWarnings(callable $obsoleteWarning)
    {
        // given
        \error_clear_last();
        // when
        try {
            $obsoleteWarning();
        } catch (\Throwable $ignored) {
        }
        // then
        $this->assertNull(\error_get_last());
        $this->assertSame(\PREG_NO_ERROR, \preg_last_error());
    }

    public function possibleObsoleteWarnings(): array
    {
        return [
            [function () {
                preg::match('/pattern/u', "\xc3\x28");
            }],
            [function () {
                preg::match('/unclosed pattern', '');
            }],
            [function () {
                preg::match('/+/', '');
            }],
        ];
    }

    /**
     * @test
     */
    public function shouldNotSilenceExceptions()
    {
        // given
        $invoker = new GuardedInvoker('preg_match', '/p/', Functions::throws(new \Exception("For Frodo")), new DefaultSuspectedReturnStrategy());

        // then
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("For Frodo");

        // when
        $invoker->catch();
    }
}
