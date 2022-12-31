<?php
namespace Test\Legacy\SafeRegex;

use PHPUnit\Framework\TestCase;
use TRegx\SafeRegex\Internal\PhpError;

/**
 * @deprecated
 * @covers \TRegx\SafeRegex\Internal\PhpError
 */
class PhpErrorTest extends TestCase
{
    public function testGetters()
    {
        // given
        $error = new PhpError(E_WARNING, 'Something failed');

        // when
        $type = $error->getType();
        $message = $error->getMessage();
        $isPreg = $error->isPregError();

        // then
        $this->assertSame(E_WARNING, $type);
        $this->assertSame('Something failed', $message);
        $this->assertFalse($isPreg);
    }

    /**
     * @test
     */
    public function shouldBePregError(): void
    {
        // given
        $error = new PhpError(0, 'preg_match()');

        // when
        $isPregError = $error->isPregError();

        // then
        $this->assertTrue($isPregError);
    }

    /**
     * @test
     */
    public function shouldNotBePregError(): void
    {
        // given
        $error = new PhpError(0, 'preg_ something');

        // when
        $isPregError = $error->isPregError();

        // then
        $this->assertFalse($isPregError);
    }
}
