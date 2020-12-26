<?php
namespace Test\Unit\TRegx\SafeRegex\Exception;

use PHPUnit\Framework\TestCase;
use TRegx\SafeRegex\Exception\SuspectedReturnPregException;

class SuspectedPregRegexExceptionTest extends TestCase
{
    public function testGetters()
    {
        // given
        $exception = new SuspectedReturnPregException('method', '/pattern/', 'value');

        // when
        $value = $exception->getReturnValue();
        $method = $exception->getInvokingMethod();
        $pattern = $exception->getPregPattern();

        // then
        $this->assertSame('value', $value);
        $this->assertSame('method', $method);
        $this->assertSame('/pattern/', $pattern);
    }
}
