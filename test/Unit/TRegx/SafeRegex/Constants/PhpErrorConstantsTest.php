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
        $unknown = E_WARNING;
        $constants = new PhpErrorConstants();

        // when
        $constant = $constants->getConstant($unknown);

        // then
        $this->assertEquals('E_WARNING', $constant);
    }

    /**
     * @test
     */
    public function shouldGetDefault()
    {
        // given
        $unknown = 101;
        $constants = new PhpErrorConstants();

        // when
        $constant = $constants->getConstant($unknown);

        // then
        $this->assertEquals('E_UNKNOWN_CODE', $constant);
    }
}
