<?php
namespace TRegx\CleanRegex\Replace\NonReplaced;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Internal\Exception\Messages\NonMatchedMessage;

class CustomThrowStrategyTest extends TestCase
{
    /**
     * @test
     */
    public function shouldThrow()
    {
        // given
        $strategy = new CustomThrowStrategy(InvalidArgumentException::class, new NonMatchedMessage());

        // then
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('expected to replace, but didn\'t'); // TODO fix the message

        // when
        $strategy->substitute('');
    }
}
