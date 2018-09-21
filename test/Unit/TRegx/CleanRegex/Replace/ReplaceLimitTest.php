<?php
namespace Test\Unit\TRegx\CleanRegex\Replace;

use TRegx\CleanRegex\Internal\InternalPattern as Pattern;
use TRegx\CleanRegex\Replace\ReplaceLimit;
use TRegx\CleanRegex\Replace\ReplacePattern;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class ReplaceLimitTest extends TestCase
{
    /**
     * @test
     */
    public function shouldLimitFirst()
    {
        // given
        $limit = new ReplaceLimit(function (int $limit) {
            // then
            $this->assertEquals(1, $limit);
            return $this->chain();
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
        $limit = new ReplaceLimit(function (int $limit) {
            // then
            $this->assertEquals(-1, $limit);
            return $this->chain();
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
        $limit = new ReplaceLimit(function (int $limit) {
            // then
            $this->assertEquals(20, $limit);
            return $this->chain();
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
        $limit = new ReplaceLimit(function () {
            return '';
        });

        // then
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Negative limit -2');

        // when
        $limit->only(-2);
    }

    private function chain(): ReplacePattern
    {
        return new ReplacePattern(new Pattern(''), '', 0);
    }
}
