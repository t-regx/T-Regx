<?php
namespace TRegx\CleanRegex\Replace\NonReplaced;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Exception\CleanRegex\NotMatched\NonReplaced\NonMatchedMessage;

class ThrowStrategyTest extends TestCase
{
    /**
     * @test
     */
    public function shouldThrow()
    {
        // given
        $strategy = new ThrowStrategy(InvalidArgumentException::class, new NonMatchedMessage());

        // then
        $this->expectException(InvalidArgumentException::class);

        // when
        $strategy->substitute('');
    }
}
