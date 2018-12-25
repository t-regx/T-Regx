<?php
namespace Test\Unit\TRegx\SafeRegex\Exception;

use PHPUnit\Framework\TestCase;
use TRegx\SafeRegex\Exception\SuspectedReturnSafeRegexException;

class SuspectedReturnSafeRegexExceptionTest extends TestCase
{
    public function testGetters()
    {
        // given
        $exception = new SuspectedReturnSafeRegexException('method', 'value');

        // when
        $value = $exception->getReturnValue();
        $method = $exception->getInvokingMethod();

        // then
        $this->assertEquals('value', $value);
        $this->assertEquals('method', $method);
    }
}
