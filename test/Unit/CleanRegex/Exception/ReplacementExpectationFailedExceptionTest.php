<?php
namespace Test\Unit\TRegx\CleanRegex\Exception;

use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Exception\ReplacementExpectationFailedException;

/**
 * @covers \TRegx\CleanRegex\Exception\ReplacementExpectationFailedException
 */
class ReplacementExpectationFailedExceptionTest extends TestCase
{
    /**
     * @test
     */
    public function shouldGetParameters_insufficient(): void
    {
        // given
        $exception = ReplacementExpectationFailedException::insufficient(4, 3, '');

        // when
        $expected = $exception->getExpected();
        $replaced = $exception->getReplaced();

        // then
        $this->assertSame(3, $expected);
        $this->assertSame(4, $replaced);
    }

    /**
     * @test
     */
    public function shouldSuperfluous(): void
    {
        // given
        $exception = ReplacementExpectationFailedException::superfluous(4, 3, '');

        // when
        $expected = $exception->getExpected();
        $replaced = $exception->getReplaced();

        // then
        $this->assertSame(3, $expected);
        $this->assertSame(4, $replaced);
    }
}
