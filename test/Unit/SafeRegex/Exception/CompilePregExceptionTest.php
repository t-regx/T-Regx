<?php
namespace Test\Unit\TRegx\SafeRegex\Exception;

use PHPUnit\Framework\TestCase;
use TRegx\SafeRegex\Exception\CompilePregException;
use TRegx\SafeRegex\PhpError;

class CompilePregExceptionTest extends TestCase
{
    public function testGetters()
    {
        // given
        $exception = new CompilePregException('', '', '', new PhpError(2, 'message', '', 0), 'error');

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
        $exception = new CompilePregException('preg_method', null, '', new PhpError(2, '', '', 0), '');

        // when
        $method = $exception->getInvokingMethod();

        // then
        $this->assertEquals('preg_method', $method);
    }

    /**
     * @test
     */
    public function shouldGet_pattern()
    {
        // given
        $exception = new CompilePregException('', '/pattern/', '', new PhpError(2, '', '', 0), '');

        // when
        $pattern = $exception->getPregPattern();

        // then
        $this->assertEquals('/pattern/', $pattern);
    }
}
