<?php
namespace Test\Integration\TRegx\CleanRegex\Replace;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Internal\InternalPattern as Pattern;
use TRegx\CleanRegex\Replace\NonReplaced\DefaultStrategy;
use TRegx\CleanRegex\Replace\NonReplaced\ReplacePatternFactory;
use TRegx\CleanRegex\Replace\ReplaceLimitImpl;
use TRegx\CleanRegex\Replace\ReplacePattern;
use TRegx\CleanRegex\Replace\ReplacePatternImpl;
use TRegx\CleanRegex\Replace\SpecificReplacePatternImpl;

class ReplaceLimitImplTest extends TestCase
{
    /**
     * @test
     */
    public function shouldLimitFirst()
    {
        // given
        $limit = new ReplaceLimitImpl(function (int $limit) {
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
        $limit = new ReplaceLimitImpl(function (int $limit) {
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
        $limit = new ReplaceLimitImpl(function (int $limit) {
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
        $limit = new ReplaceLimitImpl(function () {
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
        return new ReplacePatternImpl(
            new SpecificReplacePatternImpl(new Pattern(''), '', 0, new DefaultStrategy()),
            new Pattern(''),
            '',
            0,
            new ReplacePatternFactory()
        );
    }
}
