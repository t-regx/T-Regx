<?php
namespace Test\Unit\TRegx\SafeRegex\Constants;

use PHPUnit\Framework\TestCase;
use TRegx\SafeRegex\Constants\PhpErrorConstants;

class PhpErrorConstantsTest extends TestCase
{
    /**
     * @test
     */
    public function shouldGetConstant()
    {
        // given
        $constants = new PhpErrorConstants();

        // when
        $constant = $constants->getConstant(E_WARNING);

        // then
        $this->assertEquals('E_WARNING', $constant);
    }

    /**
     * @test
     */
    public function shouldGetDefault()
    {
        // given
        $constants = new PhpErrorConstants();

        // when
        $constant = $constants->getConstant(101);

        // then
        $this->assertEquals('E_UNKNOWN_CODE', $constant);
    }
}
