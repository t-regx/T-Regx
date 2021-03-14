<?php
namespace Test\Unit\TRegx\CleanRegex\Internal;

use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Internal\ValidPattern;
use TRegx\SafeRegex\Internal\Errors\Errors\EmptyHostError;
use TRegx\SafeRegex\Internal\Errors\ErrorsCleaner;

class ValidPatternTest extends TestCase
{
    /**
     * @test
     * @dataProvider validPcrePatterns
     * @param string $string
     */
    public function shouldValidatePcrePattern(string $string)
    {
        // when
        $isValid = ValidPattern::isValid($string);

        // then
        $this->assertTrue($isValid);
    }

    public function validPcrePatterns(): array
    {
        return [
            ['~((https?|ftp)://(\S*?\.\S*?))([\s)\[\]{},;"\':<]|\.\s | $)~'],
            ['!exclamation marks!'],
        ];
    }

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
     * @dataProvider \Test\DataProviders::invalidPregPatterns()
     * @param string $string
     */
    public function shouldNotValidatePcrePattern(string $string)
    {
        // when
        $isValid = ValidPattern::isValid($string);

        // then
        $this->assertFalse($isValid);
    }

    /**
     * @test
     * @dataProvider \Test\DataProviders::invalidPregPatterns()
     * @param string $string
     */
    public function shouldPcreNotLeaveErrors(string $string)
    {
        // given
        $errorsCleaner = new ErrorsCleaner();

        // when
        ValidPattern::isValid($string);
        $error = $errorsCleaner->getError();

        // then
        $this->assertInstanceOf(EmptyHostError::class, $error);
        $this->assertFalse($error->occurred());
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
