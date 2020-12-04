<?php
namespace Test\Functional\TRegx\SafeRegex\Guard;

use Exception;
use PHPUnit\Framework\TestCase;
use Test\Utils\Functions;
use Test\Warnings;
use TRegx\SafeRegex\Errors\Errors\EmptyHostError;
use TRegx\SafeRegex\Errors\ErrorsCleaner;
use TRegx\SafeRegex\Exception\CompilePregException;
use TRegx\SafeRegex\Exception\RuntimePregException;
use TRegx\SafeRegex\Guard\GuardedExecution;

class GuardedExecutionTest extends TestCase
{
    use Warnings;

    /**
     * @test
     */
    public function shouldCatchRuntimeWarningWhenInvoking()
    {
        // then
        $this->expectException(RuntimePregException::class);
        $this->expectExceptionMessage('After invoking preg_match(), preg_last_error() returned PREG_BAD_UTF8_ERROR.');

        // when
        GuardedExecution::invoke('preg_match', '', function () {
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
        $this->expectException(CompilePregException::class);
        $this->expectExceptionMessage("No ending delimiter '/' found");

        // when
        GuardedExecution::invoke('preg_match', '', function () {
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
        GuardedExecution::invoke('preg_match', '', Functions::throws(new Exception('Rethrown exception')));
    }

    /**
     * @test
     */
    public function shouldInvokeReturnResult()
    {
        // when
        $result = GuardedExecution::invoke('preg_match', '', Functions::constant(13));

        // then
        $this->assertEquals(13, $result);
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
    public function shouldSilenceAnException()
    {
        // given
        $errorsCleaner = new ErrorsCleaner();

        // when
        $silenced = GuardedExecution::silenced('preg_match', function () {
            $this->causeCompileWarning();
        });

        $error = $errorsCleaner->getError();

        // then
        $this->assertTrue($silenced);
        $this->assertFalse($error->occurred());
        $this->assertInstanceOf(EmptyHostError::class, $error);
    }

    /**
     * @test
     */
    public function shouldNotSilenceAnException()
    {
        // given
        $errorsCleaner = new ErrorsCleaner();

        // when
        $silenced = GuardedExecution::silenced('preg_match', Functions::constant(2)); // ...for this.

        $error = $errorsCleaner->getError();  // This method is being tested...

        // then
        $this->assertFalse($silenced);
        $this->assertFalse($error->occurred());
        $this->assertInstanceOf(EmptyHostError::class, $error);
    }

    /**
     * @test
     */
    public function shouldRethrowRuntime_withPregPattern()
    {
        // when
        try {
            GuardedExecution::invoke('preg_match', '/runtime/', function () {
                $this->causeRuntimeWarning();
                return false;
            });
        } catch (RuntimePregException $exception) {
            // then
            $this->assertEquals('/runtime/', $exception->getPregPattern());
        }
    }

    /**
     * @test
     */
    public function shouldRethrowCompile_withPregPattern()
    {
        // when
        try {
            GuardedExecution::invoke('preg_match', '/compile/', function () {
                $this->causeCompileWarning();
                return false;
            });
        } catch (CompilePregException $exception) {
            // then
            $this->assertEquals('/compile/', $exception->getPregPattern());
        }
    }

}
