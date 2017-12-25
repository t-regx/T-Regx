<?php
namespace Test\SafeRegex\Errors;

use PHPUnit\Framework\TestCase;
use SafeRegex\Errors\ErrorsCleaner;

class ErrorsCleanerTest extends TestCase
{
    /**
     * @test
     */
    public function shouldClearPhpError()
    {
        // given
        $cleaner = new ErrorsCleaner();
        $this->causePhpError();

        // when
        $cleaner->clear();

        // then
        $error = error_get_last();
        $this->assertNull($error);
    }

    /**
     * @test
     */
    public function shouldClearPregError()
    {
        // given
        $cleaner = new ErrorsCleaner();
        $this->causePregError();

        // when
        $cleaner->clear();

        // then
        $error = preg_last_error();
        $this->assertEquals(PREG_NO_ERROR, $error);
    }

    private function causePhpError()
    {
        @preg_match('/asd', '');
    }

    private function causePregError()
    {
        preg_match('/bad.utf8/u', "\xa0\xa1");
    }
}
