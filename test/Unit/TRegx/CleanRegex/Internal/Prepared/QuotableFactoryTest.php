<?php
namespace Test\Unit\TRegx\CleanRegex\Internal\Prepared;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Internal\Prepared\QuotableFactory;

class QuotableFactoryTest extends TestCase
{
    /**
     * @test
     */
    public function shouldCreateQuotableString()
    {
        // given
        $factory = new QuotableFactory();

        // when
        $quoteable = $factory->quotable('5% you (are|is) welcome');

        // then
        $this->assertEquals('5\% you \(are\|is\) welcome', $quoteable->quote('%'));
    }

    /**
     * @test
     */
    public function shouldQuoteArray()
    {
        // given
        $factory = new QuotableFactory();

        // when
        $quoteable = $factory->quotable(['first 1%', 'second 2%']);

        // then
        $this->assertEquals('first 1\%|second 2\%', $quoteable->quote('%'));
    }

    /**
     * @test
     */
    public function shouldThrowForInvalidType()
    {
        // given
        $factory = new QuotableFactory();

        // then
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("Invalid bound value. Expected string, but integer (4) given");

        // when
        $factory->quotable(4);
    }
}
