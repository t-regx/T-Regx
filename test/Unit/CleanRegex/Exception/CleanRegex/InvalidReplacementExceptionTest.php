<?php
namespace Test\Unit\CleanRegex\Exception\CleanRegex;

use CleanRegex\Exception\CleanRegex\InvalidReplacementException;
use CleanRegex\Pattern;
use PHPUnit\Framework\TestCase;

class InvalidReplacementExceptionTest extends TestCase
{

    /**
     * @test
     * @dataProvider objectsAndMessages
     * @param mixed $replacement
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
            [true, 'Invalid replace callback return type. Expected string, but boolean (true) given'],
            [new Pattern(''), 'Invalid replace callback return type. Expected string, but CleanRegex\Pattern given'],
        ];
    }
}
