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
        $constants = new PregConstants();

        // when
        $constant = $constants->getConstant(PREG_BAD_UTF8_ERROR);

        // then
        $this->assertEquals('PREG_BAD_UTF8_ERROR', $constant);
    }

    /**
     * @test
     */
    public function shouldGetDefault()
    {
        // given
        $constants = new PregConstants();

        // when
        $constant = $constants->getConstant(101);

        // then
        $this->assertEquals('UNKNOWN_PREG_ERROR', $constant);
    }
}
