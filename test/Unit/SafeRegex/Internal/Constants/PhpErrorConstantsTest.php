<?php
namespace Test\Unit\SafeRegex\Internal\Constants;

use PHPUnit\Framework\TestCase;
use TRegx\SafeRegex\Internal\Constants\PhpErrorConstants;

/**
 * @covers \TRegx\SafeRegex\Internal\Constants\PhpErrorConstants
 */
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
        $this->assertSame('E_WARNING', $constant);
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
        $this->assertSame('E_UNKNOWN_CODE', $constant);
    }
}
