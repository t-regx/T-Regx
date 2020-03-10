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
        // when
        $quoteable = QuotableFactory::quotable('5% you (are|is) welcome');

        // then
        $this->assertEquals('5\% you \(are\|is\) welcome', $quoteable->quote('%'));
    }

    /**
     * @test
     */
    public function shouldQuoteArray()
    {
        // when
        $quoteable = QuotableFactory::quotable(['first 1%', 'second 2%']);

        // then
        $this->assertEquals('first 1\%|second 2\%', $quoteable->quote('%'));
    }

    /**
     * @test
     */
    public function shouldThrowForInvalidType()
    {
        // then
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("Invalid bound value. Expected string, but integer (4) given");

        // when
        QuotableFactory::quotable(4);
    }
}
