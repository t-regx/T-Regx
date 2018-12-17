<?php
namespace Test\Unit\TRegx\SafeRegex\Exception;

use PHPUnit\Framework\TestCase;
use TRegx\SafeRegex\Exception\CompileSafeRegexException;
use TRegx\SafeRegex\PhpError;

class CompileSafeRegexExceptionTest extends TestCase
{
    public function testGetters()
    {
        // given
        $exception = new CompileSafeRegexException('', '', new PhpError(2, 'message', '', 0), 'error');

        // when
        $error = $exception->getError();
        $errorName = $exception->getErrorName();
        $errorMessage = $exception->getPregErrorMessage();

        // then
        $this->assertEquals(2, $error);
        $this->assertEquals('error', $errorName);
        $this->assertEquals('message', $errorMessage);
    }

    /**
     * @test
     */
    public function shouldGet_invokingMessage()
    {
        // given
        $exception = new CompileSafeRegexException('preg_method', '', new PhpError(2, '', '', 0), '');

        // when
        $method = $exception->getInvokingMethod();

        // then
        $this->assertEquals('preg_method', $method);
    }
}
