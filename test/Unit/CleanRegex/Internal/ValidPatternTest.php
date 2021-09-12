<?php
namespace Test\Unit\TRegx\CleanRegex\Internal;

use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Internal\ValidPattern;
use TRegx\SafeRegex\Internal\Errors\Errors\EmptyHostError;
use TRegx\SafeRegex\Internal\Errors\ErrorsCleaner;

/**
 * @covers \TRegx\CleanRegex\Internal\ValidPattern
 */
class ValidPatternTest extends TestCase
{
    /**
     * @test
     * @dataProvider validStandardPatterns
     * @param string $string
     */
    public function shouldValidateStandardPattern(string $string)
    {
        // when
        $isValid = ValidPattern::isValidStandard($string);

        // then
        $this->assertTrue($isValid);
    }

    public function validStandardPatterns(): array
    {
        return [
            ['~((https?|ftp)://(\S*?\.\S*?))([\s)\[\]{},;"\':<]|\.\s | $)~'],
            ['!exclamation marks!'],
            ['string'],
        ];
    }

    /**
     * @test
     * @dataProvider \Test\DataProviders::invalidStandardPatterns()
     * @param string $string
     */
    public function shouldNotValidateStandardPattern(string $string)
    {
        // when
        $isValid = ValidPattern::isValidStandard($string);

        // then
        $this->assertFalse($isValid);
    }

    /**
     * @test
     */
    public function shouldStandardNotLeaveErrors()
    {
        // given
        $errorsCleaner = new ErrorsCleaner();

        // when
        ValidPattern::isValidStandard('/{2,1}/');

        // then
        $this->assertInstanceOf(EmptyHostError::class, $errorsCleaner->getError());
    }
}
