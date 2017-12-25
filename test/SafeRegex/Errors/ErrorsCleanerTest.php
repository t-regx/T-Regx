<?php
namespace Test\SafeRegex\Errors;

use PHPUnit\Framework\TestCase;
use SafeRegex\Errors\ErrorsCleaner;
use Test\Warnings;

class ErrorsCleanerTest extends TestCase
{
    use Warnings;

    /**
     * @test
     */
    public function shouldClearCompileError()
    {
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
