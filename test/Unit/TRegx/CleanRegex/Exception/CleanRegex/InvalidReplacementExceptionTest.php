<?php
namespace Test\Unit\TRegx\CleanRegex\Exception\CleanRegex;

use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Exception\CleanRegex\InvalidReplacementException;
use TRegx\CleanRegex\Pattern;

class InvalidReplacementExceptionTest extends TestCase
{
    /**
     * @test
     * @dataProvider objectsAndMessages
     * @param mixed  $replacement
     * @param string $expectedMessage
     */
    public function shouldGetMessageWithType($replacement, string $expectedMessage)
    {
        // given
        $exception = new InvalidReplacementException($replacement);

        // when
        $actualMessage = $exception->getMessage();

        // then
        $this->assertEquals($expectedMessage, $actualMessage);
    }

    public function objectsAndMessages()
    {
        return [
            [true, 'Invalid callback() callback return type. Expected string, but boolean (true) given'],
            [new Pattern(''), 'Invalid callback() callback return type. Expected string, but TRegx\CleanRegex\Pattern given'],
        ];
    }
}
