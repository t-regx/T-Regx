<?php
namespace Test\Unit\TRegx\CleanRegex\Exception\CleanRegex;

use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Exception\InvalidReplacementException;

/**
 * @covers \TRegx\CleanRegex\Exception\InvalidReplacementException
 */
class InvalidReplacementExceptionTest extends TestCase
{
    /**
     * @test
     */
    public function shouldGetMessageWithType()
    {
        // given
        $exception = new InvalidReplacementException(true);

        // when
        $message = $exception->getMessage();

        // then
        $this->assertSame('Invalid callback() callback return type. Expected string, but boolean (true) given', $message);
    }
}
