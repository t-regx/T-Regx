<?php
namespace Test\Functional\TRegx;

use PHPUnit\Framework\TestCase;
use Test\Utils\TestCaseConditional;
use TRegx\Pcre;

/**
 * @covers \TRegx\Pcre
 */
class PcreTest extends TestCase
{
    use TestCaseConditional;

    /**
     * @test
     */
    public function shouldObeyContract()
    {
        // when
        $version = Pcre::semanticVersion();
        $major = Pcre::majorVersion();
        $minor = Pcre::minorVersion();

        // then
        $this->assertSame($version, "$major.$minor");
    }

    /**
     * @test
     */
    public function shouldGetPcreVersion()
    {
        // when
        $version = Pcre::semanticVersion();

        // then
        $this->assertStringStartsWith($version, \PCRE_VERSION);
    }

    /**
     * @test
     */
    public function shouldBePcre2()
    {
        // when + then
        $this->assertSame(Pcre::pcre2(), \PHP_VERSION_ID >= 70300);
    }

    /**
     * @test
     */
    public function shouldNotBeProneToConstantOverride()
    {
        if (\PHP_VERSION_ID >= 70300) {
            $this->markTestUnnecessary("PHP with PCRE2 is not prone to constant override");
        }

        // given
        \define('PCRE_VERSION_MAJOR', 1200);
        \define('PCRE_VERSION_MINOR', 1300);

        // when
        $semantic = Pcre::semanticVersion();
        $minor = Pcre::minorVersion();
        $major = Pcre::majorVersion();
        $pcre2 = Pcre::pcre2();

        // then
        $this->assertLessThan(1100, $major);
        $this->assertLessThan(1100, $minor);
        $this->assertFalse($pcre2);
        $this->assertNotSame('1200.1300', $semantic);
    }
}
