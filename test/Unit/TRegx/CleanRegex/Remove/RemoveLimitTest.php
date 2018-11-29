<?php
namespace Test\Unit\TRegx\CleanRegex\Remove;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Remove\RemoveLimit;

class RemoveLimitTest extends TestCase
{
    /**
     * @test
     */
    public function shouldLimitFirst()
    {
        // given
        $limit = new RemoveLimit(function (int $limit) {
            // then
            $this->assertEquals(1, $limit);
            return '';
        });

        // when
        $limit->first();
    }

    /**
     * @test
     */
    public function shouldLimitAll()
    {
        // given
        $limit = new RemoveLimit(function (int $limit) {
            // then
            $this->assertEquals(-1, $limit);
            return '';
        });

        // when
        $limit->all();
    }

    /**
     * @test
     */
    public function shouldLimitOnly()
    {
        // given
        $limit = new RemoveLimit(function (int $limit) {
            // then
            $this->assertEquals(20, $limit);
            return '';
        });

        // when
        $limit->only(20);
    }

    /**
     * @test
     */
    public function shouldThrowOnNegativeLimit()
    {
        // given
        $limit = new RemoveLimit(function () {
            return '';
        });

        // then
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Negative limit -2');

        // when
        $limit->only(-2);
    }
}
