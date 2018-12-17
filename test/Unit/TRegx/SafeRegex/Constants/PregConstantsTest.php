<?php
namespace Test\Unit\TRegx\SafeRegex\Constants;

use PHPUnit\Framework\TestCase;
use TRegx\SafeRegex\Constants\PregConstants;

class PregConstantsTest extends TestCase
{
    /**
     * @test
     */
    public function shouldGetConstant()
    {
        // given
        $unknown = PREG_BAD_UTF8_ERROR;
        $constants = new PregConstants();

        // when
        $constant = $constants->getConstant($unknown);

        // then
        $this->assertEquals('PREG_BAD_UTF8_ERROR', $constant);
    }

    /**
     * @test
     */
    public function shouldGetDefault()
    {
        // given
        $unknown = 101;
        $constants = new PregConstants();

        // when
        $constant = $constants->getConstant($unknown);

        // then
        $this->assertEquals('UNKNOWN_PREG_ERROR', $constant);
    }
}
