<?php
namespace Test\Unit\TRegx\SafeRegex\Errors\Errors;

use PHPUnit\Framework\TestCase;
use TRegx\SafeRegex\Errors\Errors\CompileError;
use TRegx\SafeRegex\Errors\Errors\OvertriggerCompileError;
use TRegx\SafeRegex\Errors\Errors\StandardCompileError;

class CompileErrorTest extends TestCase
{
    /**
     * @test
     */
    public function shouldGetLast_standard()
    {
        // given
        if (!function_exists('error_clear_last')) {
            $this->markTestSkipped('Only for PHP 7.0 +, with error_clear_last() method');
        }

        // when
        $error = CompileError::getLast();

        // then
        $this->assertInstanceOf(StandardCompileError::class, $error);
    }

    /**
     * @test
     */
    public function shouldGetLast_overtrigger()
    {
        // given
        if (function_exists('error_clear_last')) {
            $this->markTestSkipped('For PHP later than 5.6, with error_clear_last() method');
        }

        // when
        $error = CompileError::getLast();

        // then
        $this->assertInstanceOf(OvertriggerCompileError::class, $error);
    }
}
