<?php
namespace Test\Interaction\TRegx\CleanRegex\Replace;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Internal\InternalPattern;
use TRegx\CleanRegex\Match\Details\Detail;
use TRegx\CleanRegex\Replace\ReplaceLimit;

class ReplaceLimitTest extends TestCase
{
    /**
     * @test
     */
    public function shouldLimit_all()
    {
        // given
        $limit = new ReplaceLimit(InternalPattern::pcre('/[0-9a-g]/'), '123456789abcdefg');

        // when
        $limit->all()->callback($this->assertReplaceLimit(-1));
    }

    /**
     * @test
     */
    public function shouldLimit_first()
    {
        // given
        $limit = new ReplaceLimit(InternalPattern::pcre('/[0-9]/'), '123');

        // when
        $limit->first()->callback($this->assertReplaceLimit(1));
    }

    /**
     * @test
     */
    public function shouldLimit_only()
    {
        // given
        $limit = new ReplaceLimit(InternalPattern::pcre('/[0-9]/'), '123');

        // when
        $limit->only(2)->callback($this->assertReplaceLimit(2));
    }

    /**
     * @test
     */
    public function shouldLimit_otherwise_all()
    {
        // given
        $limit = new ReplaceLimit(InternalPattern::pcre('/[0-9a-g]/'), '123456789abcdefg');

        // when
        $limit->all()
            ->otherwiseReturning('otherwise')
            ->callback($this->assertReplaceLimit(-1));
    }

    /**
     * @test
     */
    public function shouldLimit_otherwise_first()
    {
        // given
        $limit = new ReplaceLimit(InternalPattern::pcre('/[0-9]/'), '123');

        // when
        $limit->first()
            ->otherwiseReturning('otherwise')
            ->callback($this->assertReplaceLimit(1));
    }

    /**
     * @test
     */
    public function shouldLimit_otherwise_only()
    {
        // given
        $limit = new ReplaceLimit(InternalPattern::pcre('/[0-9]/'), '123');

        // when
        $limit->only(2)
            ->otherwiseReturning('otherwise')
            ->callback($this->assertReplaceLimit(2));
    }

    /**
     * @test
     */
    public function shouldThrow_only_onNegativeLimit()
    {
        // given
        $limit = new ReplaceLimit(InternalPattern::pcre('//'), '');

        // then
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Negative limit: -2');

        // when
        $limit->only(-2);
    }

    public function assertReplaceLimit(int $limit): callable
    {
        return function (Detail $detail) use ($limit) {
            // then
            $this->assertSame($limit, $detail->limit());

            // clean
            return '';
        };
    }
}
