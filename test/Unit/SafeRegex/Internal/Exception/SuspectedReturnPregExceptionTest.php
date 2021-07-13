<?php
namespace Test\Unit\TRegx\SafeRegex\Internal\Exception;

use PHPUnit\Framework\TestCase;
use TRegx\SafeRegex\Exception\SuspectedReturnPregException;

/**
 * @covers \TRegx\SafeRegex\Exception\SuspectedReturnPregException
 */
class SuspectedReturnPregExceptionTest extends TestCase
{
    public function testGetters()
    {
        // given
        $exception = new SuspectedReturnPregException('method', '/pattern/', true);

        // when
        $method = $exception->getInvokingMethod();
        $pattern = $exception->getPregPattern();
        $value = $exception->getReturnValue();

        // then
        $this->assertSame('method', $method);
        $this->assertSame('/pattern/', $pattern);
        $this->assertTrue($value);
    }

    public function shouldAcceptPatternsAsArray()
    {
        // given
        $exception = new SuspectedReturnPregException('', ['/foo/', '/bar/'], null);

        // when
        $pattern = $exception->getPregPattern();

        // then
        $this->assertSame(['/foo/', '/bar/'], $pattern);
    }
}
