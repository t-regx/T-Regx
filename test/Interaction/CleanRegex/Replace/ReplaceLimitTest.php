<?php
namespace Test\Interaction\TRegx\CleanRegex\Replace;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Test\Fakes\CleanRegex\Internal\ThrowSubject;
use Test\Utils\Definitions;
use TRegx\CleanRegex\Internal\StringSubject;
use TRegx\CleanRegex\Match\Details\Detail;
use TRegx\CleanRegex\Replace\ReplaceLimit;

/**
 * @covers \TRegx\CleanRegex\Replace\ReplaceLimit
 */
class ReplaceLimitTest extends TestCase
{
    /**
     * @test
     */
    public function shouldLimit_all()
    {
        // given
        $limit = new ReplaceLimit(Definitions::pcre('/[0-9a-g]/'), new StringSubject('123456789abcdefg'));

        // when
        $limit->all()->callback($this->assertReplaceLimit(-1));
    }

    /**
     * @test
     */
    public function shouldLimit_first()
    {
        // given
        $limit = new ReplaceLimit(Definitions::pcre('/[0-9]/'), new StringSubject('123'));

        // when
        $limit->first()->callback($this->assertReplaceLimit(1));
    }

    /**
     * @test
     */
    public function shouldLimit_only()
    {
        // given
        $limit = new ReplaceLimit(Definitions::pcre('/[0-9]/'), new StringSubject('123'));

        // when
        $limit->only(2)->callback($this->assertReplaceLimit(2));
    }

    /**
     * @test
     */
    public function shouldThrow_only_onNegativeLimit()
    {
        // given
        $limit = new ReplaceLimit(Definitions::pcre('//'), new ThrowSubject());

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
