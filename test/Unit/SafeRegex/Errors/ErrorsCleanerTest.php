<?php
namespace Test\Unit\SafeRegex\Errors;

use PHPUnit\Framework\TestCase;
use SafeRegex\Errors\Errors\BothHostError;
use SafeRegex\Errors\Errors\CompileError;
use SafeRegex\Errors\Errors\EmptyHostError;
use SafeRegex\Errors\Errors\OvertriggerCompileError;
use SafeRegex\Errors\Errors\RuntimeError;
use SafeRegex\Errors\ErrorsCleaner;
use Test\Warnings;

class ErrorsCleanerTest extends TestCase
{
    use Warnings;

    /**
     * @test
     */
    public function shouldGetRuntimeError()
    {
        // given
        $cleaner = new ErrorsCleaner();
        $this->causeRuntimeWarning();

        // when
        $error = $cleaner->getError();

        // then
        $this->assertInstanceOf(RuntimeError::class, $error);
        $this->assertTrue($error->occurred());

        // cleanup
        $error->clear();
    }

    /**
     * @test
     */
    public function shouldGetCompileError()
    {
        // given
        $cleaner = new ErrorsCleaner();
        $this->causeCompileWarning();

        // when
        $error = $cleaner->getError();

        // then
        $this->assertInstanceOf(CompileError::class, $error);
        $this->assertTrue($error->occurred());

        // cleanup
        $error->clear();
    }

    /**
     * @test
     */
    public function shouldGetBothHostError()
    {
        // given
        $cleaner = new ErrorsCleaner();
        $this->causeCompileWarning();
        $this->causeRuntimeWarning();

        // when
        $error = $cleaner->getError();

        // then
        $this->assertInstanceOf(BothHostError::class, $error);
        $this->assertTrue($error->occurred());

        // cleanup
        $error->clear();
    }

    /**
     * @test
     */
    public function shouldGetEmptyHostError()
    {
        // given
        $cleaner = new ErrorsCleaner();

        // when
        $error = $cleaner->getError();

        // then
        $this->assertInstanceOf(EmptyHostError::class, $error);
        $this->assertFalse($error->occurred());

        // cleanup
        $error->clear();
    }

    /**
     * @test
     */
    public function shouldClearCompileError()
    {
        if (!function_exists('error_clear_last')) {
            $this->markTestSkipped('Only for PHP 7.0 +, with error_clear_last() method');
        }

        // given
        $cleaner = new ErrorsCleaner();
        $this->causeCompileWarning();

        // when
        $cleaner->clear();

        // then
        $error = error_get_last();
        $this->assertNull($error);
    }

    /**
     * @test
     */
    public function shouldClearCompileErrorPhpPre7()
    {
        if (function_exists('error_clear_last')) {
            $this->markTestSkipped('For PHP 5.6 and earlier, without error_clear_last() method');
        }

        // given
        $cleaner = new ErrorsCleaner();
        $this->causeCompileWarning();

        // when
        $cleaner->clear();

        // then
        $error = error_get_last();
        $this->assertNotNull($error);
        $this->assertEquals(OvertriggerCompileError::OVERTRIGGER_MESSAGE, $error['message']);
    }

    /**
     * @test
     */
    public function shouldClearRuntimeError()
    {
        // given
        $cleaner = new ErrorsCleaner();
        $this->causeRuntimeWarning();

        // when
        $cleaner->clear();

        // then
        $error = preg_last_error();
        $this->assertEquals(PREG_NO_ERROR, $error);
    }
}
