<?php
namespace Test\Functional\TRegx;

use PHPUnit\Framework\TestCase;
use TRegx\Pcre;
use const PHP_VERSION_ID;

class PcreTest extends TestCase
{
    /**
     * @test
     * @dataProvider pcreVersionDependant
     */
    public function shouldGetPcreVersion(string $expectedVersion, int $expectedMajor, int $expectedMinor, bool $expectedPcre2)
    {
        // when
        $version = Pcre::semanticVersion();
        $major = Pcre::majorVersion();
        $minor = Pcre::minorVersion();
        $pcre2 = Pcre::pcre2();

        // then
        $this->assertSame($expectedVersion, $version);
        $this->assertSame($expectedMajor, $major);
        $this->assertSame($expectedMinor, $minor);
        $this->assertSame($expectedPcre2, $pcre2);
    }

    public function pcreVersionDependant(): array
    {
        if (PHP_VERSION_ID >= 80100) {
            return $this->version(10, 35);
        }
        if (PHP_VERSION_ID >= 80005) {
            return $this->version(10, 34);
        }
        if (PHP_VERSION_ID >= 80003) {
            return $this->version(10, 35);
        }
        if (PHP_VERSION_ID >= 80000) {
            return $this->version(10, 34);
        }
        if (PHP_VERSION_ID >= 70418) {
            return $this->version(10, 34);
        }
        if (PHP_VERSION_ID >= 70412) {
            return $this->version(10, 35);
        }
        if (PHP_VERSION_ID >= 70406) {
            return $this->version(10, 34);
        }
        if (PHP_VERSION_ID >= 70400) {
            return $this->version(10, 33);
        }
        if (PHP_VERSION_ID >= 70328) {
            return $this->version(10, 36);
        }
        if (PHP_VERSION_ID >= 70300) {
            return $this->version(10, 32);
        }
        if (PHP_VERSION_ID >= 70234) {
            return $this->version(8, 44);
        }
        if (PHP_VERSION_ID >= 70200) {
            return $this->version(8, 41);
        }
        if (PHP_VERSION_ID >= 70133) {
            return $this->version(8, 44);
        }
        if (PHP_VERSION_ID >= 70003) {
            return $this->version(8, 38);
        }
        return $this->version(8, 37);
    }

    private function version(int $major, int $minor): array
    {
        return [PHP_VERSION => ["$major.$minor", $major, $minor, $major >= 10]];
    }

    /**
     * @test
     */
    public function shouldNotBeProneToConstantOverride()
    {
        if (PHP_VERSION_ID >= 70300) {
            $this->markTestSkipped("PHP with PCRE2 is not prone to constant override");
        }

        // given
        define('PCRE_VERSION_MAJOR', 1200);
        define('PCRE_VERSION_MINOR', 1300);

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
