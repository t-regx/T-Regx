<?php
namespace Test\Feature\TRegx\CleanRegex\Replace\Details\isInt;

use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Replace\Details\ReplaceDetail;
use function pattern;

/**
 * @coversNothing
 */
class ReplacePatternTest extends TestCase
{
    /**
     * @test
     */
    public function shouldBeInteger()
    {
        // given
        pattern('1094')
            ->replace('1094')
            ->first()
            ->callback(function (ReplaceDetail $detail) {
                $this->assertTrue($detail->isInt());

                // cleanup
                return '';
            });
    }

    /**
     * @test
     */
    public function shouldBeIntegerBase11()
    {
        // given
        pattern('9a')
            ->replace('9a')
            ->first()
            ->callback(function (ReplaceDetail $detail) {
                $this->assertTrue($detail->isInt(11));

                // cleanup
                return '';
            });
    }

    /**
     * @test
     */
    public function shouldNotBeIntegerBase10()
    {
        // given
        pattern('a0')
            ->replace('a0')
            ->first()
            ->callback(function (ReplaceDetail $detail) {
                $this->assertFalse($detail->isInt());

                // cleanup
                return '';
            });
    }

    /**
     * @test
     */
    public function shouldNotBeIntegerBase9()
    {
        // given
        pattern('9')
            ->replace('9')
            ->first()
            ->callback(function (ReplaceDetail $detail) {
                $this->assertFalse($detail->isInt(9));

                // cleanup
                return '';
            });
    }
}
